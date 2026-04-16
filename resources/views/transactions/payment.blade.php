@extends('layouts.normal')

@section('content')
<div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.10),_transparent_32%),linear-gradient(180deg,_#f8fbff_0%,_#eef4fb_100%)] px-4 py-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Pembayaran</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Konfirmasi Pesanan</h1>
                <p class="mt-2 text-sm text-slate-500">Data transaksi sudah masuk. Langkah berikutnya tinggal sambungkan metode pembayaran.</p>
            </div>
            <a href="{{ route('user.checkout') }}" class="rounded-2xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-slate-50">Kembali ke Checkout</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <section class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-emerald-100/40 backdrop-blur">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Detail Transaksi</h2>
                </div>

                <div class="space-y-4 px-6 py-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[1.5rem] bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">ID Pesanan</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">#{{ $transaksi->id }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $transaksi->status_pembayaran }}</p>
                        </div>
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
                        <h2 class="text-lg font-semibold text-slate-900">Ringkasan Pembayaran</h2>
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
                                <span class="text-sm font-semibold text-slate-700">Total Bayar</span>
                                <span class="text-2xl font-bold text-slate-900">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-dashed border-emerald-300 bg-emerald-50 px-6 py-6 text-center">
                    <h2 class="text-lg font-semibold text-slate-900">Halaman Pembayaran Siap</h2>
                    <p class="mt-2 text-sm text-slate-600">Untuk sekarang transaksi sudah berhasil dibuat dan status pembayaran masih `pending`. Nanti kita bisa lanjut sambungkan payment gateway atau upload bukti transfer di halaman ini.</p>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
