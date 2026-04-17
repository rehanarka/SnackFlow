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
        'Menunggu Pembayaran' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'Menunggu Konfirmasi' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'Diproses' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'Dibatalkan' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'Ditolak' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'Menunggu Verifikasi' => 'bg-violet-50 text-violet-700 ring-violet-200',
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

    <section class="rounded-[2rem] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.12),_transparent_30%),linear-gradient(135deg,_#ffffff_0%,_#f8fbff_55%,_#eefbf7_100%)] px-8 py-8 shadow-xl shadow-emerald-100/50">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Transaksi Admin</p>
                <h1 class="mt-3 text-3xl font-bold text-slate-900">Kelola Pesanan Masuk</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Lihat semua transaksi, cek detail pesanan, lalu terima atau tolak pesanan yang masih menunggu konfirmasi.</p>
            </div>

            <form action="{{ route('admin.transaksi') }}" method="GET" class="grid gap-3 sm:grid-cols-[1fr_1fr_1fr_auto_auto]">
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 hover:cursor-pointer">
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 hover:cursor-pointer">
                <select name="status" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 hover:cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Konfirmasi" @selected(($filters['status'] ?? '') === 'Menunggu Konfirmasi')>Menunggu Konfirmasi</option>
                    <option value="Menunggu Pembayaran" @selected(($filters['status'] ?? '') === 'Menunggu Pembayaran')>Menunggu Pembayaran</option>
                    <option value="Diproses" @selected(($filters['status'] ?? '') === 'Diproses')>Diproses</option>
                    <option value="Ditolak" @selected(($filters['status'] ?? '') === 'Ditolak')>Ditolak</option>
                    <option value="Dibatalkan" @selected(($filters['status'] ?? '') === 'Dibatalkan')>Dibatalkan</option>
                </select>
                <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:cursor-pointer">Filter</button>
                <a href="{{ route('admin.transaksi') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:bg-slate-50 hover:cursor-pointer">Reset</a>
            </form>
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
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Perlu Diproses</p>
            <p class="mt-3 text-3xl font-bold text-emerald-600">{{ $transaksi->where('status_pesanan', 'Diproses')->count() }}</p>
        </div>
        <div class="rounded-[1.75rem] border border-slate-200 bg-white px-6 py-5 shadow-lg shadow-slate-100/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Ditolak</p>
            <p class="mt-3 text-3xl font-bold text-rose-600">{{ $transaksi->where('status_pesanan', 'Ditolak')->count() }}</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100/80">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-900">Daftar Transaksi Masuk</h2>
            <p class="mt-1 text-sm text-slate-500">Pesanan berstatus <span class="font-semibold text-sky-700">Menunggu Konfirmasi</span> siap kamu review sekarang. Setelah diterima, user baru bisa lanjut ke pembayaran.</p>
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
                                    $paymentLabel = $paymentTypeLabels[$item->payment_type] ?? Str::headline($item->payment_type ?? '-');
                                    $statusLabel = $item->status_pesanan ?: '-';
                                    $statusClass = $statusClasses[$statusLabel] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
                                @endphp
                                <tr class="align-top transition duration-300 hover:bg-slate-50/80">
                                    <td class="px-6 py-5">
                                        <p class="text-sm font-semibold text-slate-900">#{{ $item->id }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-500">{{ $item->midtrans_order_id ?? 'Order lokal' }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-sm font-semibold text-slate-900">{{ $item->user->name ?? 'User' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $item->nama_penerima }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-sm font-semibold text-slate-800">{{ $item->created_at->format('d M Y') }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $item->created_at->format('H:i') }} WIB</p>
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
                                                {{ $statusLabel === 'Diproses' ? 'Perlu Diproses' : $statusLabel }}
                                            </span>
                                            @if ($item->alasan_penolakan)
                                                <p class="max-w-xs text-xs leading-5 text-rose-600">Alasan: {{ $item->alasan_penolakan }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="space-y-3">
                                            <a href="{{ route('admin.transaksi.show', $item) }}" class="inline-flex rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:cursor-pointer">
                                                Lihat Detail
                                            </a>

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
                                                    Tolak
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

<div id="rejectModal" class="fixed inset-0 z-[75] hidden items-center justify-center px-4">
    <div id="rejectModalOverlay" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300"></div>
    <div id="rejectModalPanel" class="relative w-full max-w-lg scale-95 rounded-[2rem] bg-white px-6 py-6 opacity-0 shadow-2xl transition duration-300">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-600">Tolak Pesanan</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Konfirmasi Penolakan</h2>
                <p id="rejectModalDescription" class="mt-2 text-sm leading-6 text-slate-600">Tulis alasan penolakan untuk pesanan ini.</p>
            </div>
            <button type="button" id="closeRejectModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-slate-200 hover:cursor-pointer">&times;</button>
        </div>

        <form id="rejectModalForm" action="" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="rejectReason" class="mb-2 block text-sm font-medium text-slate-700">Alasan Penolakan</label>
                <textarea id="rejectReason" name="alasan_penolakan" rows="4" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100" placeholder="Tulis alasan penolakan pesanan ini..." required></textarea>
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
        const rejectModal = document.getElementById('rejectModal');
        const rejectModalOverlay = document.getElementById('rejectModalOverlay');
        const rejectModalPanel = document.getElementById('rejectModalPanel');
        const rejectModalForm = document.getElementById('rejectModalForm');
        const rejectReason = document.getElementById('rejectReason');
        const rejectModalDescription = document.getElementById('rejectModalDescription');
        const closeRejectModal = document.getElementById('closeRejectModal');
        const cancelRejectModal = document.getElementById('cancelRejectModal');
        const rejectButtons = document.querySelectorAll('.openRejectModal');

        if (!rejectModal || !rejectModalOverlay || !rejectModalPanel || !rejectModalForm || !rejectReason || !rejectModalDescription || !closeRejectModal || !cancelRejectModal || !rejectButtons.length) {
            return;
        }

        const openRejectModal = (button) => {
            rejectModalForm.action = button.dataset.rejectAction || '';
            rejectReason.value = '';
            rejectModalDescription.textContent = `Tulis alasan penolakan untuk pesanan #${button.dataset.orderId}.`;
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
            if (event.key === 'Escape' && !rejectModal.classList.contains('hidden')) {
                closeModal();
            }
        });
    })();
</script>
@endsection
