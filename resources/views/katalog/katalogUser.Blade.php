@extends('layouts.sidebar')

@section('content')
<x-katalog.toolbar-pencarian-produk :show-tambah-button="false" :show-cart-button="true" :cart-count="0" />

<div class="mt-10">
    @if ($produks->count())
        <div id="produkGrid" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($produks as $produk)
                <x-katalog.product-card-user :produk="$produk" />
            @endforeach
        </div>

        <div id="hasilPencarianKosong" class="mt-8 hidden rounded-2xl border border-dashed border-slate-300 bg-white py-14 text-center">
            <h2 class="text-lg font-semibold text-slate-700">Produk tidak ditemukan</h2>
            <p class="mt-2 text-sm text-slate-500">Coba gunakan kata kunci lain untuk nama atau kategori produk.</p>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center">
            <h2 class="text-lg font-semibold text-gray-700">Belum ada produk</h2>
            <p class="mt-2 text-sm text-gray-500">Produk akan muncul di sini setelah katalog tersedia.</p>
        </div>
    @endif
</div>
@endsection
