@extends('layouts.normal')

@section('content')
@php
    $isAdminView = $isAdminView ?? false;
    $canPay = !$isAdminView
        && $transaksi->status_pesanan === 'Menunggu Pembayaran'
        && !in_array($transaksi->status_pembayaran, ['paid', 'settlement'], true);
    $paymentTypeLabels = [
        'bank_transfer' => 'Bank Transfer',
        'qris' => 'QRIS',
        'gopay' => 'GoPay',
        'shopeepay' => 'ShopeePay',
        'echannel' => 'Mandiri Bill',
        'cstore' => 'Convenience Store',
        'akulaku' => 'Akulaku',
    ];
    $statusNotice = match ($transaksi->status_pesanan) {
        'Menunggu Konfirmasi' => 'Pesanan sudah diterima sistem dan sekarang menunggu konfirmasi dari admin. Pembayaran akan dibuka setelah admin menerima pesanan ini.',
        'Menunggu Pembayaran' => 'Pesanan sudah diterima admin. Kamu bisa lanjut ke pembayaran dari halaman ini.',
        'Diproses' => 'Pembayaran sudah diterima dan pesanan sedang diproses.',
        'Ditolak' => 'Pesanan ditolak oleh admin. Silakan cek alasan penolakan di bawah.',
        default => 'Lihat detail penerima, pengiriman, item pesanan, dan status pembayarannya dalam satu halaman.',
    };
@endphp
<div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.10),_transparent_32%),linear-gradient(180deg,_#f8fbff_0%,_#eef4fb_100%)] px-4 py-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $isAdminView ? 'Detail Pesanan Admin' : 'Detail Pesanan' }}</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Ringkasan Pesanan #{{ $transaksi->id }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ $isAdminView ? 'Tinjau data pemesan, pengiriman, item pesanan, dan status pembayaran sebelum mengambil keputusan admin.' : $statusNotice }}</p>
            </div>
            <a href="{{ $isAdminView ? route('admin.transaksi') : route('user.transaksi') }}" class="rounded-2xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-slate-50">
                {{ $isAdminView ? 'Kembali ke Transaksi Admin' : 'Kembali ke Riwayat' }}
            </a>
        </div>

        @if ($paymentError)
            <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $paymentError }}
            </div>
        @endif

        @error('checkout')
            <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $message }}
            </div>
        @enderror

        <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <section class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-emerald-100/40 backdrop-blur">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Informasi Pesanan</h2>
                </div>

                <div class="space-y-4 px-6 py-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[1.5rem] bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">ID Pesanan</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">#{{ $transaksi->id }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $transaksi->status_pesanan }}</p>
                        </div>
                    </div>

                    @if ($transaksi->payment_type)
                        <div class="rounded-[1.5rem] border border-slate-200 bg-white px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Metode Pembayaran</p>
                            <p class="mt-2 text-base font-semibold text-slate-900">{{ $paymentTypeLabels[$transaksi->payment_type] ?? \Illuminate\Support\Str::headline($transaksi->payment_type) }}</p>
                            @if ($transaksi->payment_code)
                                <p class="mt-1 text-sm text-slate-600">Kode Pembayaran: {{ $transaksi->payment_code }}</p>
                            @endif
                            <p class="mt-1 text-sm text-slate-500">Status pembayaran: {{ $transaksi->status_pembayaran }}</p>
                        </div>
                    @endif

                    <div class="{{ $transaksi->status_pesanan === 'Ditolak' ? '' : 'hidden ' }}rounded-[1.5rem] border border-rose-200 bg-rose-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-700">Alasan Penolakan</p>
                            <p class="mt-2 text-sm leading-6 text-rose-700">{{ $transaksi->alasan_penolakan ?: 'Pesanan ini ditolak oleh admin.' }}</p>
                        </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-white px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Penerima</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $transaksi->nama_penerima }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $transaksi->no_telp_penerima }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $transaksi->alamat_penerima }}</p>
                        @if ($transaksi->rajaongkir_destination_label)
                            <p class="mt-3 text-sm font-semibold text-slate-900">{{ $transaksi->rajaongkir_destination_label }}</p>
                        @endif
                        @if ($transaksi->kode_pos_penerima)
                            <p class="mt-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Kode Pos {{ $transaksi->kode_pos_penerima }}</p>
                        @endif
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-white px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Pengiriman</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $transaksi->kurir }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $transaksi->service_pengiriman }}</p>
                        <p class="mt-1 text-sm text-slate-600">Estimasi {{ $transaksi->estimasi_pengiriman ?: '-' }}</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <div class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-emerald-100/40 backdrop-blur">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Ringkasan Pesanan</h2>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        @foreach ($transaksi->detailTransaksi as $detail)
                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $detail->produk->nama_produk ?? 'Produk' }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $detail->jumlah_produk }} x Rp {{ number_format($detail->harga_produk, 0, ',', '.') }}</p>
                                <p class="mt-2 text-sm font-semibold text-emerald-700">Rp {{ number_format($detail->subtotal_produk, 0, ',', '.') }}</p>
                            </div>
                        @endforeach

                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <span>Ongkir</span>
                            <span>Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-700">Total Pesanan</span>
                                <span class="text-2xl font-bold text-slate-900">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if ($canPay && $snapToken)
                            <button type="button" id="payButton" class="w-full rounded-[1.5rem] bg-slate-900 px-5 py-4 text-base font-semibold text-white shadow-xl shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800">Bayar Sekarang</button>
                        @elseif ($canPay && $snapRedirectUrl)
                            <a href="{{ $snapRedirectUrl }}" target="_blank" rel="noreferrer" class="block w-full rounded-[1.5rem] bg-slate-900 px-5 py-4 text-center text-base font-semibold text-white shadow-xl shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800">Lanjut ke Midtrans</a>
                        @elseif (!$isAdminView && $transaksi->status_pesanan === 'Menunggu Konfirmasi')
                            <div class="rounded-[1.5rem] border border-sky-200 bg-sky-50 px-5 py-4 text-sm font-medium text-sky-700">
                                Pesanan ini belum bisa dibayar karena masih menunggu konfirmasi dari admin.
                            </div>
                        @elseif (!$isAdminView && $transaksi->status_pesanan === 'Ditolak')
                            <div class="rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-700">
                                Pesanan ditolak oleh admin dan tidak bisa dilanjutkan ke pembayaran.
                            </div>
                        @endif

                        @if (!$isAdminView && $transaksi->status_pesanan === 'Menunggu Pembayaran')
                        <form action="{{ route('user.checkout.payment.refresh-status', $transaksi) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded-[1.5rem] border border-slate-300 bg-white px-5 py-4 text-base font-semibold text-slate-800 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-slate-50 hover:cursor-pointer">
                                Cek Status Pembayaran
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="rounded-[2rem] border border-dashed border-emerald-300 bg-emerald-50 px-6 py-6 text-center">
                    <h2 class="text-lg font-semibold text-slate-900">{{ $isAdminView ? 'Pesanan Siap Ditinjau' : 'Pesanan Sudah Tercatat' }}</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        {{ $isAdminView
                            ? 'Gunakan halaman ini untuk memeriksa detail pesanan secara lengkap sebelum menerima atau menolak transaksi dari halaman admin.'
                            : 'Halaman ini menampilkan detail pesanan sekaligus langkah pembayaran. Kalau pesanan belum lunas, kamu masih bisa lanjut bayar atau sinkronkan status terbaru dari Midtrans lewat tombol di atas.' }}
                    </p>
                </div>
            </aside>
        </div>
    </div>
</div>

@if ($snapToken)
    <script src="{{ $midtransSnapScriptUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        (() => {
            const payButton = document.getElementById('payButton');
            const snapToken = @json($snapToken);

            if (!payButton || !snapToken || typeof window.snap === 'undefined') {
                return;
            }

            payButton.addEventListener('click', () => {
                window.snap.pay(snapToken, {
                    onSuccess: function () {
                        window.location.href = @json(route('user.transaksi'));
                    },
                    onPending: function () {
                        window.location.reload();
                    },
                    onError: function () {
                        window.location.reload();
                    },
                    onClose: function () {
                        window.location.reload();
                    },
                });
            });
        })();
    </script>
@endif
@endsection
