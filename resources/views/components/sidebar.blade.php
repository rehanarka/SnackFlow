@php
    $role = auth()->user()->role === 'admin' ? 'admin' : 'user';
@endphp

<div id="sidebar" class="fixed top-0 left-0 z-40 w-67 h-screen overflow-hidden shadow-2xl">
    <div class="absolute inset-0">
        <img src="/images/sidebar.png" alt="" class="w-full h-full">
    </div>

    <div class="relative z-10 h-full pt-7 pl-5 pr-4">
        <div class="border-b border-gray-300 pb-4 flex flex-col">
            <h1 class="text-blue-500 text-2xl italic font-bold text-heading">SnackFlow</h1>
            <p class="text-gray-500">Manajemen Penjualan</p>
        </div>

        <div class="py-5 overflow-hidden">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route($role . '.dashboard') }}" class="flex {{ request()->routeIs($role . '.dashboard') ? 'bg-white border-l-4 border-blue-600' : 'bg-transparent' }} items-center px-2 py-1.5 text-body rounded-base {{ !request()->routeIs($role . '.dashboard') ? 'hover:bg-white transition duration-300 hover:scale-105' : 'hover:bg-blue-200 transition duration-300 hover:scale-105' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs($role . '.dashboard') ? 'text-blue-600' : 'text-black' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z"/>
                        </svg>
                        <span class="ml-3 {{ request()->routeIs($role . '.dashboard') ? 'text-blue-600' : 'text-black' }}">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route($role . '.katalog') }}" class="{{ request()->routeIs($role . '.katalog') ? 'bg-white border-l-4 border-blue-600' : 'bg-transparent' }} {{ !request()->routeIs($role . '.katalog') ? 'hover:bg-white transition duration-300 hover:scale-105' : 'hover:bg-blue-200 transition duration-300 hover:scale-105' }} flex items-center w-full px-2 py-1.5 rounded-sm">
                        <svg class="{{ request()->routeIs($role . '.katalog') ? 'text-blue-600' : 'text-black' }} shrink-0 w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
                        </svg>
                        <span class="ml-3 {{ request()->routeIs($role . '.katalog') ? 'text-blue-600' : 'text-black' }}">Produk</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>