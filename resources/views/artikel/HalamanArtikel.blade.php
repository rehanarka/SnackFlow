@extends('layouts.sidebar')

@section('content')
@php
    $isAdminView = $isAdminView ?? false;
    $artikelIndexRoute = $isAdminView ? 'admin.artikel' : 'user.artikel';
    $artikelShowRoute = $isAdminView ? 'admin.artikel.show' : 'user.artikel.show';
@endphp

<div class="-mt-5 space-y-6">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="bg-[linear-gradient(135deg,_#ffffff_0%,_#eef7ff_55%,_#fff8eb_100%)] px-6 py-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Artikel</p>
                    <h1 class="mt-3 text-3xl font-bold text-slate-900">Daftar Artikel</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ $isAdminView ? 'Kelola artikel wawasan seperti manfaat produk, cerita bahan, atau informasi camilan untuk pelanggan.' : 'Baca artikel wawasan seperti manfaat produk, cerita bahan, atau informasi camilan dari SnackFlow.' }}</p>
                </div>
                @if ($isAdminView)
                    <a href="{{ route('admin.artikel.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800">
                        Tambah Artikel
                    </a>
                @endif
            </div>
        </div>
    </section>

    @if (session('artikel_success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('artikel_success') }}</div>
    @endif

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-900">Artikel</h2>
            <p class="mt-1 text-sm text-slate-500">Klik salah satu artikel untuk melihat detail lengkapnya.</p>
        </div>

        @if ($artikels->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto max-w-md rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-10">
                    <h3 class="text-lg font-semibold text-slate-800">Belum ada artikel</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $isAdminView ? 'Klik tombol Tambah Artikel untuk membuat artikel pertama.' : 'Artikel akan tampil di sini setelah admin menambahkan data artikel.' }}</p>
                </div>
            </div>
        @else
            <div class="grid gap-6 p-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($artikels as $artikel)
                    <a href="{{ route($artikelShowRoute, $artikel) }}" class="group overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                            @if ($artikel->gambar_artikel)
                                <img src="{{ asset('storage/' . $artikel->gambar_artikel) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center text-sm font-semibold text-slate-400">Tidak ada gambar</div>
                            @endif
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Artikel</p>
                            <h3 class="mt-2 text-lg font-bold leading-tight text-slate-900">{{ $artikel->judul }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
