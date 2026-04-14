@props([
    'showTambahButton' => true,
    'showCartButton' => false,
    'cartCount' => 0,
])

<div class="flex items-start justify-between gap-4">
    <div class="flex-1">
        @if ($errors->first())
            <p class="mb-2 text-red-500">{{ $errors->first() }}</p>
        @endif

        <div class="relative max-w-2xl">
            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>
            </span>
            <input id="cariProdukInput" type="text" placeholder="Cari nama atau kategori produk..." class="w-full rounded-2xl border border-slate-200 bg-white/90 py-3 pl-12 pr-4 text-slate-700 shadow-sm outline-none transition duration-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-200">
        </div>
    </div>

    @if ($showTambahButton)
        <button type="button" id="addProductBtn" class="h-12 rounded-2xl bg-blue-600 px-5 text-white shadow-lg shadow-blue-200 transition duration-300 hover:-translate-y-0.5 hover:cursor-pointer hover:bg-blue-700">Tambah Produk</button>
    @elseif ($showCartButton)
        <button type="button" class="relative flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-lg shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:cursor-pointer" aria-label="Keranjang">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
            </svg>
            <span class="absolute -right-1.5 -top-1.5 flex h-6 min-w-6 items-center justify-center rounded-full bg-red-500 px-1 text-xs font-semibold text-white shadow-md">{{ $cartCount }}</span>
        </button>
    @endif
</div>
