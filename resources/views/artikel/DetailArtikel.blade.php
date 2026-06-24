@extends('layouts.sidebar')

@section('content')
@php
    $isAdminView = $isAdminView ?? false;
    $artikelIndexRoute = $isAdminView ? 'admin.artikel' : 'user.artikel';
@endphp

<div class="space-y-6 lg:-mt-5">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="grid lg:grid-cols-[360px_1fr]">
            <div class="bg-slate-100">
                <div class="aspect-[4/3] lg:h-full lg:aspect-auto">
                    @if ($artikel->gambar_artikel)
                        <img src="{{ asset('storage/' . $artikel->gambar_artikel) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full min-h-72 items-center justify-center text-sm font-semibold text-slate-400">Tidak ada gambar</div>
                    @endif
                </div>
            </div>

            <div class="bg-[linear-gradient(135deg,_#ffffff_0%,_#eef7ff_55%,_#fff8eb_100%)] px-6 py-7">
                <div class="flex flex-col gap-5 lg:h-full lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Detail Artikel</p>
                        <h1 class="mt-3 text-2xl font-bold leading-tight text-slate-900 sm:text-3xl">{{ $artikel->judul }}</h1>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route($artikelIndexRoute) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:bg-slate-50">
                            Kembali
                        </a>
                        @if ($isAdminView)
                            <a href="{{ route('admin.artikel.edit', $artikel) }}" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-sky-700">
                                Edit
                            </a>
                            <button type="button" class="deleteArtikelBtn rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-rose-700" data-delete-url="{{ route('admin.artikel.destroy', $artikel) }}">
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (session('artikel_success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('artikel_success') }}</div>
    @endif

    <section class="rounded-[2rem] border border-slate-200 bg-white px-6 py-6 shadow-xl shadow-slate-100">
        <h2 class="text-lg font-semibold text-slate-900">Isi Artikel</h2>
        <div class="mt-5 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $artikel->konten_artikel }}</div>
    </section>

    @if ($isAdminView)
    <div id="deleteArtikelModal" class="fixed inset-0 z-[70] hidden items-center justify-center px-4">
        <div id="deleteArtikelOverlay" class="absolute inset-0 bg-slate-950/50"></div>
        <div class="relative w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-600">Hapus Artikel</p>
                    <h3 class="mt-2 text-xl font-bold text-slate-900">Konfirmasi Hapus</h3>
                </div>
                <button type="button" id="closeDeleteArtikelModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">&times;</button>
            </div>

            <p class="mt-5 text-sm leading-6 text-slate-600">Apakah anda yakin ingin menghapus artikel ini?</p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancelDeleteArtikelModal" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Tidak
                </button>
                <form id="deleteArtikelForm" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700 sm:w-auto">
                        Iya
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
