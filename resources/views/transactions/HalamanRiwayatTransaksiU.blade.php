@extends('layouts.sidebar')

@section('content')
@php
    use Illuminate\Support\Str;

    $paymentTypeLabels = [
        'bank_transfer' => 'Bank Transfer',
        'qris' => 'QRIS',
        'gopay' => 'GoPay',
        'shopeepay' => 'ShopeePay',
        'echannel' => 'Mandiri Bill',
        'cstore' => 'Convenience Store',
        'akulaku' => 'Akulaku',
    ];

    $statusClasses = [
        'Menunggu Konfirmasi' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'Dikonfirmasi' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'Diproses' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'Dibatalkan' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'Selesai' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];
@endphp

<div class="space-y-4 lg:-mt-5">
    <section class="rounded-4xl border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_30%),linear-gradient(135deg,_#ffffff_0%,_#f8fbff_55%,_#eef5ff_100%)] px-3 py-7 shadow-xl shadow-sky-100/50">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Transaksi Saya</p>
                <h1 class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">Riwayat Pesanan</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Lihat pesanan yang sudah kamu buat, metode pembayarannya, dan progres status pesanan dengan tampilan yang lebih rapi.</p>
            </div>

            <form action="{{ route('user.transaksi') }}" method="GET" class="grid gap-3 sm:grid-cols-[1fr_1fr_auto_auto]">
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100 hover:cursor-pointer">
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100 hover:cursor-pointer">
                <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:cursor-pointer">Filter</button>
                <a href="{{ route('user.transaksi') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:bg-slate-50 hover:cursor-pointer">Reset</a>
            </form>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100/80">
        @if (session('review_error'))
            <div class="border-b border-rose-200 bg-rose-50 px-6 py-4 text-sm font-semibold text-rose-700">
                {{ session('review_error') }}
            </div>
        @endif

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-900">Daftar Transaksi</h2>
            <p class="mt-1 text-sm text-slate-500">Tampilan pembayaran, total belanja, dan status pesanan terbaru kamu.</p>
        </div>

        @if ($transaksi->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto max-w-md rounded-[1.75rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-10">
                    <h3 class="text-lg font-semibold text-slate-800">Belum ada transaksi</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Pesanan yang sudah kamu checkout nanti akan muncul di halaman ini.</p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <div class="max-h-96 overflow-y-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/80">
                        <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            <th class="px-6 py-4">Pesanan</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Pembayaran</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transaksi as $item)
                            @php
                                $metodePembayaranValue = $item->metode_pembayaran;
                                $paymentLabel = $paymentTypeLabels[$metodePembayaranValue] ?? Str::headline($metodePembayaranValue ?? '-');
                                $statusLabel = $item->status_pesanan ?: '-';
                                $statusClass = $statusClasses[$statusLabel] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
                                $tanggalTransaksi = $item->tanggal_transaksi;
                            @endphp
                            <tr class="transition duration-300 hover:bg-slate-100/50">
                                <td class="px-6 py-5">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">#{{ $item->id }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-500">Order ID</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-sm font-semibold text-slate-800">{{ $tanggalTransaksi?->format('d M Y') ?? '-' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $tanggalTransaksi?->format('H:i') ? $tanggalTransaksi->format('H:i') . ' WIB' : '-' }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Termasuk ongkir</p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex rounded-full bg-sky-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-sky-700 ring-1 ring-sky-200">
                                        {{ $paymentLabel ?: '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] ring-1 {{ $statusClass }}">
                                        {{ $statusLabel}}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('user.checkout.payment', $item) }}" class="inline-flex rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:cursor-pointer">
                                        Lihat Detail
                                    </a>
                                    @if ($item->status_pesanan === 'Selesai')
                                        <a href="{{ route('user.transaksi.review', $item) }}" class="inline-flex rounded-2xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-amber-600 hover:cursor-pointer">
                                            Review
                                        </a>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
