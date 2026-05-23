@extends('layouts.sidebar')

@section('content')
@php
    $reviewCount = $transaksi->reviewProduk->count();
    $itemCount = $transaksi->detailTransaksi->count();
@endphp

<div class="-mt-5 space-y-6">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="bg-[linear-gradient(135deg,_#ffffff_0%,_#fff8eb_54%,_#f7fbff_100%)] px-6 py-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-amber-600">Review Produk</p>
                    <h1 class="mt-3 text-3xl font-bold text-slate-900">Beri Review Pesanan #{{ $transaksi->id }}</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Isi ulasan untuk produk dari transaksi yang sudah selesai. Rating disimpan sebagai angka 1 sampai 5, dan foto review boleh dikosongkan.</p>
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

    @if (session('review_error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">{{ session('review_error') }}</div>
    @endif

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-900">Produk yang Direview</h2>
            <p class="mt-1 text-sm text-slate-500">Kirim review satu per satu untuk setiap produk di transaksi ini.</p>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach ($transaksi->detailTransaksi as $detail)
                @php($reviewTersimpan = $transaksi->reviewProduk->firstWhere('produk_id', $detail->produk_id))
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

                            @if ($reviewTersimpan)
                                <p class="mt-3 inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">Review sudah tersimpan</p>
                            @endif
                        </div>
                    </aside>

                    <div class="space-y-5">
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Rating Produk</p>
                            <div class="ratingStars mt-2 flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input id="rating-{{ $detail->id }}-{{ $i }}" type="radio" name="rating" value="{{ $i }}" class="hidden" {{ (int) old('rating', $reviewTersimpan?->rating) === $i ? 'checked' : '' }}>
                                    <label for="rating-{{ $detail->id }}-{{ $i }}" data-rating-value="{{ $i }}" class="cursor-pointer text-4xl text-slate-200 transition hover:text-amber-300">&#9733;</label>
                                @endfor
                            </div>
                        </div>

                        <label class="block">
                            <span class="text-sm font-semibold text-slate-700">Review Produk</span>
                            <textarea name="review_produk" rows="5" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100" placeholder="Tulis pengalaman kamu dengan produk ini.">{{ old('review_produk', $reviewTersimpan?->review_produk) }}</textarea>
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
</div>
@endsection
