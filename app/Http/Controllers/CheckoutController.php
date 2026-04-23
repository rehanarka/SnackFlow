<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\KodePos;
use App\Models\Penerima;
use App\Models\Provinsi;
use App\Models\Transaksi;
use App\Services\MidtransService;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(private readonly RajaOngkirService $rajaOngkirService)
    {
    }

    private function filterJneRegularRates(array $rates): array
    {
        return collect($rates)
            ->filter(function ($rate) {
                if (!is_array($rate)) {
                    return false;
                }

                $code = strtolower((string) ($rate['code'] ?? $rate['courier'] ?? ''));
                $service = strtoupper((string) ($rate['service'] ?? $rate['service_name'] ?? ''));

                return $code === 'jne' && $service === 'REG';
            })
            ->values()
            ->all();
    }

    public function index(Request $request)
    {
        $keranjangItems = $request->user()->keranjang()->with('produk')->orderByDesc('id')->get();
        $subtotal = $keranjangItems->sum(fn ($item) => ($item->produk->harga ?? 0) * $item->jumlah_produk);
        $totalBerat = $keranjangItems->sum(fn ($item) => ($item->produk->berat ?? 0) * $item->jumlah_produk);
        $rajaongkirRates = $this->filterJneRegularRates(session('rajaongkir_rates', []));
        $selectedDestination = session('rajaongkir_selected_destination');
        $selectedShipping = session('rajaongkir_selected_shipping');
        $checkoutForm = session('checkout_form', []);

        if ($rajaongkirRates !== session('rajaongkir_rates', [])) {
            $request->session()->put('rajaongkir_rates', $rajaongkirRates);
        }

        $selectedShippingCode = strtolower((string) ($selectedShipping['code'] ?? $selectedShipping['courier_code'] ?? 'jne'));
        $selectedShippingService = strtoupper((string) ($selectedShipping['service_pengiriman'] ?? $selectedShipping['service'] ?? ''));

        if ($selectedShipping && !($selectedShippingCode === 'jne' && $selectedShippingService === 'REG')) {
            $selectedShipping = null;
            $request->session()->forget('rajaongkir_selected_shipping');
        }

        $estimatedTotal = $subtotal + (int) ($selectedShipping['ongkir'] ?? 0);
        $originLocation = null;

        try {
            $originLocation = $this->rajaOngkirService->getConfiguredOrigin();
        } catch (Throwable) {
            $originLocation = null;
        }

        return view('transactions.checkout', compact(
            'keranjangItems',
            'subtotal',
            'totalBerat',
            'rajaongkirRates',
            'selectedDestination',
            'selectedShipping',
            'checkoutForm',
            'estimatedTotal',
            'originLocation'
        ));
    }

    public function rates(Request $request)
    {
        $keranjangItems = $request->user()->keranjang()->with('produk')->orderByDesc('id')->get();

        if ($keranjangItems->isEmpty()) {
            $message = 'Keranjang masih kosong. Tambahkan produk terlebih dahulu sebelum cek ongkir.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->withErrors([
                'checkout' => $message,
            ]);
        }

        $validated = $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'no_telp_penerima' => 'required|string|max:30',
            'alamat_penerima' => 'required|string',
            'selected_destination_id' => 'required',
            'selected_destination_label' => 'required|string',
            'selected_destination_postal_code' => 'nullable|string|max:10',
            'selected_destination_province' => 'nullable|string|max:255',
            'selected_destination_city' => 'nullable|string|max:255',
            'selected_destination_district' => 'nullable|string|max:255',
            'selected_destination_subdistrict' => 'nullable|string|max:255',
        ], [
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'no_telp_penerima.required' => 'No. telp penerima wajib diisi.',
            'alamat_penerima.required' => 'Alamat penerima wajib diisi.',
            'selected_destination_id.required' => 'Tujuan pengiriman wajib dipilih.',
            'selected_destination_label.required' => 'Tujuan pengiriman wajib dipilih.',
        ]);

        $selectedDestination = [
            'id' => $validated['selected_destination_id'],
            'label' => $validated['selected_destination_label'],
            'postal_code' => $validated['selected_destination_postal_code'] ?? null,
            'province_name' => $validated['selected_destination_province'] ?? null,
            'city_name' => $validated['selected_destination_city'] ?? null,
            'district_name' => $validated['selected_destination_district'] ?? null,
            'subdistrict_name' => $validated['selected_destination_subdistrict'] ?? null,
        ];

        $request->session()->put('checkout_form', [
            'nama_penerima' => $validated['nama_penerima'],
            'no_telp_penerima' => $validated['no_telp_penerima'],
            'alamat_penerima' => $validated['alamat_penerima'],
        ]);
        $request->session()->put('rajaongkir_selected_destination', $selectedDestination);

        try {
            $rates = $this->filterJneRegularRates(
                $this->rajaOngkirService->calculateDomesticCost((int) $selectedDestination['id'], $keranjangItems)
            );
        } catch (Throwable $th) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $th->getMessage()], 422);
            }

            return back()->withInput()->withErrors([
                'checkout' => $th->getMessage(),
            ]);
        }

        $request->session()->put('rajaongkir_rates', $rates);
        $request->session()->forget('rajaongkir_selected_shipping');

        if (empty($rates)) {
            $message = 'Tidak ada layanan pengiriman yang tersedia untuk origin, tujuan, dan berat paket ini.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'rates' => [],
                    'selected_destination' => $selectedDestination,
                ], 422);
            }

            return back()->withInput()->withErrors([
                'checkout' => $message,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Opsi pengiriman berhasil dimuat. Sekarang pilih kurir yang paling cocok.',
                'rates' => $rates,
                'selected_destination' => $selectedDestination,
            ]);
        }

        return back()->withInput()->with('success', 'Opsi pengiriman berhasil dimuat. Sekarang pilih kurir yang paling cocok.');
    }

    public function autocompleteDestination(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string|min:3|max:255',
        ]);

        try {
            $destinations = $this->rajaOngkirService->searchDomesticDestinations($validated['q']);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'data' => [],
            ], 422);
        }

        $formatted = collect($destinations)->map(function ($destination) {
            $label = $destination['label'] ?? trim(collect([
                $destination['subdistrict_name'] ?? null,
                $destination['district_name'] ?? null,
                $destination['city_name'] ?? null,
                $destination['province_name'] ?? null,
            ])->filter()->implode(', '));

            return [
                'id' => $destination['id'] ?? null,
                'label' => $label,
                'postal_code' => $destination['zip_code'] ?? ($destination['postal_code'] ?? ''),
                'province_name' => $destination['province_name'] ?? null,
                'city_name' => $destination['city_name'] ?? null,
                'district_name' => $destination['district_name'] ?? null,
                'subdistrict_name' => $destination['subdistrict_name'] ?? null,
            ];
        })->filter(fn ($destination) => !empty($destination['id']) && !empty($destination['label']))
            ->values()
            ->all();

        return response()->json([
            'data' => $formatted,
        ]);
    }

    public function selectShipping(Request $request)
    {
        $validated = $request->validate([
            'ongkir' => 'required|integer|min:0',
            'kurir' => 'required|string|max:100',
            'service_pengiriman' => 'required|string|max:150',
            'estimasi_pengiriman' => 'nullable|string|max:100',
        ], [
            'ongkir.required' => 'Nilai ongkir wajib ada.',
            'kurir.required' => 'Kurir wajib dipilih.',
            'service_pengiriman.required' => 'Layanan pengiriman wajib dipilih.',
        ]);

        $checkoutForm = $request->session()->get('checkout_form');
        $selectedDestination = $request->session()->get('rajaongkir_selected_destination');
        $keranjangItems = $request->user()->keranjang()->with('produk')->orderByDesc('id')->get();

        if (!$checkoutForm) {
            return redirect()->route('user.checkout')->withErrors([
                'checkout' => 'Isi data penerima dan cek ongkir dulu sebelum memilih kurir.',
            ]);
        }

        if (!$selectedDestination) {
            return redirect()->route('user.checkout')->withErrors([
                'checkout' => 'Pilih tujuan pengiriman dulu sebelum memilih kurir.',
            ]);
        }

        if ($keranjangItems->isEmpty()) {
            return redirect()->route('user.checkout')->withErrors([
                'checkout' => 'Keranjang masih kosong. Tambahkan produk terlebih dahulu sebelum memilih kurir.',
            ]);
        }

        $request->session()->put('rajaongkir_selected_shipping', [
            'ongkir' => $validated['ongkir'],
            'kurir' => $validated['kurir'],
            'service_pengiriman' => $validated['service_pengiriman'],
            'estimasi_pengiriman' => $validated['estimasi_pengiriman'] ?? '-',
        ]);
        $request->session()->forget('rajaongkir_rates');

        return redirect()->route('user.checkout')->with('success', 'Kurir berhasil dipilih. Lanjutkan dengan tombol Checkout untuk mengirim pesanan ke admin.');
    }

    public function proceedToPayment(Request $request)
    {
        $checkoutForm = $request->session()->get('checkout_form');
        $selectedDestination = $request->session()->get('rajaongkir_selected_destination');
        $selectedShipping = $request->session()->get('rajaongkir_selected_shipping');
        $keranjangItems = $request->user()->keranjang()->with('produk')->orderByDesc('id')->get();

        if (!$checkoutForm || !$selectedDestination || !$selectedShipping) {
            return redirect()->route('user.checkout')->withErrors([
                'checkout' => 'Lengkapi data checkout dan pilih kurir dulu sebelum lanjut ke pembayaran.',
            ]);
        }

        if ($keranjangItems->isEmpty()) {
            return redirect()->route('user.checkout')->withErrors([
                'checkout' => 'Keranjang masih kosong. Tambahkan produk terlebih dahulu sebelum checkout.',
            ]);
        }

        $subtotal = $keranjangItems->sum(fn ($item) => ($item->produk->harga ?? 0) * $item->jumlah_produk);

        $transaksi = DB::transaction(function () use ($request, $checkoutForm, $selectedDestination, $selectedShipping, $keranjangItems, $subtotal) {
            $kodePos = null;
            $provinsi = null;
            $kabupaten = null;
            $kecamatan = null;

            if (!empty($selectedDestination['postal_code'])) {
                $kodePos = KodePos::firstOrCreate([
                    'nomor_kode_pos' => $selectedDestination['postal_code'],
                ]);
            }

            if (!empty($selectedDestination['province_name'])) {
                $provinsi = Provinsi::firstOrCreate([
                    'nama_provinsi' => $selectedDestination['province_name'],
                ]);
            }

            if ($provinsi && !empty($selectedDestination['city_name'])) {
                $kabupaten = Kabupaten::firstOrCreate([
                    'provinsi_id' => $provinsi->id,
                    'nama_kabupaten' => $selectedDestination['city_name'],
                ]);
            }

            $namaKecamatan = $selectedDestination['district_name'] ?? $selectedDestination['subdistrict_name'] ?? null;

            if ($kabupaten && !empty($namaKecamatan)) {
                $kecamatan = Kecamatan::firstOrCreate([
                    'kabupaten_id' => $kabupaten->id,
                    'nama_kecamatan' => $namaKecamatan,
                ], [
                    'kode_pos_id' => $kodePos?->id,
                ]);

                if ($kodePos && !$kecamatan->kode_pos_id) {
                    $kecamatan->update([
                        'kode_pos_id' => $kodePos->id,
                    ]);
                }
            }

            $penerima = Penerima::firstOrCreate([
                'provinsi_id' => $provinsi?->id,
                'kabupaten_id' => $kabupaten?->id,
                'kecamatan_id' => $kecamatan?->id,
                'kode_pos_id' => $kodePos?->id,
                'nama_penerima' => $checkoutForm['nama_penerima'],
                'no_telp_penerima' => $checkoutForm['no_telp_penerima'],
                'detail_alamat' => $checkoutForm['alamat_penerima'],
            ]);

            $transaksi = Transaksi::create([
                'user_id' => $request->user()->id,
                'penerima_id' => $penerima->id,
                'ongkir' => $selectedShipping['ongkir'],
                'tanggal_transaksi' => now(),
                'status_transaksi' => 'Menunggu Konfirmasi',
                'status_pembayaran' => 'pending',
            ]);

            foreach ($keranjangItems as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item->produk_id,
                    'jumlah_produk' => $item->jumlah_produk,
                    'harga_produk' => $item->produk->harga ?? 0,
                    'subtotal_produk' => ($item->produk->harga ?? 0) * $item->jumlah_produk,
                ]);
            }

            $request->user()->keranjang()->delete();
            $request->user()->keranjangUtama()->delete();

            return $transaksi->fresh(['detailTransaksi.produk']);
        });

        $request->session()->forget([
            'checkout_form',
            'rajaongkir_selected_destination',
            'rajaongkir_selected_shipping',
            'rajaongkir_rates',
        ]);

        return redirect()
            ->route('user.katalog')
            ->with('checkout_success', [
                'transaction_id' => $transaksi->id,
                'message' => 'Pesanan berhasil dibuat dan sekarang menunggu konfirmasi dari admin.',
            ]);
    }

    public function payment(Request $request, Transaksi $transaksi, MidtransService $midtransService)
    {
        abort_unless($transaksi->user_id === $request->user()->id, 404);

        $transaksi->load(['detailTransaksi.produk', 'user']);
        $snapToken = null;
        $snapRedirectUrl = null;
        $paymentError = null;

        if ($transaksi->status_pesanan === 'Dikonfirmasi') {
            try {
                $snapTransaction = $midtransService->createOrGetSnapTransaction($transaksi);
                $snapToken = $snapTransaction['token'] ?? null;
                $snapRedirectUrl = $snapTransaction['redirect_url'] ?? null;
                $transaksi->refresh();
            } catch (Throwable $th) {
                $paymentError = $th->getMessage();
            }
        }

        return view('transactions.payment', [
            'transaksi' => $transaksi,
            'snapToken' => $snapToken,
            'snapRedirectUrl' => $snapRedirectUrl,
            'midtransClientKey' => config('services.midtrans.client_key'),
            'midtransSnapScriptUrl' => $midtransService->getSnapScriptUrl(),
            'paymentError' => $paymentError,
        ]);
    }

    public function refreshPaymentStatus(Request $request, Transaksi $transaksi, MidtransService $midtransService)
    {
        abort_unless($transaksi->user_id === $request->user()->id, 404);

        if ($transaksi->status_pesanan !== 'Dikonfirmasi') {
            return redirect()
                ->route('user.checkout.payment', $transaksi)
                ->withErrors([
                    'checkout' => 'Pembayaran belum bisa dilakukan sebelum pesanan dikonfirmasi admin.',
                ]);
        }

        try {
            $midtransService->syncTransactionStatus($transaksi);

            return redirect()
                ->route('user.checkout.payment', $transaksi)
                ->with('success', 'Status pembayaran berhasil diperbarui dari Midtrans.');
        } catch (Throwable $th) {
            return redirect()
                ->route('user.checkout.payment', $transaksi)
                ->withErrors([
                    'checkout' => 'Gagal memperbarui status pembayaran: ' . $th->getMessage(),
                ]);
        }
    }
}
