@props(['pesan' => null])

<div id="peringatanProdukModal" data-popup-peringatan="{{ $pesan ? 'true' : 'false' }}" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div id="peringatanProdukOverlay" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 ease-out"></div>

    <div id="peringatanProdukModalContent" class="relative w-full max-w-md bg-white rounded-xl shadow-lg opacity-0 scale-95 transition-all duration-300 ease-out">
        <div class="border-b border-red-100 px-6 py-4">
            <h2 class="text-lg font-semibold text-red-600">Peringatan</h2>
        </div>

        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">{{ $pesan }}</p>

            <div class="mt-6 flex justify-end">
                <button type="button" id="closePeringatanProdukModal" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition hover:scale-105 duration-300 hover:cursor-pointer">OK</button>
            </div>
        </div>
    </div>
</div>
