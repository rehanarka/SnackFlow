<div id="hapusProductModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div id="hapusModalOverlay" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 ease-out"></div>

    <div id="hapusProductModalContent" class="relative w-full max-w-md bg-white rounded-xl shadow-lg opacity-0 scale-95 transition-all duration-300 ease-out">
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h2>
        </div>

        <div class="px-6 py-5">
            <p id="hapusProductMessage" class="text-sm text-gray-600">Apakah kamu yakin ingin menghapus produk ini?</p>

            <form id="hapusProductForm" action="" method="POST" class="mt-6 flex justify-end gap-2">
                @csrf
                @method('DELETE')
                <button type="button" id="cancelHapusProductModal" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition hover:scale-105 hover:cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition hover:scale-105 duration-300 hover:cursor-pointer">Hapus</button>
            </form>
        </div>
    </div>
</div>
