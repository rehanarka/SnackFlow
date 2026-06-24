@extends('layouts.sidebar')

@section('content')
@php
    $isEdit = $mode === 'edit';
    $formAction = $isEdit ? route('admin.artikel.update', $artikel) : route('admin.artikel.store');
@endphp

<div class="space-y-6 lg:-mt-5">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <div class="bg-[linear-gradient(135deg,_#ffffff_0%,_#eef7ff_55%,_#fff8eb_100%)] px-6 py-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">{{ $isEdit ? 'Edit Artikel' : 'Tambah Artikel' }}</p>
                    <h1 class="mt-3 text-3xl font-bold text-slate-900">{{ $isEdit ? 'Ubah Artikel' : 'Buat Artikel Baru' }}</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Isi judul, gambar opsional, dan konten artikel untuk memberi wawasan kepada pelanggan.</p>
                </div>
                <a href="{{ $isEdit ? route('admin.artikel.show', $artikel) : route('admin.artikel') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:bg-slate-50">
                    Kembali
                </a>
            </div>
        </div>
    </section>

    @if (session('artikel_error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">{{ session('artikel_error') }}</div>
    @endif

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-100">
        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="grid gap-6 p-6 lg:grid-cols-[320px_1fr]">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <aside class="space-y-4">
                <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50">
                    <div class="aspect-[4/3]">
                        @if ($isEdit && $artikel->gambar_artikel)
                            <img src="{{ asset('storage/' . $artikel->gambar_artikel) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center px-6 text-center text-sm font-semibold text-slate-400">Preview gambar artikel</div>
                        @endif
                    </div>
                </div>
                <p class="text-sm leading-6 text-slate-500">Gambar artikel boleh dikosongkan. Jika mengedit tanpa memilih gambar baru, gambar lama tetap dipakai.</p>
            </aside>

            <div class="space-y-5">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Judul Artikel</span>
                    <input type="text" name="judul" maxlength="100" value="{{ old('judul', $artikel?->judul) }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Gambar Artikel</span>
                    <input type="file" name="gambar_artikel" accept="image/*" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Konten Artikel</span>
                    <textarea name="konten_artikel" rows="12" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">{{ old('konten_artikel', $artikel?->konten_artikel) }}</textarea>
                </label>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ $isEdit ? route('admin.artikel.show', $artikel) : route('admin.artikel') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit" class="rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-sky-700">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah' }}
                    </button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
