<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $filters = $this->resolvePeriodFilters($request);
        $year = $filters['year'];
        $month = $filters['month'];

        $transaksiQuery = Transaksi::query()
            ->where('status_transaksi', 'Selesai')
            ->whereYear('tanggal_transaksi', $year)
            ->when($month, fn ($query) => $query->whereMonth('tanggal_transaksi', $month));

        $totalTransaksi = (clone $transaksiQuery)->count();

        if ($month) {
            $dailyCounts = Transaksi::query()
                ->selectRaw('DAY(tanggal_transaksi) as tanggal, COUNT(*) as jumlah')
                ->where('status_transaksi', 'Selesai')
                ->whereYear('tanggal_transaksi', $year)
                ->whereMonth('tanggal_transaksi', $month)
                ->groupByRaw('DAY(tanggal_transaksi)')
                ->pluck('jumlah', 'tanggal');

            $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
            $lineChart = collect(range(1, $daysInMonth))->map(fn (int $day) => [
                'label' => (string) $day,
                'jumlah' => (int) ($dailyCounts[$day] ?? 0),
                'show_label' => $day % 3 === 0,
            ]);
            $lineChartMode = 'daily';
        } else {
            $monthlyCounts = Transaksi::query()
                ->selectRaw('MONTH(tanggal_transaksi) as bulan, COUNT(*) as jumlah')
                ->where('status_transaksi', 'Selesai')
                ->whereYear('tanggal_transaksi', $year)
                ->groupByRaw('MONTH(tanggal_transaksi)')
                ->pluck('jumlah', 'bulan');

            $lineChart = collect(range(1, 12))->map(fn (int $monthNumber) => [
                'label' => $this->monthLabels()[$monthNumber],
                'jumlah' => (int) ($monthlyCounts[$monthNumber] ?? 0),
                'show_label' => true,
            ]);
            $lineChartMode = 'monthly';
        }

        $produkPenjualan = DetailTransaksi::query()
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->join('katalog_produk', 'detail_transaksi.produk_id', '=', 'katalog_produk.id')
            ->select([
                'katalog_produk.nama_produk',
                DB::raw('SUM(detail_transaksi.jumlah_produk) as total_jumlah'),
                DB::raw('SUM(detail_transaksi.subtotal_produk) as total_penjualan_produk'),
            ])
            ->where('transaksi.status_transaksi', 'Selesai')
            ->whereYear('transaksi.tanggal_transaksi', $year)
            ->when($month, fn ($query) => $query->whereMonth('transaksi.tanggal_transaksi', $month))
            ->groupBy('katalog_produk.id', 'katalog_produk.nama_produk')
            ->orderByDesc('total_jumlah')
            ->get();

        $produkTerlaris = $produkPenjualan->first();
        $totalPenjualanProduk = (int) $produkPenjualan->sum('total_penjualan_produk');
        $barChart = $produkPenjualan->take(8)->values();
        $availableYears = $this->availableTransactionYears();

        return view('laporan.LaporanPenjualan', compact(
            'filters',
            'availableYears',
            'lineChart',
            'lineChartMode',
            'barChart',
            'produkPenjualan',
            'produkTerlaris',
            'totalPenjualanProduk',
            'totalTransaksi'
        ));
    }

    public function keuangan(Request $request)
    {
        $filters = $this->resolvePeriodFilters($request);
        $year = $filters['year'];
        $month = $filters['month'];

        $incomeData = $this->buildIncomeSeries($year, $month);
        $expenseData = $this->buildExpenseSeries($year, $month);
        $financeChart = $incomeData->map(function (array $incomeItem, int $index) use ($expenseData) {
            $expenseItem = $expenseData[$index] ?? ['jumlah' => 0];
            $income = (int) $incomeItem['jumlah'];
            $expense = (int) $expenseItem['jumlah'];

            return [
                'label' => $incomeItem['label'],
                'show_label' => $incomeItem['show_label'],
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense,
            ];
        });

        $totalIncome = (int) $financeChart->sum('income');
        $totalPengeluaran = (int) $financeChart->sum('expense');
        $profit = $totalIncome - $totalPengeluaran;
        $pengeluaran = $this->pengeluaranQueryForPeriod($year, $month)->get();

        return view('laporan.LaporanKeuangan', [
            'filters' => $filters,
            'availableYears' => $this->availableFinanceYears(),
            'financeChart' => $financeChart,
            'pengeluaran' => $pengeluaran,
            'totalIncome' => $totalIncome,
            'totalPengeluaran' => $totalPengeluaran,
            'profit' => $profit,
            'chartMode' => $month ? 'daily' : 'monthly',
        ]);
    }

    public function storePengeluaran(Request $request)
    {
        if (!$request->filled('nama_pengeluaran') || !$request->filled('tanggal_pengeluaran') || !$request->filled('nominal')) {
            return back()->withInput()->with('pengeluaran_error', 'Data Tidak Boleh Kosong');
        }

        $validator = Validator::make($request->all(), [
            'nama_pengeluaran' => 'required|string|max:255',
            'tanggal_pengeluaran' => 'required|date',
            'nominal' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('pengeluaran_error', 'Data Tidak Sesuai');
        }

        Pengeluaran::create($validator->validated());

        return redirect()->route('admin.laporan.keuangan')->with('pengeluaran_success', 'Data Berhasil Dibuat');
    }

    public function pengeluaranUpdate(Request $request, Pengeluaran $pengeluaran)
    {
        if (!$request->filled('nama_pengeluaran') || !$request->filled('tanggal_pengeluaran') || !$request->filled('nominal')) {
            return back()->withInput()->with('pengeluaran_error', 'Data Tidak Boleh Kosong');
        }

        $validator = Validator::make($request->all(), [
            'nama_pengeluaran' => 'required|string|max:255',
            'tanggal_pengeluaran' => 'required|date',
            'nominal' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('pengeluaran_error', 'Data Tidak Sesuai');
        }

        $pengeluaran->update($validator->validated());

        return redirect()->route('admin.laporan.keuangan')->with('pengeluaran_success', 'Data Berhasil Diubah');
    }

    public function pengeluaranDestroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('admin.laporan.keuangan')->with('pengeluaran_success', 'Data Berhasil Dihapus');
    }

    private function resolvePeriodFilters(Request $request): array
    {
        $year = (int) $request->input('year', now()->year);
        $month = $request->filled('month') ? (int) $request->input('month') : null;

        if ($year < 2000 || $year > 2100) {
            $year = now()->year;
        }

        if ($month !== null && ($month < 1 || $month > 12)) {
            $month = null;
        }

        return [
            'year' => $year,
            'month' => $month,
        ];
    }

    private function availableTransactionYears()
    {
        $years = Transaksi::query()
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->whereNotNull('tanggal_transaksi')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return $years->isEmpty() ? collect([now()->year]) : $years;
    }

    private function availablePengeluaranYears()
    {
        $years = Pengeluaran::query()
            ->selectRaw('YEAR(tanggal_pengeluaran) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return $years->isEmpty() ? collect([now()->year]) : $years;
    }

    private function availableFinanceYears()
    {
        return $this->availableTransactionYears()
            ->merge($this->availablePengeluaranYears())
            ->unique()
            ->sortDesc()
            ->values();
    }

    private function pengeluaranQueryForPeriod(int $year, ?int $month)
    {
        return Pengeluaran::query()
            ->whereYear('tanggal_pengeluaran', $year)
            ->when($month, fn ($query) => $query->whereMonth('tanggal_pengeluaran', $month))
            ->orderByDesc('tanggal_pengeluaran')
            ->orderByDesc('id');
    }

    private function buildIncomeSeries(int $year, ?int $month)
    {
        if ($month) {
            $income = DetailTransaksi::query()
                ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
                ->selectRaw('DAY(transaksi.tanggal_transaksi) as tanggal, SUM(detail_transaksi.subtotal_produk) as jumlah')
                ->where('transaksi.status_transaksi', 'Selesai')
                ->whereYear('transaksi.tanggal_transaksi', $year)
                ->whereMonth('transaksi.tanggal_transaksi', $month)
                ->groupByRaw('DAY(transaksi.tanggal_transaksi)')
                ->pluck('jumlah', 'tanggal');

            $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

            return collect(range(1, $daysInMonth))->map(fn (int $day) => [
                'label' => (string) $day,
                'jumlah' => (int) ($income[$day] ?? 0),
                'show_label' => $day % 3 === 0,
            ]);
        }

        $income = DetailTransaksi::query()
            ->join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->selectRaw('MONTH(transaksi.tanggal_transaksi) as bulan, SUM(detail_transaksi.subtotal_produk) as jumlah')
            ->where('transaksi.status_transaksi', 'Selesai')
            ->whereYear('transaksi.tanggal_transaksi', $year)
            ->groupByRaw('MONTH(transaksi.tanggal_transaksi)')
            ->pluck('jumlah', 'bulan');

        return collect(range(1, 12))->map(fn (int $monthNumber) => [
            'label' => $this->monthLabels()[$monthNumber],
            'jumlah' => (int) ($income[$monthNumber] ?? 0),
            'show_label' => true,
        ]);
    }

    private function buildExpenseSeries(int $year, ?int $month)
    {
        if ($month) {
            $expenses = Pengeluaran::query()
                ->selectRaw('DAY(tanggal_pengeluaran) as tanggal, SUM(nominal) as jumlah')
                ->whereYear('tanggal_pengeluaran', $year)
                ->whereMonth('tanggal_pengeluaran', $month)
                ->groupByRaw('DAY(tanggal_pengeluaran)')
                ->pluck('jumlah', 'tanggal');

            $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

            return collect(range(1, $daysInMonth))->map(fn (int $day) => [
                'label' => (string) $day,
                'jumlah' => (int) ($expenses[$day] ?? 0),
                'show_label' => $day % 3 === 0,
            ]);
        }

        $expenses = Pengeluaran::query()
            ->selectRaw('MONTH(tanggal_pengeluaran) as bulan, SUM(nominal) as jumlah')
            ->whereYear('tanggal_pengeluaran', $year)
            ->groupByRaw('MONTH(tanggal_pengeluaran)')
            ->pluck('jumlah', 'bulan');

        return collect(range(1, 12))->map(fn (int $monthNumber) => [
            'label' => $this->monthLabels()[$monthNumber],
            'jumlah' => (int) ($expenses[$monthNumber] ?? 0),
            'show_label' => true,
        ]);
    }

    private function monthLabels(): array
    {
        return [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];
    }
}
