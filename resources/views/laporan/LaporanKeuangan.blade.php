@extends('layouts.sidebar')

@section('content')
@php
    $monthOptions = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $chartItems = $financeChart->values();
    $chartCount = max($chartItems->count(), 1);
    $chartStep = $chartCount > 1 ? 804 / ($chartCount - 1) : 0;
    $maxValue = max($chartItems->max('income') ?? 0, $chartItems->max('expense') ?? 0, $chartItems->max('profit') ?? 0, 1);

    $buildPoints = function (string $key, int $seriesMax) use ($chartItems, $chartStep) {
        return $chartItems->map(function ($item, $index) use ($key, $chartStep, $seriesMax) {
            $x = 76 + ($index * $chartStep);
            $y = 292 - ((max((int) $item[$key], 0) / max($seriesMax, 1)) * 220);
            return round($x, 2) . ',' . round($y, 2);
        })->implode(' ');
    };
    $incomeMax = max($chartItems->max('income') ?? 0, 1);
    $expenseMax = max($chartItems->max('expense') ?? 0, 1);
    $profitMax = max($chartItems->max('profit') ?? 0, 1);
    $incomePoints = $buildPoints('income', $incomeMax);
    $expensePoints = $buildPoints('expense', $expenseMax);
    $profitPoints = $buildPoints('profit', $profitMax);
    $hasFinanceData = $chartItems->sum('income') > 0 || $chartItems->sum('expense') > 0 || $chartItems->sum('profit') !== 0;
@endphp

<div class="space-y-6 lg:-mt-5">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="bg-[linear-gradient(135deg,_#ffffff_0%,_#f0fff7_55%,_#eef7ff_100%)] px-6 py-7">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">Laporan Keuangan</p>
            <h1 class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">Grafik Income, Pengeluaran, dan Profit</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Lihat perbandingan income, pengeluaran, dan profit berdasarkan periode bulan dan tahun.</p>
        </div>

        <form action="{{ route('admin.laporan.keuangan') }}" method="GET" class="grid gap-4 border-t border-slate-200 px-6 py-5 md:grid-cols-[1fr_1fr_auto_auto]">
            <select name="month" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                <option value="">Semua Bulan</option>
                @foreach ($monthOptions as $monthNumber => $monthLabel)
                    <option value="{{ $monthNumber }}" @selected($filters['month'] === $monthNumber)>{{ $monthLabel }}</option>
                @endforeach
            </select>
            <select name="year" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" @selected($filters['year'] === (int) $year)>{{ $year }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Pilih Periode</button>
            <a href="{{ route('admin.laporan.keuangan') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
        </form>
    </section>

    @if (session('pengeluaran_success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('pengeluaran_success') }}</div>
    @endif

    @if (session('pengeluaran_error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">{{ session('pengeluaran_error') }}</div>
    @endif

    <section class="grid gap-5 lg:grid-cols-3">
        <div class="rounded-[1.5rem] border border-slate-200 bg-white px-5 py-5 shadow-lg shadow-slate-100">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total Income</p>
            <p class="mt-3 text-2xl font-bold text-sky-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-[1.5rem] border border-slate-200 bg-white px-5 py-5 shadow-lg shadow-slate-100">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total Pengeluaran</p>
            <p class="mt-3 text-2xl font-bold text-rose-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-[1.5rem] border border-slate-200 bg-white px-5 py-5 shadow-lg shadow-slate-100">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Profit</p>
            <p class="mt-3 text-2xl font-bold {{ $profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">Rp {{ number_format($profit, 0, ',', '.') }}</p>
        </div>
    </section>

        <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Data Pengeluaran</h2>
                    <p class="mt-1 text-sm text-slate-500">Data mengikuti filter periode laporan keuangan di atas.</p>
                </div>
                <button type="button" id="openAddPengeluaranModal" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Tambah</button>
            </div>
        </div>

        <div class="max-h-[28rem] overflow-x-auto overflow-y-auto [scrollbar-color:#7dd3fc_#f1f5f9] [scrollbar-width:thin] [&::-webkit-scrollbar]:h-2 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:rounded-full [&::-webkit-scrollbar-track]:bg-slate-100 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-sky-300">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="sticky top-0 bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                        <th class="px-6 py-4">Nama Pengeluaran</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pengeluaran as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $item->nama_pengeluaran }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $item->tanggal_pengeluaran?->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-rose-700">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="editPengeluaranBtn rounded-2xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700" data-update-url="{{ route('admin.laporan.keuangan.pengeluaran.update', $item) }}" data-nama="{{ $item->nama_pengeluaran }}" data-tanggal="{{ $item->tanggal_pengeluaran?->format('Y-m-d') }}" data-nominal="{{ $item->nominal }}">Edit</button>
                                    <button type="button" class="deletePengeluaranBtn rounded-2xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700" data-delete-url="{{ route('admin.laporan.keuangan.pengeluaran.destroy', $item) }}">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm font-semibold text-slate-500">Belum ada data pengeluaran pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Grafik Keuangan</h2>
            <p class="mt-1 text-sm text-slate-500">Income, pengeluaran, dan profit ditampilkan sebagai chart terpisah.</p>
        </div>

        @if (!$hasFinanceData)
            <div class="rounded-[1.75rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-16 text-center">
                <h3 class="text-lg font-semibold text-slate-800">Belum ada data keuangan</h3>
                <p class="mt-2 text-sm leading-6 text-slate-500">Tidak ada income, pengeluaran, atau profit pada periode yang dipilih.</p>
            </div>
        @else
            <div class="grid gap-6 xl:grid-cols-3">
                @foreach ([
                    ['title' => 'Grafik Income', 'key' => 'income', 'points' => $incomePoints, 'max' => $incomeMax, 'color' => '#0284c7', 'pill' => 'bg-sky-50 text-sky-700'],
                    ['title' => 'Grafik Pengeluaran', 'key' => 'expense', 'points' => $expensePoints, 'max' => $expenseMax, 'color' => '#e11d48', 'pill' => 'bg-rose-50 text-rose-700'],
                    ['title' => 'Grafik Profit', 'key' => 'profit', 'points' => $profitPoints, 'max' => $profitMax, 'color' => '#059669', 'pill' => 'bg-emerald-50 text-emerald-700'],
                ] as $series)
                    <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-xl shadow-slate-100">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-base font-semibold text-slate-900">{{ $series['title'] }}</h3>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $series['pill'] }}">Line Chart</span>
                        </div>
                        <div class="mt-5 rounded-[1.5rem] border border-slate-200 bg-slate-50 p-3">
                            <svg viewBox="0 0 520 300" class="h-56 w-full sm:h-64">
                                <rect x="0" y="0" width="520" height="300" rx="20" fill="#ffffff" />
                                @for ($grid = 0; $grid <= 4; $grid++)
                                    @php
                                        $gridY = 54 + ($grid * 46);
                                        $gridValue = round($series['max'] - (($grid / 4) * $series['max']));
                                    @endphp
                                    <line x1="54" y1="{{ $gridY }}" x2="486" y2="{{ $gridY }}" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="5 7" />
                                    <text x="42" y="{{ $gridY + 4 }}" text-anchor="end" font-size="10" font-weight="700" fill="#64748b">{{ number_format($gridValue, 0, ',', '.') }}</text>
                                @endfor
                                <line x1="54" y1="238" x2="490" y2="238" stroke="#94a3b8" stroke-width="1.3" />
                                <line x1="54" y1="44" x2="54" y2="238" stroke="#94a3b8" stroke-width="1.3" />
                                <polyline points="{{ collect(explode(' ', $series['points']))->map(function ($point) {
                                    [$x, $y] = explode(',', $point);
                                    $scaledX = 54 + (((float) $x - 76) * (432 / 804));
                                    $scaledY = 238 - ((292 - (float) $y) * (184 / 220));
                                    return round($scaledX, 2) . ',' . round($scaledY, 2);
                                })->implode(' ') }}" fill="none" stroke="{{ $series['color'] }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                @foreach ($chartItems as $index => $item)
                                    @php($x = 54 + ($index * ($chartCount > 1 ? 432 / ($chartCount - 1) : 0)))
                                    @if ($item['show_label'])
                                        <text x="{{ $x }}" y="268" text-anchor="middle" font-size="10" font-weight="800" fill="#475569">{{ $item['label'] }}</text>
                                    @endif
                                @endforeach
                                <text x="270" y="292" text-anchor="middle" font-size="11" font-weight="800" fill="#64748b">{{ $chartMode === 'daily' ? 'Tanggal' : 'Bulan' }}</text>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <div id="addPengeluaranModal" class="fixed inset-0 z-[70] hidden items-center justify-center px-4">
        <div id="addPengeluaranOverlay" class="absolute inset-0 bg-slate-950/50"></div>
        <div class="relative w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">Tambah Pengeluaran</p>
                    <h3 class="mt-2 text-xl font-bold text-slate-900">Tambah Data Pengeluaran</h3>
                </div>
                <button type="button" id="closeAddPengeluaranModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">&times;</button>
            </div>

            <form action="{{ route('admin.laporan.keuangan.pengeluaran.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Nama Pengeluaran</span>
                    <input type="text" name="nama_pengeluaran" value="{{ old('nama_pengeluaran') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Tanggal Pengeluaran</span>
                    <input type="date" name="tanggal_pengeluaran" value="{{ old('tanggal_pengeluaran') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Nominal</span>
                    <input type="number" name="nominal" min="1" value="{{ old('nominal') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                </label>
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" id="cancelAddPengeluaranModal" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" class="rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editPengeluaranModal" class="fixed inset-0 z-[70] hidden items-center justify-center px-4">
        <div id="editPengeluaranOverlay" class="absolute inset-0 bg-slate-950/50"></div>
        <div class="relative w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Edit Pengeluaran</p>
                    <h3 class="mt-2 text-xl font-bold text-slate-900">Ubah Data Pengeluaran</h3>
                </div>
                <button type="button" id="closeEditPengeluaranModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">&times;</button>
            </div>

            <form id="editPengeluaranForm" method="POST" action="#" class="mt-5 space-y-4">
                @csrf
                @method('PUT')
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Nama Pengeluaran</span>
                    <input id="editNamaPengeluaran" type="text" name="nama_pengeluaran" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Tanggal Pengeluaran</span>
                    <input id="editTanggalPengeluaran" type="date" name="tanggal_pengeluaran" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Nominal</span>
                    <input id="editNominalPengeluaran" type="number" min="1" name="nominal" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </label>
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" id="cancelEditPengeluaranModal" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" class="rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deletePengeluaranModal" class="fixed inset-0 z-[70] hidden items-center justify-center px-4">
        <div id="deletePengeluaranOverlay" class="absolute inset-0 bg-slate-950/50"></div>
        <div class="relative w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-600">Hapus Pengeluaran</p>
                    <h3 class="mt-2 text-xl font-bold text-slate-900">Konfirmasi Hapus</h3>
                </div>
                <button type="button" id="closeDeletePengeluaranModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">&times;</button>
            </div>
            <p class="mt-5 text-sm leading-6 text-slate-600">Yakin ingin menghapus data pengeluaran ini?</p>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancelDeletePengeluaranModal" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Tidak</button>
                <form id="deletePengeluaranForm" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700 sm:w-auto">Iya</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
