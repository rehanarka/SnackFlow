@extends('layouts.sidebar')

@section('content')
@php($popupPeringatan = session('popup_peringatan'))

@include('katalog.partials.toolbar-pencarian-produk')

@include('katalog.partials.form-katalog-produk')
@include('katalog.partials.halaman-detail-katalog-produk')

<div class="mt-10">
    @if ($produks->count())
        <div id="produkGrid" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($produks as $produk)
                @include('katalog.partials.product-card', ['produk' => $produk])
            @endforeach
        </div>
        <div id="hasilPencarianKosong" class="mt-8 hidden rounded-2xl border border-dashed border-slate-300 bg-white py-14 text-center">
            <h2 class="text-lg font-semibold text-slate-700">Produk tidak ditemukan</h2>
            <p class="mt-2 text-sm text-slate-500">Coba gunakan kata kunci lain untuk nama atau kategori produk.</p>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center">
            <h2 class="text-lg font-semibold text-gray-700">Belum ada produk</h2>
            <p class="mt-2 text-sm text-gray-500">Klik tombol <span class="font-medium">Tambah Produk</span> untuk menambahkan data pertama.</p>
        </div>
    @endif
</div>

@include('katalog.partials.modal-hapus-produk')
@include('katalog.partials.modal-peringatan-produk', ['pesan' => $popupPeringatan])

@include('katalog.partials.modal-update-produk')
@endsection
