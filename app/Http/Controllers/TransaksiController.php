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
        $transaksi = Transaksi::with('metodePembayaran')
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

        return view('transactions.transaksi', compact('transaksi', 'filters'));
    }

    public function adminIndex(Request $request)
    {
        $transaksi = Transaksi::with(['user', 'metodePembayaran'])
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

        return view('transactions.transaksiAdmin', compact('transaksi', 'filters'));
    }

    public function adminShow(Transaksi $transaksi)
    {
        $transaksi->load(['detailTransaksi.produk', 'user', 'metodePembayaran']);

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

        $transaksi->update([
            'status_transaksi' => 'Selesai',
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi diterima.');
    }
}
