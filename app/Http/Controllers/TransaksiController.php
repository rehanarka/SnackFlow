<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Services\MidtransService;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $transaksi = Transaksi::where('id_user', $user->id)
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->string('start_date')->toString());
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->string('end_date')->toString());
            })
            ->latest()
            ->get();

        $filters = [
            'start_date' => $request->string('start_date')->toString(),
            'end_date' => $request->string('end_date')->toString(),
        ];

        return view('transactions.transaksi', compact('transaksi', 'filters'));
    }

    public function adminIndex(Request $request)
    {
        $transaksi = Transaksi::with('user')
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->string('start_date')->toString());
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->string('end_date')->toString());
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status_pesanan', $request->string('status')->toString());
            })
            ->latest()
            ->get();

        $filters = [
            'start_date' => $request->string('start_date')->toString(),
            'end_date' => $request->string('end_date')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        return view('transactions.transaksiAdmin', compact('transaksi', 'filters'));
    }

    public function adminShow(Transaksi $transaksi)
    {
        $transaksi->load(['detailTransaksi.produk', 'user']);

        return view('transactions.payment', [
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
        if ($transaksi->status_pesanan !== 'Menunggu Konfirmasi') {
            return back()->withErrors([
                'checkout' => 'Pesanan sudah diterima.',
            ]);
        }

        $transaksi->update([
            'status_pesanan' => 'Menunggu Pembayaran',
            'alasan_penolakan' => null,
        ]);

        return back()->with('success', 'Pesanan berhasil diterima. User sekarang bisa melanjutkan ke pembayaran.');
    }

    public function rejectByAdmin(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->status_pesanan !== 'Menunggu Konfirmasi') {
            return back()->withErrors([
                'checkout' => 'Pesanan ini sudah tidak berada di tahap konfirmasi admin.',
            ]);
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $transaksi->update([
            'status_pesanan' => 'Ditolak',
            'status_pembayaran' => 'dibatalkan',
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        return back()->with('success', 'Pesanan berhasil ditolak dan alasan penolakan sudah disimpan.');
    }
}
