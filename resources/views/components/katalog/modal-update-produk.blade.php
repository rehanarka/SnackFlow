<div id="updateProductModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div id="updateModalOverlay" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 ease-out"></div>

    <div id="updateProductModalContent" class="relative w-full max-w-lg bg-white rounded-xl shadow-lg opacity-0 scale-95 transition-all duration-300 ease-out">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Update Produk</h2>
            <button type="button" id="closeUpdateProductModal" class="text-gray-500 hover:text-black text-xl hover:cursor-pointer">&times;</button>
        </div>

        <form id="updateProductForm" action="" method="POST" enctype="multipart/form-data" autocomplete="off" class="px-6 py-5 space-y-4">
            @csrf
            @method('PUT')
            <x-katalog.form-produk-fields prefix="update" />

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancelUpdateProductModal" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition hover:scale-105 hover:cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition hover:scale-105 duration-300 hover:cursor-pointer">Update</button>
            </div>
        </form>
    </div>
</div>
