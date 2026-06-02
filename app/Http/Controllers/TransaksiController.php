<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Kabupaten;
use App\Models\KatalogProduk;
use App\Models\Kecamatan;
use App\Models\KodePos;
use App\Models\Penerima;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $transaksi = Transaksi::query()
            ->where('user_id', $user->id)
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '>=', $request->string('start_date')->toString());
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '<=', $request->string('end_date')->toString());
            })
            ->orderByDesc('tanggal_transaksi')
            ->orderByDesc('id')
            ->get();

        $filters = [
            'start_date' => $request->string('start_date')->toString(),
            'end_date' => $request->string('end_date')->toString(),
        ];

        return view('transactions.HalamanRiwayatTransaksiU', compact('transaksi', 'filters'));
    }

    public function adminIndex(Request $request)
    {
        $produkOptions = KatalogProduk::query()
            ->orderBy('nama_produk')
            ->get(['id', 'nama_produk', 'harga', 'stok']);

        $transaksi = Transaksi::with(['user', 'penerima.kodePos', 'penerima.kecamatan', 'penerima.kabupaten', 'penerima.provinsi', 'detailTransaksi.produk'])
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '>=', $request->string('start_date')->toString());
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '<=', $request->string('end_date')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status_transaksi', $request->string('status')->toString());
            })
            ->orderByDesc('tanggal_transaksi')
            ->orderByDesc('id')
            ->get();

        $filters = [
            'start_date' => $request->string('start_date')->toString(),
            'end_date' => $request->string('end_date')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        return view('transactions.HalamanRiwayatTransaksi', compact('transaksi', 'filters', 'produkOptions'));
    }

    public function adminShow(Transaksi $transaksi)
    {
        $transaksi->load(['detailTransaksi.produk', 'user']);

        return view('transactions.InvoicePembayaran', [
            'transaksi' => $transaksi,
            'snapToken' => null,
            'snapRedirectUrl' => null,
            'midtransClientKey' => config('services.midtrans.client_key'),
            'midtransSnapScriptUrl' => app(MidtransService::class)->getSnapScriptUrl(),
            'paymentError' => null,
            'isAdminView' => true,
        ]);
    }

    public function approveByAdmin(Transaksi $transaksi)
    {
        if ($transaksi->status_transaksi !== 'Menunggu Konfirmasi') {
            return back()->withErrors([
                'checkout' => 'Pesanan ini sudah diproses admin.',
            ]);
        }

        $transaksi->update([
            'status_transaksi' => 'Dikonfirmasi',
            'catatan_admin' => null,
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi. User sekarang bisa melanjutkan ke pembayaran.');
    }

    public function rejectByAdmin(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->status_transaksi !== 'Menunggu Konfirmasi') {
            return back()->withErrors([
                'checkout' => 'Pesanan ini sudah tidak berada di tahap konfirmasi admin.',
            ]);
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ], [
            'alasan_penolakan.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $transaksi->update([
            'status_transaksi' => 'Dibatalkan',
            'status_pembayaran' => 'dibatalkan',
            'catatan_admin' => $validated['alasan_penolakan'],
        ]);

        return back()->with('success', 'Pesanan berhasil dibatalkan dan alasan pembatalan sudah disimpan.');
    }

    public function markAsReceived(Request $request, Transaksi $transaksi)
    {
        abort_unless($transaksi->user_id === $request->user()->id, 404);

        if ($transaksi->status_transaksi !== 'Diproses') {
            return back()->withErrors([
                'checkout' => 'Pesanan belum bisa diselesaikan sekarang.',
            ]);
        }

        $validated = $request->validate([
            'foto_pesanan_selesai' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ], [
            'foto_pesanan_selesai.required' => 'Foto penerimaan pesanan wajib diisi.',
            'foto_pesanan_selesai.image' => 'Foto penerimaan pesanan harus berupa gambar.',
            'foto_pesanan_selesai.mimes' => 'Foto penerimaan pesanan harus berupa JPG, JPEG, PNG, WEBP, atau GIF.',
            'foto_pesanan_selesai.max' => 'Foto penerimaan pesanan tidak boleh lebih dari 2MB.',
        ]);

        if ($transaksi->foto_pesanan_selesai) {
            Storage::disk('public')->delete($transaksi->foto_pesanan_selesai);
        }

        $transaksi->update([
            'status_transaksi' => 'Selesai',
            'foto_pesanan_selesai' => $request->file('foto_pesanan_selesai')->store('transaksi-selesai', 'public'),
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi diterima.');
    }

    public function storeOffline(Request $request)
    {
        $validated = $this->validateOfflinePayload($request);
        $groupedItems = $this->normalizeOfflineItems($validated['items']);
        $transaksi = $this->persistOfflineTransaction($request, $validated, $groupedItems);

        return redirect()
            ->route('admin.transaksi')
            ->with('success', 'Transaksi offline berhasil dicatat dengan nomor pesanan #' . $transaksi->id . '.');
    }

    public function updateOffline(Request $request, Transaksi $transaksi)
    {
        if (!$transaksi->is_offline) {
            return back()->withErrors([
                'checkout' => 'Transaksi online tidak bisa diedit dari form offline.',
            ]);
        }

        $validated = $this->validateOfflinePayload($request);
        $groupedItems = $this->normalizeOfflineItems($validated['items']);
        $updatedTransaksi = $this->persistOfflineTransaction($request, $validated, $groupedItems, $transaksi);

        return redirect()
            ->route('admin.transaksi')
            ->with('success', 'Transaksi offline #' . $updatedTransaksi->id . ' berhasil diperbarui.');
    }

    private function validateOfflinePayload(Request $request): array
    {
        return $request->validate([
            'nama_penerima' => 'required|string|max:100',
            'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|in:qris,cod,bank_transfer',
            'no_telp_penerima' => 'required|string|max:15',
            'detail_alamat' => 'required|string',
            'nomor_kode_pos' => 'required|string|max:5',
            'nama_kecamatan' => 'required|string|max:100',
            'nama_kabupaten' => 'required|string|max:100',
            'nama_provinsi' => 'required|string|max:100',
            'resi' => 'nullable|string|max:100',
            'ongkir' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:katalog_produk,id',
            'items.*.jumlah_produk' => 'required|integer|min:1|max:9999',
        ], [
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'no_telp_penerima.required' => 'Nomor telepon penerima wajib diisi.',
            'detail_alamat.required' => 'Alamat wajib diisi.',
            'nomor_kode_pos.required' => 'Kode pos wajib diisi.',
            'nama_kecamatan.required' => 'Kecamatan wajib diisi.',
            'nama_kabupaten.required' => 'Kabupaten wajib diisi.',
            'nama_provinsi.required' => 'Provinsi wajib diisi.',
            'ongkir.required' => 'Ongkir wajib diisi.',
            'items.required' => 'Minimal satu produk wajib dipilih.',
            'items.*.produk_id.required' => 'Produk wajib dipilih.',
            'items.*.jumlah_produk.required' => 'Jumlah produk wajib diisi.',
        ]);
    }

    private function normalizeOfflineItems(array $items)
    {
        $normalizedItems = collect($items)
            ->map(fn (array $item) => [
                'produk_id' => (int) $item['produk_id'],
                'jumlah_produk' => (int) $item['jumlah_produk'],
            ]);

        return $normalizedItems
            ->groupBy(fn (array $item) => $item['produk_id'])
            ->map(function ($groupedItems, $produkId) {
                $jumlahProduk = (int) $groupedItems->sum(fn (array $item) => (int) $item['jumlah_produk']);

                if ($jumlahProduk < 1 || $jumlahProduk > 9999) {
                    throw ValidationException::withMessages([
                        'items' => 'Jumlah produk tidak valid.',
                    ]);
                }

                return [
                    'produk_id' => (int) $produkId,
                    'jumlah_produk' => $jumlahProduk,
                ];
            })
            ->values();
    }

    private function persistOfflineTransaction(Request $request, array $validated, $groupedItems, ?Transaksi $transaksi = null): Transaksi
    {
        return DB::transaction(function () use ($request, $validated, $groupedItems, $transaksi) {
            $existingDetailItems = collect();

            if ($transaksi) {
                $transaksi->load('detailTransaksi', 'penerima');
                $existingDetailItems = $transaksi->detailTransaksi;
            }

            $produkIds = $groupedItems->pluck('produk_id')
                ->merge($existingDetailItems->pluck('produk_id'))
                ->unique()
                ->values();

            $produkMap = KatalogProduk::query()
                ->whereIn('id', $produkIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($existingDetailItems as $existingItem) {
                $produkLama = $produkMap->get($existingItem->produk_id);

                if ($produkLama) {
                    $produkLama->increment('stok', (int) $existingItem->jumlah_produk);
                }
            }

            foreach ($groupedItems as $item) {
                $produk = $produkMap->get($item['produk_id']);

                if (!$produk) {
                    throw ValidationException::withMessages([
                        'items' => 'Produk yang dipilih tidak ditemukan.',
                    ]);
                }

                if ((int) $item['jumlah_produk'] > (int) $produk->stok) {
                    throw ValidationException::withMessages([
                        'items' => "Stok produk {$produk->nama_produk} tidak cukup. Sisa stok saat ini: {$produk->stok}.",
                    ]);
                }
            }

            $kodePos = KodePos::firstOrCreate([
                'nomor_kode_pos' => $validated['nomor_kode_pos'],
            ]);

            $provinsi = Provinsi::firstOrCreate([
                'nama_provinsi' => $validated['nama_provinsi'],
            ]);

            $kabupaten = Kabupaten::firstOrCreate([
                'provinsi_id' => $provinsi->id,
                'nama_kabupaten' => $validated['nama_kabupaten'],
            ]);

            $kecamatan = Kecamatan::firstOrCreate([
                'kabupaten_id' => $kabupaten->id,
                'nama_kecamatan' => $validated['nama_kecamatan'],
            ], [
                'kode_pos_id' => $kodePos->id,
            ]);

            if (!$kecamatan->kode_pos_id) {
                $kecamatan->update([
                    'kode_pos_id' => $kodePos->id,
                ]);
            }

            if ($transaksi && $transaksi->penerima) {
                $penerima = tap($transaksi->penerima)->update([
                    'provinsi_id' => $provinsi->id,
                    'kabupaten_id' => $kabupaten->id,
                    'kecamatan_id' => $kecamatan->id,
                    'kode_pos_id' => $kodePos->id,
                    'nama_penerima' => $validated['nama_penerima'],
                    'no_telp_penerima' => $validated['no_telp_penerima'],
                    'detail_alamat' => $validated['detail_alamat'],
                ]);
                $penerima = $transaksi->penerima->fresh();
            } else {
                $penerima = Penerima::create([
                    'provinsi_id' => $provinsi->id,
                    'kabupaten_id' => $kabupaten->id,
                    'kecamatan_id' => $kecamatan->id,
                    'kode_pos_id' => $kodePos->id,
                    'nama_penerima' => $validated['nama_penerima'],
                    'no_telp_penerima' => $validated['no_telp_penerima'],
                    'detail_alamat' => $validated['detail_alamat'],
                ]);
            }

            if ($transaksi) {
                $transaksi->update([
                    'penerima_id' => $penerima->id,
                    'tanggal_transaksi' => $validated['tanggal_transaksi'],
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_transaksi' => 'Selesai',
                    'status_pembayaran' => 'paid',
                    'catatan_admin' => 'Transaksi offline toko',
                    'resi' => $validated['resi'] ?: null,
                    'ongkir' => (int) $validated['ongkir'],
                    'midtrans_order_id' => null,
                ]);

                $transaksi->detailTransaksi()->delete();
            } else {
                $transaksi = Transaksi::create([
                    'user_id' => $request->user()->id,
                    'penerima_id' => $penerima->id,
                    'tanggal_transaksi' => $validated['tanggal_transaksi'],
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_transaksi' => 'Selesai',
                    'status_pembayaran' => 'paid',
                    'catatan_admin' => 'Transaksi offline toko',
                    'resi' => $validated['resi'] ?: null,
                    'ongkir' => (int) $validated['ongkir'],
                    'midtrans_order_id' => null,
                ]);
            }

            foreach ($groupedItems as $item) {
                $produk = $produkMap->get($item['produk_id']);

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk->id,
                    'jumlah_produk' => $item['jumlah_produk'],
                    'harga_produk' => (int) $produk->harga,
                    'subtotal_produk' => (int) $produk->harga * (int) $item['jumlah_produk'],
                ]);

                $produk->decrement('stok', (int) $item['jumlah_produk']);
            }

            return $transaksi->fresh(['detailTransaksi.produk', 'penerima']);
        });
    }
}
