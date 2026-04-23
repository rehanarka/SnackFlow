@php
    $user = auth()->user();
    $avatarUrl = '/images/avatar-default.png';
    $profileRoute = route(($user->role === 'admin' ? 'admin' : 'user') . '.profile');

    if (!empty($user->avatar)) {
        $avatarUrl = filter_var($user->avatar, FILTER_VALIDATE_URL) ? $user->avatar : asset('storage/' . $user->avatar);
    }

    $roleLabel = $user->role === 'admin' ? 'Admin SnackFlow' : 'Member SnackFlow';
    $routeName = request()->route()?->getName() ?? '';
    $requestPath = request()->path();

    $pageTitle = 'Profile';
    $pageDescription = 'Kelola informasi akun anda';

    if (str_ends_with($routeName, '.dashboard')) {
        $pageTitle = 'Dashboard';
        $pageDescription = $user->role === 'admin' ? 'Pantau aktivitas toko dan ringkasan operasional.' : 'Lihat ringkasan akun dan aktivitas belanja anda.';
    } elseif (str_ends_with($routeName, '.katalog')) {
        $pageTitle = 'Produk';
        $pageDescription = $user->role === 'admin' ? 'Kelola katalog produk dan stok yang tersedia.' : 'Jelajahi produk yang tersedia untuk dipesan.';
    } elseif (str_contains($routeName, 'transaksi') || str_contains($routeName, 'checkout') || str_contains($requestPath, 'transaksi') || str_contains($requestPath, 'checkout') || str_contains($requestPath, 'keranjang')) {
        $pageTitle = 'Transaksi';
        $pageDescription = $user->role === 'admin' ? 'Pantau proses pesanan dan aktivitas transaksi.' : 'Kelola keranjang, checkout, dan pesanan anda.';
    } elseif (str_contains($requestPath, 'shipping') || str_contains($requestPath, 'kurir')) {
        $pageTitle = 'Pengiriman';
        $pageDescription = 'Atur informasi pengiriman dan status kurir.';
    }
@endphp

<div class="fixed top-0 left-64 right-0 z-20 h-[122px] rounded-2xl shadow-xl">
    <div class="absolute inset-0 overflow-hidden rounded-2xl">
        <img src="/images/header.png" class="h-full w-full shadow-2xl" alt="">
    </div>

    <div class="relative z-10 flex h-full items-center justify-between px-6">
        <div class="flex flex-col">
            <h1 class="text-2xl font-bold text-black">{{ $pageTitle }}</h1>
            <p class="text-sm text-black/80">{{ $pageDescription }}</p>
        </div>

        <div class="relative">
            <button id="profileDropdownToggle" type="button" aria-expanded="false" class=" flex items-center rounded-full border border-white bg-white/75 shadow-lg backdrop-blur-md transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:cursor-pointer">
                <img src="{{ $avatarUrl }}" alt="" class="h-[60px] w-[60px] rounded-full object-cover transition duration-300 group-hover:ring-blue-200">
            </button>

            <div id="profileDropdownMenu" class="pointer-events-none absolute right-0  hidden w-80 translate-y-2 opacity-0 transition duration-200">
                <div class="overflow-hidden rounded-3xl border border-white/70 bg-white/90 shadow-2xl backdrop-blur-xl">
                    <div class="relative overflow-hidden bg-gradient-to-br from-sky-100 via-white to-cyan-50 px-5 pb-5 pt-4">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-sky-400 via-cyan-300 to-blue-500"></div>

                        <div class="flex items-center gap-4">
                                <img src="{{ $avatarUrl }}" alt="Avatar {{ $user->nama_lengkap }}" class="h-16 w-16 rounded-2xl object-cover shadow-md ring-2 ring-white">
                            <div class="min-w-0">
                                <p class="truncate text-base font-semibold text-slate-900">{{ $user->nama_lengkap }}</p>
                                <p class="truncate text-sm text-slate-500">{{ $user->email }}</p>
                                <span class="mt-2 inline-flex rounded-full bg-slate-900 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-white">{{ $roleLabel }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 px-5 py-4">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Profile</p>
                            <p class="mt-2 text-sm text-slate-700">Masuk sebagai <span class="font-semibold text-slate-900">{{ $user->nama_lengkap }}</span>. Kelola sesi akunmu sepenuhnya difitur profile.</p>
                        </div>

                        <a href="{{ $profileRoute }}" class="flex w-full items-center justify-between rounded-2xl bg-sky-500 px-4 py-3 text-left text-white shadow-lg shadow-sky-200 transition duration-300 hover:-translate-y-0.5 hover:bg-sky-600">
                            <span>
                                <span class="block text-sm font-semibold">Profile</span>
                                <span class="block text-xs text-sky-100">Buka halaman detail akun anda</span>
                            </span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7h5m0 0v5m0-5-6 6M8 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-2"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
