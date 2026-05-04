@php
    $role = auth()->user()->role === 'admin' ? 'admin' : 'user';
    $menuBaseClass = 'flex items-center w-full px-2 py-1.5 rounded-sm transition duration-300 hover:scale-105';
    $activeMenuClass = 'bg-white border-l-4 border-blue-600 hover:bg-blue-200';
    $inactiveMenuClass = 'bg-transparent hover:bg-white';
    $disabledMenuClass = 'bg-transparent hover:bg-white hover:cursor-pointer';

    $menus = [
        ['label' => 'Produk', 'route' => $role . '.katalog', 'active' => request()->routeIs($role . '.katalog'), 'available' => true, 'icon' => '
            <svg class="ICON_CLASS shrink-0 w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
            </svg>
        '],
        ['label' => 'Transaksi', 'route' => $role . '.transaksi', 'active' => request()->routeIs($role . '.transaksi'), 'available' => true, 'icon' => '
            <svg class="ICON_CLASS w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V6a4 4 0 1 1 8 0v1m-9 4h10m-11 9h12a1 1 0 0 0 1-1l-1-10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1L5 19a1 1 0 0 0 1 1Z"/>
            </svg>
        '],
        ['label' => 'Riwayat Transaksi', 'route' => null, 'active' => request()->is('*/riwayat-transaksi'), 'available' => false, 'icon' => '
            <svg class="ICON_CLASS w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-3.4-7"/>
            </svg>
        '],
    ];

    if ($role === 'admin') {
        $menus[] = ['label' => 'Konten', 'route' => null, 'active' => request()->is('admin/konten'), 'available' => false, 'icon' => '
            <svg class="ICON_CLASS w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M7 4v3m10-3v3M6 11h12M6 15h8m-8 5h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z"/>
            </svg>
        '];
        $menus[] = ['label' => 'Laporan', 'route' => null, 'active' => request()->is('admin/laporan'), 'available' => false, 'icon' => '
            <svg class="ICON_CLASS w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m3 6V7m3 10v-3m-9 7h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z"/>
            </svg>
        '];
    }
@endphp

<div id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen overflow-hidden shadow-2xl">
    <div class="absolute inset-0">
        <img src="/images/sidebar-opt.jpg" alt="" class="w-full h-full" width="320" height="934" fetchpriority="high" decoding="async">
    </div>

    <div class="relative z-10 h-full">
        <div class="border-b border-white flex flex-col">
            <img src="/images/Logo-opt.png" alt="Logo" class="-mt-5 -mb-5 w-40 h-40 object-cover rounded-full mx-auto" width="160" height="160" decoding="async">
        </div>

        <div class="py-5 overflow-hidden pl-5 pr-4">
            <ul class="space-y-2 font-medium">
                @foreach ($menus as $menu)
                    @php
                        $itemClass = $menuBaseClass . ' ' . ($menu['active'] ? $activeMenuClass : ($menu['available'] ? $inactiveMenuClass : $disabledMenuClass));
                        $textClass = $menu['active'] ? 'text-blue-600' : 'text-black';
                        $icon = str_replace('ICON_CLASS', $textClass, $menu['icon']);
                    @endphp

                    <li>
                        @if ($menu['available'])
                            <a href="{{ route($menu['route']) }}" class="{{ $itemClass }}">{!! $icon !!}<span class="ml-3 {{ $textClass }}">{{ $menu['label'] }}</span></a>
                        @else
                            <div class="{{ $itemClass }}">
                                {!! $icon !!}
                                <div class="ml-3 flex flex-1 items-center justify-between gap-2">
                                    <span class="{{ $textClass }}">{{ $menu['label'] }}</span>
                                    <span class="rounded-full bg-white/80 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">Soon</span>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach

                <li class="pt-3">
                    <button type="button" class="logoutTriggerBtn flex w-full items-center rounded-sm bg-transparent px-2 py-1.5 text-left transition duration-300 hover:scale-105 hover:bg-red-50"><svg class="h-5 w-5 text-red-500 transition duration-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-3-3m3 3-3 3m4-9h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-1"/></svg><span class="ml-3 text-red-500">Log Out</span></button>
                </li>
            </ul>
        </div>
    </div>
</div>

<x-modal.confirm
    modal-id="logoutModal"
    overlay-id="logoutModalOverlay"
    panel-id="logoutModalContent"
    title="Konfirmasi Logout"
    message="Apakah Anda yakin ingin keluar?"
    close-button-id="closeLogoutModal"
    cancel-button-id="cancelLogoutModal"
    cancel-label="Batal"
    submit-label="Logout"
    form-action="{{ route('logout') }}"
    submit-class="bg-red-500 text-white shadow-lg shadow-red-200 hover:bg-red-600"
/>
