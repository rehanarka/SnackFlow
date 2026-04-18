@extends('layouts.sidebar')

@section('content')
@php
    $user = auth()->user();
    $avatarUrl = '/images/avatar-default.png';
    $updateProfileRoute = route(($user->role === 'admin' ? 'admin' : 'user') . '.profile.update');

    if (!empty($user->avatar)) {
        $avatarUrl = filter_var($user->avatar, FILTER_VALIDATE_URL) ? $user->avatar : asset('storage/' . $user->avatar);
    }
@endphp

<div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg shadow-slate-100">
        <div class="bg-gradient-to-r from-sky-100 via-white to-cyan-50 px-6 py-5">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Profile</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
            <p class="mt-2 text-sm text-slate-600">Informasi akun aktif yang sedang digunakan di SnackFlow.</p>
        </div>

        <div class="space-y-5 px-6 py-6">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Nama</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $user->name }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Role</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ ucfirst($user->role) }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Email</p>
                <p class="mt-2 text-base font-semibold text-slate-900">{{ $user->email }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">No. Telp</p>
                <p class="mt-2 text-base font-semibold text-slate-900">{{ $user->no_telp ?: '-' }}</p>
            </div>
        </div>
    </section>

    <aside class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg shadow-slate-100">
        <div class="flex flex-col items-center px-6 py-8 text-center">
            <img src="{{ $avatarUrl }}" alt="Avatar {{ $user->name }}" class="h-28 w-28 rounded-[2rem] object-cover shadow-lg ring-4 ring-sky-100">
            <h3 class="mt-5 text-xl font-bold text-slate-900">{{ $user->name }}</h3>
            <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
            <span class="mt-4 inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-white">{{ ucfirst($user->role) }}</span>
        </div>

        <div class="border-t border-slate-200 px-6 py-6">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Edit Profile</p>
                    <p class="text-xs text-slate-500">Perbarui nama, email, dan nomor telepon anda.</p>
                </div>
                <span class="rounded-full bg-sky-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-600">Editable</span>
            </div>

            <form action="{{ $updateProfileRoute }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                @method('PATCH')

                <div>
                    <label for="avatar" class="mb-1 block text-sm font-medium text-slate-700">Foto Profile</label>
                    <input id="avatar" name="avatar" type="file" accept="image/*" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-700 outline-none file:mr-3 file:rounded-full file:border-0 file:bg-sky-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-sky-700">
                    <p class="mt-2 text-xs text-slate-500">Biarkan kosong jika tidak ingin mengganti foto profile.</p>
                    @error('avatar')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-700 outline-none transition duration-300 focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-700 outline-none transition duration-300 focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_telp" class="mb-1 block text-sm font-medium text-slate-700">No. Telp</label>
                    <input id="no_telp" name="no_telp" type="text" value="{{ old('no_telp', $user->no_telp) }}" placeholder="Masukkan nomor telepon" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-700 outline-none transition duration-300 focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                    @error('no_telp')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <a href="/send-email" class="text-sm text-sky-600 hover:text-sky-800 mt-10">Edit Password</a>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800">Simpan Perubahan</button>
            </form>
        </div>
    </aside>
</div>
@endsection
