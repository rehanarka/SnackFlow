@extends('layouts.sidebar')

@section('content')
@php
    $reviews = $transaksi->reviewProduk;
    $reviewCount = $reviews->count();
    $itemCount = $transaksi->detailTransaksi->count();
    $hasReview = $reviewCount > 0;
@endphp

<div class="space-y-6 lg:-mt-5">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="bg-[linear-gradient(135deg,_#ffffff_0%,_#fff8eb_54%,_#f7fbff_100%)] px-6 py-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-amber-600">Review Produk</p>
                    <h1 class="mt-3 text-3xl font-bold text-slate-900">{{ $hasReview ? 'Data Review Pesanan' : 'Beri Review Pesanan' }} #{{ $transaksi->id }}</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        {{ $hasReview
                            ? 'Review yang sudah kamu kirim untuk transaksi selesai ini ditampilkan di sini.'
                            : 'Transaksi ini belum memiliki review. Isi rating bintang dan ulasan produk untuk mengirim review.' }}
                    </p>
                </div>
                <a href="{{ route('user.transaksi') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:bg-slate-50">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid border-t border-slate-200 bg-white sm:grid-cols-3">
            <div class="border-b border-slate-200 px-6 py-5 sm:border-b-0 sm:border-r">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p>
                <p class="mt-2 text-lg font-bold text-slate-900">{{ $transaksi->status_pesanan }}</p>
            </div>
            <div class="border-b border-slate-200 px-6 py-5 sm:border-b-0 sm:border-r">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Produk</p>
                <p class="mt-2 text-lg font-bold text-slate-900">{{ $itemCount }} item</p>
            </div>
            <div class="px-6 py-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Review Tersimpan</p>
                <p class="mt-2 text-lg font-bold text-slate-900">{{ $reviewCount }} dari {{ $itemCount }}</p>
            </div>
        </div>
    </section>

    @if (session('review_success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('review_success') }}</div>
    @endif

    @if (session('review_delete_success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('review_delete_success') }}</div>
    @endif

    @if (session('review_error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">{{ session('review_error') }}</div>
    @endif

    @if ($hasReview)
        <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">Data Review Produk</h2>
                <p class="mt-1 text-sm text-slate-500">Klik hapus jika ingin menghapus review yang sudah kamu kirim.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach ($reviews as $review)
                    <article class="grid gap-6 px-6 py-6 lg:grid-cols-[170px_1fr_auto]">
                        <div class="overflow-hidden rounded-[1.25rem] border border-slate-200 bg-slate-50">
                            <div class="aspect-square">
                                @if ($review->foto_review)
                                    <img src="{{ asset('storage/' . $review->foto_review) }}" alt="Foto review {{ $review->produk->nama_produk ?? 'produk' }}" class="h-full w-full object-cover">
                                @elseif ($review->produk?->foto_produk)
                                    <img src="{{ asset('storage/' . $review->produk->foto_produk) }}" alt="{{ $review->produk->nama_produk }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center px-4 text-center text-xs font-semibold text-slate-400">Tidak ada foto</div>
                                @endif
                            </div>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-lg font-bold text-slate-900">{{ $review->produk->nama_produk ?? 'Produk' }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-500">Transaksi #{{ $review->transaksi_id }}</p>
                                </div>
                                <div class="flex items-center gap-1 text-xl" aria-label="Rating {{ $review->rating }} dari 5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}">&#9733;</span>
                                    @endfor
                                </div>
                            </div>

                            <p class="mt-4 rounded-2xl bg-slate-50 px-4 py-4 text-sm leading-6 text-slate-700">{{ $review->review_produk }}</p>
                        </div>

                        <div class="flex items-start lg:justify-end">
                            <button
                                type="button"
                                class="deleteReviewBtn rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-rose-700"
                                data-delete-url="{{ route('user.transaksi.review.destroy', [$transaksi, $review]) }}"
                                data-product-name="{{ $review->produk->nama_produk ?? 'review ini' }}"
                            >
                                Hapus
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <div id="deleteReviewModal" class="fixed inset-0 z-[70] hidden items-center justify-center px-4">
            <div id="deleteReviewOverlay" class="absolute inset-0 bg-slate-950/50"></div>
            <div class="relative w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-600">Hapus Review</p>
                        <h3 class="mt-2 text-xl font-bold text-slate-900">Konfirmasi Hapus</h3>
                    </div>
                    <button type="button" id="closeDeleteReviewModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">&times;</button>
                </div>

                <p id="deleteReviewMessage" class="mt-5 text-sm leading-6 text-slate-600">Apakah anda yakin ingin menghapus review ini?</p>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" id="cancelDeleteReviewModal" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Tidak
                    </button>
                    <form id="deleteReviewForm" method="POST" action="#">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700 sm:w-auto">
                            Iya
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">Form Review Produk</h2>
                <p class="mt-1 text-sm text-slate-500">Belum ada review untuk transaksi ini. Kirim review satu per satu untuk produk di bawah.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach ($transaksi->detailTransaksi as $detail)
                    <form action="{{ route('user.transaksi.review.store', $transaksi) }}" method="POST" enctype="multipart/form-data" class="grid gap-6 px-6 py-6 lg:grid-cols-[260px_1fr]">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $detail->produk_id }}">

                        <aside class="space-y-4">
                            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50">
                                <div class="aspect-[4/3]">
                                    @if ($detail->produk?->foto_produk)
                                        <img src="{{ asset('storage/' . $detail->produk->foto_produk) }}" alt="{{ $detail->produk->nama_produk }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full items-center justify-center text-sm font-semibold text-slate-400">No Image</div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $detail->produk->nama_produk ?? 'Produk' }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $detail->jumlah_produk }} x Rp {{ number_format($detail->harga_produk, 0, ',', '.') }}</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">Subtotal Rp {{ number_format($detail->subtotal_produk, 0, ',', '.') }}</p>
                            </div>
                        </aside>

                        <div class="space-y-5">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Rating Produk</p>
                                <div class="ratingStars mt-2 flex gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <input id="rating-{{ $detail->id }}-{{ $i }}" type="radio" name="rating" value="{{ $i }}" class="hidden" {{ (int) old('rating') === $i ? 'checked' : '' }}>
                                        <label for="rating-{{ $detail->id }}-{{ $i }}" data-rating-value="{{ $i }}" class="cursor-pointer text-4xl text-slate-200 transition hover:text-amber-300">&#9733;</label>
                                    @endfor
                                </div>
                            </div>

                            <label class="block">
                                <span class="text-sm font-semibold text-slate-700">Review Produk</span>
                                <textarea name="review_produk" rows="5" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100" placeholder="Tulis pengalaman kamu dengan produk ini.">{{ old('review_produk') }}</textarea>
                            </label>

                            <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                                <label class="block">
                                    <span class="text-sm font-semibold text-slate-700">Foto Review</span>
                                    <input type="file" name="foto_review" accept="image/*" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                                    <span class="mt-2 block text-xs text-slate-500">Opsional, boleh dikosongkan.</span>
                                </label>

                                <button type="submit" class="rounded-[1.5rem] bg-amber-500 px-7 py-4 text-base font-semibold text-white shadow-lg shadow-amber-100 transition duration-300 hover:-translate-y-0.5 hover:bg-amber-600">
                                    Kirim Review
                                </button>
                            </div>
                        </div>
                    </form>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
