<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(private readonly RajaOngkirService $rajaOngkirService)
    {
    }

    public function index(Request $request)
    {
        $keranjangItems = $request->user()->keranjang()->with('produk')->latest()->get();
        $subtotal = $keranjangItems->sum(fn ($item) => ($item->produk->harga ?? 0) * $item->jumlah_produk);
        $totalBerat = $keranjangItems->sum(fn ($item) => ($item->produk->berat ?? 0) * $item->jumlah_produk);
        $rajaongkirRates = session('rajaongkir_rates', []);
        $selectedDestination = session('rajaongkir_selected_destination');
        $selectedShipping = session('rajaongkir_selected_shipping');
        $checkoutForm = session('checkout_form', []);
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
        $keranjangItems = $request->user()->keranjang()->with('produk')->latest()->get();

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
        ];

        $request->session()->put('checkout_form', [
            'nama_penerima' => $validated['nama_penerima'],
            'no_telp_penerima' => $validated['no_telp_penerima'],
            'alamat_penerima' => $validated['alamat_penerima'],
        ]);
        $request->session()->put('rajaongkir_selected_destination', $selectedDestination);

        try {
            $rates = $this->rajaOngkirService->calculateDomesticCost((int) $selectedDestination['id'], $keranjangItems);
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
        $keranjangItems = $request->user()->keranjang()->with('produk')->latest()->get();

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

        return redirect()->route('user.checkout')->with('success', 'Kurir berhasil dipilih. Lanjutkan dengan tombol Checkout untuk masuk ke halaman pembayaran.');
    }

    public function proceedToPayment(Request $request)
    {
        $checkoutForm = $request->session()->get('checkout_form');
        $selectedDestination = $request->session()->get('rajaongkir_selected_destination');
        $selectedShipping = $request->session()->get('rajaongkir_selected_shipping');
        $keranjangItems = $request->user()->keranjang()->with('produk')->latest()->get();

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
            $transaksi = Transaksi::create([
                'id_user' => $request->user()->id,
                'nama_penerima' => $checkoutForm['nama_penerima'],
                'no_telp_penerima' => $checkoutForm['no_telp_penerima'],
                'alamat_penerima' => $checkoutForm['alamat_penerima'],
                'kode_pos_penerima' => $selectedDestination['postal_code'] ?? null,
                'rajaongkir_destination_id' => $selectedDestination['id'] ?? null,
                'rajaongkir_destination_label' => $selectedDestination['label'] ?? null,
                'subtotal' => $subtotal,
                'ongkir' => $selectedShipping['ongkir'],
                'total_bayar' => $subtotal + $selectedShipping['ongkir'],
                'kurir' => $selectedShipping['kurir'],
                'service_pengiriman' => $selectedShipping['service_pengiriman'],
                'estimasi_pengiriman' => $selectedShipping['estimasi_pengiriman'] ?? '-',
                'status_pesanan' => 'menunggu_pembayaran',
                'status_pembayaran' => 'pending',
            ]);

            foreach ($keranjangItems as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_produk' => $item->id_produk,
                    'jumlah_produk' => $item->jumlah_produk,
                    'harga_produk' => $item->produk->harga ?? 0,
                    'subtotal_produk' => ($item->produk->harga ?? 0) * $item->jumlah_produk,
                ]);
            }

            $request->user()->keranjang()->delete();

            return $transaksi->fresh(['detailTransaksi.produk']);
        });

        $request->session()->forget([
            'checkout_form',
            'rajaongkir_selected_destination',
            'rajaongkir_selected_shipping',
            'rajaongkir_rates',
        ]);

        return redirect()->route('user.checkout.payment', $transaksi);
    }

    public function payment(Request $request, Transaksi $transaksi)
    {
        abort_unless($transaksi->id_user === $request->user()->id, 404);

        $transaksi->load(['detailTransaksi.produk']);

        return view('transactions.payment', [
            'transaksi' => $transaksi,
        ]);
    }
}
