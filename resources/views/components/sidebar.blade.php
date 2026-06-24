@php
    $role = auth()->user()->role === 'admin' ? 'admin' : 'user';
    $menuBaseClass = 'flex min-w-[4.75rem] flex-col items-center justify-center rounded-2xl px-2 py-2 text-center transition duration-300 hover:scale-105 lg:min-w-0 lg:w-full lg:flex-row lg:justify-start lg:rounded-sm lg:py-1.5 lg:text-left';
    $activeMenuClass = 'bg-white border-t-4 border-blue-600 hover:bg-blue-200 lg:border-t-0 lg:border-l-4';
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
        ['label' => 'Artikel', 'route' => $role . '.artikel', 'active' => request()->routeIs($role . '.artikel*'), 'available' => true, 'icon' => '
            <svg class="ICON_CLASS w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M7 4v3m10-3v3M6 11h12M6 15h8m-8 5h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z"/>
            </svg>
        '],
    ];

    if ($role === 'admin') {
        $menus[] = ['label' => 'Laporan', 'route' => null, 'active' => request()->routeIs('admin.laporan*'), 'available' => true, 'children' => [
            ['label' => 'Laporan Penjualan', 'route' => 'admin.laporan.penjualan', 'active' => request()->routeIs('admin.laporan.penjualan')],
            ['label' => 'Laporan Keuangan', 'route' => 'admin.laporan.keuangan', 'active' => request()->routeIs('admin.laporan.keuangan')],
        ], 'icon' => '
            <svg class="ICON_CLASS w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m3 6V7m3 10v-3m-9 7h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z"/>
            </svg>
        '];
    }
@endphp

<div id="sidebar" class="fixed bottom-0 left-0 right-0 z-40 h-20 w-full overflow-visible border-t border-slate-200 bg-white/95 shadow-[0_-18px_50px_rgba(15,23,42,0.14)] backdrop-blur-xl lg:top-0 lg:right-auto lg:h-screen lg:w-64 lg:overflow-hidden lg:border-t-0 lg:bg-transparent lg:shadow-2xl lg:backdrop-blur-none">
    <div class="absolute inset-0 hidden lg:block">
        <img src="/images/sidebar-opt.jpg" alt="" class="w-full h-full" width="320" height="934" fetchpriority="high" decoding="async">
    </div>

    <div class="relative z-10 h-full">
        <div class="hidden border-b border-white lg:flex lg:flex-col">
            <img src="/images/Logo-opt.png" alt="Logo" class="-mt-5 -mb-5 w-40 h-40 object-cover rounded-full mx-auto" width="160" height="160" decoding="async">
        </div>

        <div class="h-full overflow-x-auto px-2 py-2 lg:h-auto lg:overflow-hidden lg:py-5 lg:pl-5 lg:pr-4">
            <ul class="flex h-full items-center gap-2 text-xs font-medium lg:block lg:h-auto lg:space-y-2 lg:text-base">
                @foreach ($menus as $menu)
                    @php
                        $itemClass = $menuBaseClass . ' ' . ($menu['active'] ? $activeMenuClass : ($menu['available'] ? $inactiveMenuClass : $disabledMenuClass));
                        $textClass = $menu['active'] ? 'text-blue-600' : 'text-black';
                        $icon = str_replace('ICON_CLASS', $textClass, $menu['icon']);
                    @endphp

                    <li>
                        @if ($menu['available'] && !empty($menu['children']))
                            <details class="group" {{ $menu['active'] ? 'open' : '' }}>
                                <summary class="{{ $itemClass }} list-none hover:cursor-pointer">
                                    {!! $icon !!}
                                    <span class="mt-1 leading-tight {{ $textClass }} lg:ml-3 lg:mt-0">{{ $menu['label'] }}</span>
                                    <svg class="mt-1 h-3 w-3 {{ $textClass }} transition duration-300 group-open:rotate-180 lg:ml-auto lg:mt-0 lg:h-4 lg:w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                                    </svg>
                                </summary>
                                <ul class="absolute bottom-[4.75rem] right-2 z-50 w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl lg:static lg:mt-2 lg:w-auto lg:space-y-1 lg:rounded-none lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none">
                                    @foreach ($menu['children'] as $child)
                                        @php
                                            $childIconClass = $child['active'] ? 'text-blue-600' : 'text-black';
                                            $childClass = $child['active']
                                                ? $activeMenuClass . ' text-blue-600 shadow-sm'
                                                : $inactiveMenuClass . ' text-black';
                                        @endphp
                                        <li>
                                            <a href="{{ route($child['route']) }}" class="{{ $menuBaseClass }} {{ $childClass }}">
                                                <span class="flex h-5 w-5 shrink-0 items-center justify-center {{ $childIconClass }}">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                                </span>
                                                <span class="ml-2 {{ $childIconClass }} lg:ml-3">{{ $child['label'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        @elseif ($menu['available'])
                            <a href="{{ route($menu['route']) }}" class="{{ $itemClass }}">{!! $icon !!}<span class="mt-1 leading-tight {{ $textClass }} lg:ml-3 lg:mt-0">{{ $menu['label'] }}</span></a>
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
                    <button type="button" class="logoutTriggerBtn flex min-w-[4.75rem] flex-col items-center justify-center rounded-2xl bg-transparent px-2 py-2 text-center text-xs transition duration-300 hover:scale-105 hover:bg-red-50 lg:w-full lg:flex-row lg:justify-start lg:rounded-sm lg:py-1.5 lg:text-left lg:text-base"><svg class="h-5 w-5 text-red-500 transition duration-75" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-3-3m3 3-3 3m4-9h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-1"/></svg><span class="mt-1 leading-tight text-red-500 lg:ml-3 lg:mt-0">Log Out</span></button>
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
