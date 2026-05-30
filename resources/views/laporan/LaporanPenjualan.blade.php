@extends('layouts.sidebar')

@section('content')
@php
    $monthOptions = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
    $maxLine = max($lineChart->max('jumlah'), 1);
    $lineItems = $lineChart->values();
    $lineCount = max($lineItems->count(), 1);
    $lineStep = $lineCount > 1 ? 804 / ($lineCount - 1) : 0;
    $linePoints = $lineItems->map(function ($item, $index) use ($maxLine, $lineStep) {
        $x = 76 + ($index * $lineStep);
        $y = 292 - (($item['jumlah'] / $maxLine) * 220);
        return round($x, 2) . ',' . round($y, 2);
    })->implode(' ');
    $areaPoints = '76,292 ' . $linePoints . ' 880,292';
    $maxBar = max($barChart->max('total_jumlah') ?? 0, 1);
    $peakLine = $lineItems->sortByDesc('jumlah')->first();
    $hasTransactionData = $lineItems->sum('jumlah') > 0;
    $hasProductSalesData = $barChart->sum('total_jumlah') > 0;
@endphp

<div class="-mt-5 space-y-6">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="bg-[linear-gradient(135deg,_#ffffff_0%,_#eef7ff_55%,_#fff8eb_100%)] px-6 py-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Laporan Penjualan</p>
                    <h1 class="mt-3 text-3xl font-bold text-slate-900">Ringkasan Penjualan Produk</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Filter periode bulan dan tahun untuk melihat laporan penjualan sesuai periode yang dipilih.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="grid gap-4 border-t border-slate-200 px-6 py-5 md:grid-cols-[1fr_1fr_auto_auto]">
            <select name="month" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                <option value="">Semua Bulan</option>
                @foreach ($monthOptions as $monthNumber => $monthLabel)
                    <option value="{{ $monthNumber }}" @selected($filters['month'] === $monthNumber)>{{ $monthLabel }}</option>
                @endforeach
            </select>
            <select name="year" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" @selected($filters['year'] === (int) $year)>{{ $year }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Pilih Periode</button>
            <a href="{{ route('admin.laporan.penjualan') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
        </form>
    </section>

    <section class="grid gap-5 lg:grid-cols-3">
        <div class="rounded-[1.5rem] border border-slate-200 bg-white px-5 py-5 shadow-lg shadow-slate-100">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total Transaksi</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalTransaksi }}</p>
        </div>
        <div class="rounded-[1.5rem] border border-slate-200 bg-white px-5 py-5 shadow-lg shadow-slate-100">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Produk Terlaris</p>
            <p class="mt-3 text-xl font-bold text-slate-900">{{ $produkTerlaris->nama_produk ?? '-' }}</p>
        </div>
        <div class="rounded-[1.5rem] border border-slate-200 bg-white px-5 py-5 shadow-lg shadow-slate-100">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Nama Produk</p>
            <p class="mt-3 text-xl font-bold text-slate-900">{{ $produkTerlaris->nama_produk ?? '-' }}</p>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-100">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Grafik Jumlah Transaksi</h2>
                </div>
                <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">Line Chart</span>
            </div>

            @if (!$hasTransactionData)
                <div class="mt-6 rounded-[1.75rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-16 text-center">
                    <h3 class="text-lg font-semibold text-slate-800">Belum ada transaksi</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Tidak ada transaksi selesai pada periode yang dipilih.</p>
                </div>
            @else
                <div class="mt-6 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4">
                <svg viewBox="0 0 960 380" class="h-80 w-full">
                    <defs>
                        <linearGradient id="lineAreaGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.34" />
                            <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.02" />
                        </linearGradient>
                        <filter id="lineGlow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="8" stdDeviation="7" flood-color="#0284c7" flood-opacity="0.18" />
                        </filter>
                    </defs>
                    <rect x="0" y="0" width="960" height="380" rx="24" fill="#ffffff" />
                    <text x="76" y="38" font-size="15" font-weight="800" fill="#0f172a">Tren transaksi</text>
                    <text x="76" y="58" font-size="12" font-weight="600" fill="#64748b">Puncak: {{ $peakLine['jumlah'] ?? 0 }} transaksi</text>
                    @for ($grid = 0; $grid <= 4; $grid++)
                        @php
                            $gridY = 82 + ($grid * 52.5);
                            $gridValue = round($maxLine - (($grid / 4) * $maxLine));
                        @endphp
                        <line x1="76" y1="{{ $gridY }}" x2="892" y2="{{ $gridY }}" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="6 8" />
                        <text x="52" y="{{ $gridY + 5 }}" text-anchor="end" font-size="13" font-weight="600" fill="#64748b">{{ $gridValue }}</text>
                    @endfor
                    <line x1="76" y1="292" x2="900" y2="292" stroke="#94a3b8" stroke-width="1.5" />
                    <line x1="76" y1="70" x2="76" y2="292" stroke="#94a3b8" stroke-width="1.5" />
                    <polygon points="{{ $areaPoints }}" fill="url(#lineAreaGradient)" />
                    <polyline points="{{ $linePoints }}" fill="none" stroke="#0284c7" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" filter="url(#lineGlow)" />
                    @foreach ($lineItems as $index => $item)
                        @php
                            $x = 76 + ($index * $lineStep);
                            $y = 292 - (($item['jumlah'] / $maxLine) * 220);
                        @endphp
                        <line x1="{{ $x }}" y1="292" x2="{{ $x }}" y2="{{ $y }}" stroke="#bae6fd" stroke-width="1" stroke-dasharray="3 7" opacity="{{ $item['show_label'] ? '1' : '0' }}" />
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="9" fill="#ffffff" stroke="#0284c7" stroke-width="4" />
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="3" fill="#0284c7" />
                        @if ($item['show_label'])
                            <text x="{{ $x }}" y="326" text-anchor="middle" font-size="14" font-weight="800" fill="#475569">{{ $item['label'] }}</text>
                        @endif
                        @if ($item['jumlah'] > 0)
                            <rect x="{{ $x - 13 }}" y="{{ max($y - 33, 22) }}" width="26" height="20" rx="10" fill="#0f172a" />
                            <text x="{{ $x }}" y="{{ max($y - 19, 36) }}" text-anchor="middle" font-size="12" font-weight="800" fill="#ffffff">{{ $item['jumlah'] }}</text>
                        @endif
                    @endforeach
                    <text x="484" y="360" text-anchor="middle" font-size="13" font-weight="800" fill="#64748b">{{ $lineChartMode === 'daily' ? 'Tanggal' : 'Bulan' }}</text>
                    <text x="18" y="190" text-anchor="middle" font-size="13" font-weight="800" fill="#64748b" transform="rotate(-90 18 190)">Jumlah Transaksi</text>
                </svg>
            </div>
            @endif
        </div>

        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-100">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Grafik Penjualan Produk</h2>
                </div>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Bar Chart</span>
            </div>

            <div class="mt-6 max-h-80 space-y-4 overflow-y-auto pr-2 [scrollbar-color:#7dd3fc_#f1f5f9] [scrollbar-width:thin] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:rounded-full [&::-webkit-scrollbar-track]:bg-slate-100 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-sky-300">
                @if (!$hasProductSalesData)
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
                        <h3 class="text-base font-semibold text-slate-800">Belum ada penjualan produk</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Produk terjual belum tersedia pada periode yang dipilih.</p>
                    </div>
                @else
                @foreach ($barChart as $produk)
                    @php($barWidth = max(((int) $produk->total_jumlah / $maxBar) * 100, 4))
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                            <span class="truncate font-semibold text-slate-700">{{ $produk->nama_produk }}</span>
                            <span class="font-bold text-slate-900">{{ (int) $produk->total_jumlah }}</span>
                        </div>
                        <div class="h-5 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-emerald-500 shadow-sm shadow-emerald-100" style="width: {{ $barWidth }}%"></div>
                        </div>
                    </div>
                @endforeach
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
