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
        'cod' => 'COD',
    ];

    $statusClasses = [
        'Menunggu Konfirmasi' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'Dikonfirmasi' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'Diproses' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'Dibatalkan' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'Selesai' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];
@endphp

<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @error('checkout')
        <div class="rounded-3xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
            {{ $message }}
        </div>
    @enderror

    @if ($errors->any() && !$errors->has('checkout'))
        <div class="rounded-3xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="rounded-[2rem] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.12),_transparent_30%),linear-gradient(135deg,_#ffffff_0%,_#f8fbff_55%,_#eefbf7_100%)] px-8 py-8 shadow-xl shadow-emerald-100/50">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Transaksi Admin</p>
                <h1 class="mt-3 text-3xl font-bold text-slate-900">Kelola Pesanan Masuk</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Lihat transaksi online, catat pembelian offline toko, lalu kelola status pesanan dari satu halaman yang sama.</p>
            </div>

            <div class="flex flex-col gap-3 lg:min-w-[44rem]">
                <form action="{{ route('admin.transaksi') }}" method="GET" class="grid gap-3 sm:grid-cols-[1fr_1fr_1fr_auto_auto]">
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 hover:cursor-pointer">
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 hover:cursor-pointer">
                    <select name="status" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 hover:cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Konfirmasi" @selected(($filters['status'] ?? '') === 'Menunggu Konfirmasi')>Menunggu Konfirmasi</option>
                        <option value="Dikonfirmasi" @selected(($filters['status'] ?? '') === 'Dikonfirmasi')>Dikonfirmasi</option>
                        <option value="Diproses" @selected(($filters['status'] ?? '') === 'Diproses')>Diproses</option>
                        <option value="Dibatalkan" @selected(($filters['status'] ?? '') === 'Dibatalkan')>Dibatalkan</option>
                        <option value="Selesai" @selected(($filters['status'] ?? '') === 'Selesai')>Selesai</option>
                    </select>
                    <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:cursor-pointer">Filter</button>
                    <a href="{{ route('admin.transaksi') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:bg-slate-50 hover:cursor-pointer">Reset</a>
                </form>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-4">
        <div class="rounded-[1.75rem] border border-slate-200 bg-white px-6 py-5 shadow-lg shadow-slate-100/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total Pesanan</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $transaksi->count() }}</p>
        </div>
        <div class="rounded-[1.75rem] border border-slate-200 bg-white px-6 py-5 shadow-lg shadow-slate-100/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Menunggu Konfirmasi</p>
            <p class="mt-3 text-3xl font-bold text-sky-600">{{ $transaksi->where('status_pesanan', 'Menunggu Konfirmasi')->count() }}</p>
        </div>
        <div class="rounded-[1.75rem] border border-slate-200 bg-white px-6 py-5 shadow-lg shadow-slate-100/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Dikonfirmasi</p>
            <p class="mt-3 text-3xl font-bold text-amber-600">{{ $transaksi->where('status_pesanan', 'Dikonfirmasi')->count() }}</p>
        </div>
        <div class="rounded-[1.75rem] border border-slate-200 bg-white px-6 py-5 shadow-lg shadow-slate-100/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Diproses</p>
            <p class="mt-3 text-3xl font-bold text-emerald-600">{{ $transaksi->where('status_pesanan', 'Diproses')->count() }}</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100/80">
        <div class="border-b border-slate-200 px-6 py-5 flex justify-between">
            <div flex flex-col>
                <h2 class="text-lg font-semibold text-slate-900">Daftar Transaksi Masuk</h2>
                <p class="mt-1 text-sm text-slate-500">Pesanan berstatus <span class="font-semibold text-sky-700">Menunggu Konfirmasi</span> siap kamu review sekarang. Setelah dikonfirmasi, user baru bisa lanjut ke pembayaran.</p>
            </div>
            <div class="flex justify-end">
                <button type="button" id="openOfflineTransactionModal" data-mode="create" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition duration-300 hover:-translate-y-0.5 hover:bg-emerald-500 hover:cursor-pointer">
                    Tambah Transaksi
                </button>
            </div>
        </div>

        @if ($transaksi->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto max-w-md rounded-[1.75rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-10">
                    <h3 class="text-lg font-semibold text-slate-800">Belum ada transaksi masuk</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Pesanan user akan muncul di halaman ini setelah checkout berhasil dibuat.</p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <div class="max-h-[34rem] overflow-y-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80">
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                <th class="px-6 py-4">Pesanan</th>
                                <th class="px-6 py-4">Pemesan</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Pembayaran</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Aksi Admin</th>
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
                                    $isOffline = $item->is_offline;
                                @endphp
                                <tr class="align-top transition duration-300 hover:bg-slate-50/80">
                                    <td class="px-6 py-5">
                                        <p class="text-sm font-semibold text-slate-900">#{{ $item->id }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-500">{{ $isOffline ? 'Offline Toko' : ($item->midtrans_order_id ?? 'Menunggu Midtrans') }}</p>
                                        <span class="mt-2 inline-flex rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] {{ $isOffline ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-sky-50 text-sky-700 ring-1 ring-sky-200' }}">
                                            {{ $isOffline ? 'Offline' : 'Online' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-sm font-semibold text-slate-900">{{ $isOffline ? 'Offline Toko' : ($item->user->nama_lengkap ?? 'User') }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $item->nama_penerima }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-sm font-semibold text-slate-800">{{ $tanggalTransaksi?->format('d M Y') ?? '-' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $tanggalTransaksi?->format('H:i') ? $tanggalTransaksi->format('H:i') . ' WIB' : '-' }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="space-y-2">
                                            <span class="inline-flex rounded-full bg-sky-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-sky-700 ring-1 ring-sky-200">
                                                {{ $paymentLabel ?: '-' }}
                                            </span>
                                            <p class="text-xs text-slate-500">Status bayar: {{ $item->status_pembayaran }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="space-y-2">
                                            <span class="inline-flex rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] ring-1 {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                            @if ($item->alasan_penolakan)
                                                <p class="max-w-xs text-xs leading-5 text-rose-600">Catatan: {{ $item->alasan_penolakan }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="space-y-3">
                                            <a href="{{ route('admin.transaksi.show', $item) }}" class="inline-flex rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:cursor-pointer">
                                                Lihat Detail
                                            </a>

                                            @if ($isOffline)
                                                <button
                                                    type="button"
                                                    class="openOfflineEditModal inline-flex w-full justify-center rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition duration-300 hover:-translate-y-0.5 hover:bg-emerald-100 hover:cursor-pointer"
                                                    data-update-action="{{ route('admin.transaksi.update-offline', $item) }}"
                                                    data-transaksi-id="{{ $item->id }}"
                                                    data-nama-penerima="{{ $item->nama_penerima }}"
                                                    data-tanggal-transaksi="{{ $tanggalTransaksi?->format('Y-m-d\TH:i') }}"
                                                    data-metode-pembayaran="{{ $item->metode_pembayaran }}"
                                                    data-no-telp-penerima="{{ $item->no_telp_penerima }}"
                                                    data-detail-alamat="{{ $item->alamat_penerima }}"
                                                    data-nomor-kode-pos="{{ $item->penerima?->kodePos?->nomor_kode_pos }}"
                                                    data-nama-kecamatan="{{ $item->penerima?->kecamatan?->nama_kecamatan }}"
                                                    data-nama-kabupaten="{{ $item->penerima?->kabupaten?->nama_kabupaten }}"
                                                    data-nama-provinsi="{{ $item->penerima?->provinsi?->nama_provinsi }}"
                                                    data-resi="{{ $item->resi }}"
                                                    data-ongkir="{{ $item->ongkir }}"
                                                    data-items='@json($item->detailTransaksi->map(fn ($detail) => ["produk_id" => $detail->produk_id, "jumlah_produk" => $detail->jumlah_produk])->values())'
                                                >
                                                    Edit
                                                </button>
                                            @endif

                                            @if ($item->status_pesanan === 'Menunggu Konfirmasi')
                                                <form action="{{ route('admin.transaksi.approve', $item) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex w-full justify-center rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-emerald-500 hover:cursor-pointer">
                                                        Terima
                                                    </button>
                                                </form>

                                                <button
                                                    type="button"
                                                    class="openRejectModal inline-flex w-full justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition duration-300 hover:-translate-y-0.5 hover:bg-rose-100 hover:cursor-pointer"
                                                    data-reject-action="{{ route('admin.transaksi.reject', $item) }}"
                                                    data-order-id="{{ $item->id }}"
                                                >
                                                    Batalkan
                                                </button>
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

@php
    $oldItems = old('items', [['produk_id' => '', 'jumlah_produk' => 1]]);
@endphp

<div id="offlineTransactionModal" class="fixed inset-0 z-[76] hidden items-center justify-center px-4">
    <div id="offlineTransactionModalOverlay" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300"></div>
    <div id="offlineTransactionModalPanel" class="relative max-h-[92vh] w-full max-w-5xl scale-95 overflow-hidden rounded-[2rem] bg-white opacity-0 shadow-2xl transition duration-300">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
            <div>
                <p id="offlineTransactionModalEyebrow" class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">Tambah Transaksi Offline</p>
                <h2 id="offlineTransactionModalTitle" class="mt-2 text-2xl font-bold text-slate-900">Catat Pembelian Toko</h2>
                <p id="offlineTransactionModalDescription" class="mt-2 text-sm leading-6 text-slate-600">Transaksi offline tidak memakai Midtrans. Metode pembayaran bisa dipilih langsung dan status pesanan otomatis selesai.</p>
            </div>
            <button type="button" id="closeOfflineTransactionModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-slate-200 hover:cursor-pointer">&times;</button>
        </div>

        <form id="offlineTransactionForm" action="{{ route('admin.transaksi.store-offline') }}" method="POST" class="max-h-[calc(92vh-96px)] overflow-y-auto px-6 py-6">
            @csrf
            <input type="hidden" id="offlineTransactionMethod" name="_method" value="POST">
            <input type="hidden" id="offlineTransactionId" name="transaksi_id" value="">

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="offlineNamaPenerima" class="mb-2 block text-sm font-medium text-slate-700">Nama Penerima</label>
                    <input id="offlineNamaPenerima" type="text" name="nama_penerima" value="{{ old('nama_penerima') }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                </div>
                <div>
                    <label for="offlineTanggalTransaksi" class="mb-2 block text-sm font-medium text-slate-700">Tanggal Transaksi</label>
                    <input id="offlineTanggalTransaksi" type="datetime-local" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', now()->format('Y-m-d\TH:i')) }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                </div>
                <div>
                    <label for="offlineMetodePembayaran" class="mb-2 block text-sm font-medium text-slate-700">Metode Pembayaran</label>
                    <select id="offlineMetodePembayaran" name="metode_pembayaran" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                        <option value="">Pilih metode pembayaran</option>
                        <option value="qris" @selected(old('metode_pembayaran') === 'qris')>QRIS</option>
                        <option value="cod" @selected(old('metode_pembayaran') === 'cod')>COD</option>
                        <option value="bank_transfer" @selected(old('metode_pembayaran') === 'bank_transfer')>Transfer</option>
                    </select>
                </div>
                <div>
                    <label for="offlineNoTelpPenerima" class="mb-2 block text-sm font-medium text-slate-700">No. Telp Penerima</label>
                    <input id="offlineNoTelpPenerima" type="text" name="no_telp_penerima" value="{{ old('no_telp_penerima') }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                </div>
                <div class="md:col-span-2">
                    <label for="offlineDetailAlamat" class="mb-2 block text-sm font-medium text-slate-700">Detail Alamat</label>
                    <textarea id="offlineDetailAlamat" name="detail_alamat" rows="3" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>{{ old('detail_alamat') }}</textarea>
                </div>
                <div>
                    <label for="offlineNomorKodePos" class="mb-2 block text-sm font-medium text-slate-700">Kode Pos</label>
                    <input id="offlineNomorKodePos" type="text" name="nomor_kode_pos" value="{{ old('nomor_kode_pos') }}" maxlength="5" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                </div>
                <div>
                    <label for="offlineNamaKecamatan" class="mb-2 block text-sm font-medium text-slate-700">Kecamatan</label>
                    <input id="offlineNamaKecamatan" type="text" name="nama_kecamatan" value="{{ old('nama_kecamatan') }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                </div>
                <div>
                    <label for="offlineNamaKabupaten" class="mb-2 block text-sm font-medium text-slate-700">Kabupaten</label>
                    <input id="offlineNamaKabupaten" type="text" name="nama_kabupaten" value="{{ old('nama_kabupaten') }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                </div>
                <div>
                    <label for="offlineNamaProvinsi" class="mb-2 block text-sm font-medium text-slate-700">Provinsi</label>
                    <input id="offlineNamaProvinsi" type="text" name="nama_provinsi" value="{{ old('nama_provinsi') }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                </div>
                <div>
                    <label for="offlineResi" class="mb-2 block text-sm font-medium text-slate-700">Resi</label>
                    <input id="offlineResi" type="text" name="resi" value="{{ old('resi') }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" placeholder="Kosongkan jika tidak ada">
                </div>
                <div>
                    <label for="offlineOngkir" class="mb-2 block text-sm font-medium text-slate-700">Ongkir</label>
                    <input id="offlineOngkir" type="number" min="0" name="ongkir" value="{{ old('ongkir', 0) }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                </div>
            </div>

            <div class="mt-8 rounded-[1.75rem] border border-slate-200 bg-slate-50 px-5 py-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Produk yang Dibeli</p>
                        <p class="mt-1 text-xs text-slate-500">Gunakan tombol tambah baris jika pembeli membeli lebih dari satu produk. Produk yang sama akan otomatis digabung saat disimpan.</p>
                    </div>
                    <button type="button" id="addOfflineItemRow" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition duration-300 hover:bg-slate-800 hover:cursor-pointer">
                        Tambah Produk
                    </button>
                </div>

                <div id="offlineItemsContainer" class="mt-5 space-y-4">
                    @foreach ($oldItems as $index => $oldItem)
                        <div class="offlineItemRow grid gap-3 rounded-[1.5rem] border border-slate-200 bg-white px-4 py-4 md:grid-cols-[1.4fr_0.8fr_auto]">
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Produk</label>
                                <select name="items[{{ $index }}][produk_id]" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                                    <option value="">Pilih produk</option>
                                    @foreach ($produkOptions as $produk)
                                        <option value="{{ $produk->id }}" @selected((string) ($oldItem['produk_id'] ?? '') === (string) $produk->id)>
                                            {{ $produk->nama_produk }} - Rp {{ number_format($produk->harga, 0, ',', '.') }} (Stok {{ $produk->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Jumlah</label>
                                <input type="number" min="1" max="9999" inputmode="numeric" name="items[{{ $index }}][jumlah_produk]" value="{{ $oldItem['jumlah_produk'] ?? 1 }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="removeOfflineItemRow inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition duration-300 hover:bg-rose-100 hover:cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancelOfflineTransactionModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:bg-slate-50 hover:cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="offlineTransactionSubmitButton" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-emerald-500 hover:cursor-pointer">
                    Simpan Transaksi Offline
                </button>
            </div>
        </form>
    </div>
</div>

<template id="offlineItemRowTemplate">
    <div class="offlineItemRow grid gap-3 rounded-[1.5rem] border border-slate-200 bg-white px-4 py-4 md:grid-cols-[1.4fr_0.8fr_auto]">
        <div>
            <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Produk</label>
            <select data-field="produk_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
                <option value="">Pilih produk</option>
                @foreach ($produkOptions as $produk)
                    <option value="{{ $produk->id }}">
                        {{ $produk->nama_produk }} - Rp {{ number_format($produk->harga, 0, ',', '.') }} (Stok {{ $produk->stok }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Jumlah</label>
            <input type="number" min="1" max="9999" inputmode="numeric" value="1" data-field="jumlah_produk" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" required>
        </div>
        <div class="flex items-end">
            <button type="button" class="removeOfflineItemRow inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition duration-300 hover:bg-rose-100 hover:cursor-pointer">
                Hapus
            </button>
        </div>
    </div>
</template>

<div id="rejectModal" class="fixed inset-0 z-[75] hidden items-center justify-center px-4">
    <div id="rejectModalOverlay" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300"></div>
    <div id="rejectModalPanel" class="relative w-full max-w-lg scale-95 rounded-[2rem] bg-white px-6 py-6 opacity-0 shadow-2xl transition duration-300">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-600">Batalkan Pesanan</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Konfirmasi Pembatalan</h2>
                <p id="rejectModalDescription" class="mt-2 text-sm leading-6 text-slate-600">Tulis alasan pembatalan untuk pesanan ini.</p>
            </div>
            <button type="button" id="closeRejectModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-slate-200 hover:cursor-pointer">&times;</button>
        </div>

        <form id="rejectModalForm" action="" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="rejectReason" class="mb-2 block text-sm font-medium text-slate-700">Alasan Pembatalan</label>
                <textarea id="rejectReason" name="alasan_penolakan" rows="4" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100" placeholder="Tulis alasan pembatalan pesanan ini..." required></textarea>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancelRejectModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:bg-slate-50 hover:cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-rose-500 hover:cursor-pointer">
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (() => {
        const offlineModal = document.getElementById('offlineTransactionModal');
        const offlineModalOverlay = document.getElementById('offlineTransactionModalOverlay');
        const offlineModalPanel = document.getElementById('offlineTransactionModalPanel');
        const openOfflineModalButton = document.getElementById('openOfflineTransactionModal');
        const editOfflineModalButtons = document.querySelectorAll('.openOfflineEditModal');
        const closeOfflineModalButton = document.getElementById('closeOfflineTransactionModal');
        const cancelOfflineModalButton = document.getElementById('cancelOfflineTransactionModal');
        const offlineTransactionForm = document.getElementById('offlineTransactionForm');
        const offlineTransactionMethod = document.getElementById('offlineTransactionMethod');
        const offlineTransactionId = document.getElementById('offlineTransactionId');
        const offlineTransactionModalEyebrow = document.getElementById('offlineTransactionModalEyebrow');
        const offlineTransactionModalTitle = document.getElementById('offlineTransactionModalTitle');
        const offlineTransactionModalDescription = document.getElementById('offlineTransactionModalDescription');
        const offlineTransactionSubmitButton = document.getElementById('offlineTransactionSubmitButton');
        const offlineNamaPenerima = document.getElementById('offlineNamaPenerima');
        const offlineTanggalTransaksi = document.getElementById('offlineTanggalTransaksi');
        const offlineMetodePembayaran = document.getElementById('offlineMetodePembayaran');
        const offlineNoTelpPenerima = document.getElementById('offlineNoTelpPenerima');
        const offlineDetailAlamat = document.getElementById('offlineDetailAlamat');
        const offlineNomorKodePos = document.getElementById('offlineNomorKodePos');
        const offlineNamaKecamatan = document.getElementById('offlineNamaKecamatan');
        const offlineNamaKabupaten = document.getElementById('offlineNamaKabupaten');
        const offlineNamaProvinsi = document.getElementById('offlineNamaProvinsi');
        const offlineResi = document.getElementById('offlineResi');
        const offlineOngkir = document.getElementById('offlineOngkir');
        const offlineItemsContainer = document.getElementById('offlineItemsContainer');
        const addOfflineItemRowButton = document.getElementById('addOfflineItemRow');
        const offlineItemRowTemplate = document.getElementById('offlineItemRowTemplate');
        const rejectModal = document.getElementById('rejectModal');
        const rejectModalOverlay = document.getElementById('rejectModalOverlay');
        const rejectModalPanel = document.getElementById('rejectModalPanel');
        const rejectModalForm = document.getElementById('rejectModalForm');
        const rejectReason = document.getElementById('rejectReason');
        const rejectModalDescription = document.getElementById('rejectModalDescription');
        const closeRejectModal = document.getElementById('closeRejectModal');
        const cancelRejectModal = document.getElementById('cancelRejectModal');
        const rejectButtons = document.querySelectorAll('.openRejectModal');

        const openOfflineModal = () => {
            if (!offlineModal || !offlineModalOverlay || !offlineModalPanel) {
                return;
            }

            offlineModal.classList.remove('hidden');
            offlineModal.classList.add('flex');

            requestAnimationFrame(() => {
                offlineModalOverlay.classList.remove('opacity-0');
                offlineModalOverlay.classList.add('opacity-100');
                offlineModalPanel.classList.remove('opacity-0', 'scale-95');
                offlineModalPanel.classList.add('opacity-100', 'scale-100');
            });
        };

        const closeOfflineModal = () => {
            if (!offlineModal || !offlineModalOverlay || !offlineModalPanel) {
                return;
            }

            offlineModalOverlay.classList.remove('opacity-100');
            offlineModalOverlay.classList.add('opacity-0');
            offlineModalPanel.classList.remove('opacity-100', 'scale-100');
            offlineModalPanel.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                offlineModal.classList.add('hidden');
                offlineModal.classList.remove('flex');
            }, 200);
        };

        const populateOfflineRow = (row, item = {}) => {
            const productSelect = row.querySelector('select');
            const qtyInput = row.querySelector('input[type="number"]');

            if (productSelect) {
                productSelect.value = item.produk_id ?? '';
            }

            if (qtyInput) {
                qtyInput.value = item.jumlah_produk ?? 1;
            }
        };

        const renumberOfflineRows = () => {
            if (!offlineItemsContainer) {
                return;
            }

            const rows = offlineItemsContainer.querySelectorAll('.offlineItemRow');

            rows.forEach((row, index) => {
                const productSelect = row.querySelector('select');
                const qtyInput = row.querySelector('input[type="number"]');
                const removeButton = row.querySelector('.removeOfflineItemRow');

                if (productSelect) {
                    productSelect.name = `items[${index}][produk_id]`;
                }

                if (qtyInput) {
                    qtyInput.name = `items[${index}][jumlah_produk]`;
                }

                if (removeButton) {
                    removeButton.disabled = rows.length === 1;
                    removeButton.classList.toggle('opacity-50', rows.length === 1);
                    removeButton.classList.toggle('cursor-not-allowed', rows.length === 1);
                }
            });
        };

        const attachOfflineRowEvents = (row) => {
            const removeButton = row.querySelector('.removeOfflineItemRow');

            if (!removeButton) {
                return;
            }

            removeButton.addEventListener('click', () => {
                const rows = offlineItemsContainer?.querySelectorAll('.offlineItemRow') ?? [];

                if (rows.length <= 1) {
                    return;
                }

                row.remove();
                renumberOfflineRows();
            });
        };

        const addOfflineRow = () => {
            if (!offlineItemsContainer || !offlineItemRowTemplate) {
                return;
            }

            const fragment = offlineItemRowTemplate.content.cloneNode(true);
            const newRow = fragment.querySelector('.offlineItemRow');

            if (!newRow) {
                return;
            }

            offlineItemsContainer.appendChild(fragment);
            const appendedRow = offlineItemsContainer.lastElementChild;
            attachOfflineRowEvents(appendedRow);
            renumberOfflineRows();
            return appendedRow;
        };

        const resetOfflineRows = (items = [{ produk_id: '', jumlah_produk: 1 }]) => {
            if (!offlineItemsContainer) {
                return;
            }

            offlineItemsContainer.innerHTML = '';

            items.forEach((item) => {
                const row = addOfflineRow();
                populateOfflineRow(row, item);
            });

            renumberOfflineRows();
        };

        const setOfflineModalMode = (mode, payload = {}) => {
            if (!offlineTransactionForm || !offlineTransactionMethod || !offlineTransactionSubmitButton) {
                return;
            }

            if (mode === 'edit') {
                offlineTransactionForm.action = payload.action || offlineTransactionForm.action;
                offlineTransactionMethod.value = 'PUT';
                if (offlineTransactionId) {
                    offlineTransactionId.value = payload.transaksiId || '';
                }
                if (offlineTransactionModalEyebrow) offlineTransactionModalEyebrow.textContent = 'Edit Transaksi Offline';
                if (offlineTransactionModalTitle) offlineTransactionModalTitle.textContent = 'Perbarui Pembelian Toko';
                if (offlineTransactionModalDescription) offlineTransactionModalDescription.textContent = 'Ubah data transaksi offline beserta item produknya. Stok lama akan disesuaikan ulang otomatis.';
                offlineTransactionSubmitButton.textContent = 'Simpan Perubahan';
                return;
            }

            offlineTransactionForm.action = @json(route('admin.transaksi.store-offline'));
            offlineTransactionMethod.value = 'POST';
            if (offlineTransactionId) {
                offlineTransactionId.value = '';
            }
            if (offlineTransactionModalEyebrow) offlineTransactionModalEyebrow.textContent = 'Tambah Transaksi Offline';
            if (offlineTransactionModalTitle) offlineTransactionModalTitle.textContent = 'Catat Pembelian Toko';
            if (offlineTransactionModalDescription) offlineTransactionModalDescription.textContent = 'Transaksi offline tidak memakai Midtrans. Metode pembayaran bisa dipilih langsung dan status pesanan otomatis selesai.';
            offlineTransactionSubmitButton.textContent = 'Simpan Transaksi Offline';
        };

        const resetOfflineForm = () => {
            if (offlineTransactionForm) {
                offlineTransactionForm.reset();
            }

            if (offlineTanggalTransaksi) {
                offlineTanggalTransaksi.value = @json(now()->format('Y-m-d\TH:i'));
            }

            if (offlineOngkir) {
                offlineOngkir.value = 0;
            }

            resetOfflineRows();
            setOfflineModalMode('create');
        };

        const fillOfflineFormForEdit = (button) => {
            setOfflineModalMode('edit', {
                action: button.dataset.updateAction,
                transaksiId: button.dataset.transaksiId,
            });

            if (offlineNamaPenerima) offlineNamaPenerima.value = button.dataset.namaPenerima || '';
            if (offlineTanggalTransaksi) offlineTanggalTransaksi.value = button.dataset.tanggalTransaksi || '';
            if (offlineMetodePembayaran) offlineMetodePembayaran.value = button.dataset.metodePembayaran || '';
            if (offlineNoTelpPenerima) offlineNoTelpPenerima.value = button.dataset.noTelpPenerima || '';
            if (offlineDetailAlamat) offlineDetailAlamat.value = button.dataset.detailAlamat || '';
            if (offlineNomorKodePos) offlineNomorKodePos.value = button.dataset.nomorKodePos || '';
            if (offlineNamaKecamatan) offlineNamaKecamatan.value = button.dataset.namaKecamatan || '';
            if (offlineNamaKabupaten) offlineNamaKabupaten.value = button.dataset.namaKabupaten || '';
            if (offlineNamaProvinsi) offlineNamaProvinsi.value = button.dataset.namaProvinsi || '';
            if (offlineResi) offlineResi.value = button.dataset.resi || '';
            if (offlineOngkir) offlineOngkir.value = button.dataset.ongkir || 0;

            let items = [{ produk_id: '', jumlah_produk: 1 }];

            try {
                items = JSON.parse(button.dataset.items || '[]');
            } catch (error) {
                items = [{ produk_id: '', jumlah_produk: 1 }];
            }

            resetOfflineRows(items.length ? items : [{ produk_id: '', jumlah_produk: 1 }]);
        };

        if (offlineItemsContainer) {
            offlineItemsContainer.querySelectorAll('.offlineItemRow').forEach((row) => {
                attachOfflineRowEvents(row);
            });

            renumberOfflineRows();
        }

        if (openOfflineModalButton) {
            openOfflineModalButton.addEventListener('click', () => {
                resetOfflineForm();
                openOfflineModal();
            });
        }

        if (editOfflineModalButtons.length) {
            editOfflineModalButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    fillOfflineFormForEdit(button);
                    openOfflineModal();
                });
            });
        }

        if (closeOfflineModalButton) {
            closeOfflineModalButton.addEventListener('click', closeOfflineModal);
        }

        if (cancelOfflineModalButton) {
            cancelOfflineModalButton.addEventListener('click', closeOfflineModal);
        }

        if (offlineModalOverlay) {
            offlineModalOverlay.addEventListener('click', closeOfflineModal);
        }

        if (addOfflineItemRowButton) {
            addOfflineItemRowButton.addEventListener('click', addOfflineRow);
        }

        if (!rejectModal || !rejectModalOverlay || !rejectModalPanel || !rejectModalForm || !rejectReason || !rejectModalDescription || !closeRejectModal || !cancelRejectModal || !rejectButtons.length) {
            if (@json($errors->any() && !$errors->has('checkout'))) {
                openOfflineModal();
            }

            return;
        }

        const openRejectModal = (button) => {
            rejectModalForm.action = button.dataset.rejectAction || '';
            rejectReason.value = '';
            rejectModalDescription.textContent = `Tulis alasan pembatalan untuk pesanan #${button.dataset.orderId}.`;
            rejectModal.classList.remove('hidden');
            rejectModal.classList.add('flex');

            requestAnimationFrame(() => {
                rejectModalOverlay.classList.remove('opacity-0');
                rejectModalOverlay.classList.add('opacity-100');
                rejectModalPanel.classList.remove('opacity-0', 'scale-95');
                rejectModalPanel.classList.add('opacity-100', 'scale-100');
            });
        };

        const closeModal = () => {
            rejectModalOverlay.classList.remove('opacity-100');
            rejectModalOverlay.classList.add('opacity-0');
            rejectModalPanel.classList.remove('opacity-100', 'scale-100');
            rejectModalPanel.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                rejectModal.classList.add('hidden');
                rejectModal.classList.remove('flex');
            }, 200);
        };

        rejectButtons.forEach((button) => {
            button.addEventListener('click', () => openRejectModal(button));
        });

        closeRejectModal.addEventListener('click', closeModal);
        cancelRejectModal.addEventListener('click', closeModal);
        rejectModalOverlay.addEventListener('click', closeModal);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && offlineModal && !offlineModal.classList.contains('hidden')) {
                closeOfflineModal();
            }

            if (event.key === 'Escape' && !rejectModal.classList.contains('hidden')) {
                closeModal();
            }
        });

        if (@json($errors->any() && !$errors->has('checkout'))) {
            openOfflineModal();
        }
    })();
</script>
@endsection
