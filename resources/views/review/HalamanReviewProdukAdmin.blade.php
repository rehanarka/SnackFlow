@extends('layouts.sidebar')

@section('content')
@php
    $isAdminView = $isAdminView ?? false;
    $backRoute = $backRoute ?? ($isAdminView ? route('admin.katalog') : route('user.katalog'));
    $reviews = $produk->reviewProduk;
    $averageRating = $reviews->count() ? round($reviews->avg('rating'), 1) : 0;
@endphp

<div class="space-y-6 lg:-mt-5">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="grid lg:grid-cols-[280px_1fr]">
            <div class="bg-slate-50">
                <div class="aspect-[4/3] lg:h-full lg:aspect-auto">
                    @if ($produk->foto_produk)
                        <img src="{{ asset('storage/' . $produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full min-h-64 items-center justify-center text-sm font-semibold text-slate-400">No Image</div>
                    @endif
                </div>
            </div>

            <div class="bg-[linear-gradient(135deg,_#ffffff_0%,_#fff8eb_52%,_#f7fbff_100%)] px-6 py-7">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-amber-600">Review Produk</p>
                        <h1 class="mt-3 text-3xl font-bold text-slate-900">{{ $produk->nama_produk }}</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Halaman ini menampilkan semua ulasan user untuk produk yang dipilih dari katalog.</p>
                    </div>
                    <a href="{{ $backRoute }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:bg-slate-50">
                        Kembali
                    </a>
                </div>

                <div class="mt-7 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/80 bg-white/80 px-4 py-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total Review</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $reviews->count() }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/80 bg-white/80 px-4 py-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Rating Rata-rata</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($averageRating, 1, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/80 bg-white/80 px-4 py-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Stok Produk</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $produk->stok }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Daftar Review User</h2>
                    <p class="mt-1 text-sm text-slate-500">Ulasan, rating, foto, dan transaksi asal review.</p>
                </div>
                <div class="flex items-center gap-1 text-xl" aria-label="Rating rata-rata {{ $averageRating }} dari 5">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= round($averageRating) ? 'text-amber-400' : 'text-slate-200' }}">&#9733;</span>
                    @endfor
                </div>
            </div>
        </div>

        @if ($reviews->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto max-w-md rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-10">
                    <h3 class="text-lg font-semibold text-slate-800">Belum ada review</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Review dari user akan muncul setelah transaksi selesai dan user mengirim ulasan.</p>
                </div>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($reviews as $review)
                    <article class="grid gap-5 px-6 py-6 lg:grid-cols-[150px_1fr]">
                        <div class="overflow-hidden rounded-[1.25rem] border border-slate-200 bg-slate-50">
                            <div class="aspect-square">
                                @if ($review->foto_review)
                                    <img src="{{ asset('storage/' . $review->foto_review) }}" alt="Foto review {{ $produk->nama_produk }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center px-4 text-center text-xs font-semibold text-slate-400">Tidak ada foto</div>
                                @endif
                            </div>
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-base font-semibold text-slate-900">{{ $review->user->nama_lengkap ?? 'User' }}</p>
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
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
