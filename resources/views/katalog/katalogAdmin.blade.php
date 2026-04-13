@extends('layouts.sidebar')

@section('content')
    <div class="flex justify-between">
        <div>
            <h1 class="text-4xl font-bold">Produk</h1>
            <p class="text-gray-500">Kelola produk olahan anda</p>
            @if ($errors->first())
                <p class="text-red-500">{{ $errors->first() }}</p>
            @endif
        </div>
        <button type="button" id="addProduct" class="px-4 h-10 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 hover:scale-105 hover:cursor-pointer">Tambah Produk</button>

<div id="addProductModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4"> 
    <div id="modalOverlay" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 ease-out"></div>
    <div id="addProductModalContent" class="relative w-full max-w-lg bg-white rounded-xl shadow-lg opacity-0 scale-95 transition-all duration-300 ease-out">

        <div class="flex items-center justify-between border-b px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800">Tambah Produk</h2>
            <button type="button" id="closeAddProductModal" class="text-gray-500 hover:text-black text-xl hover:cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.katalog.tambah') }}" method="POST" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf
            <div>
                <label for="nama_produk" class="block mb-1 text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama produk"required>
            </div>

            <div>
                <label for="kategori" class="block mb-1 text-sm font-medium text-gray-700">Kategori</label>
                <input type="text" id="kategori" name="kategori" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan kategori">
            </div>

            <div>
                <label for="harga" class="block mb-1 text-sm font-medium text-gray-700">Harga</label>
                <input type="number" id="harga" name="harga" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan harga" required>
            </div>

            <div>
                <label for="stok" class="block mb-1 text-sm font-medium text-gray-700">Stok</label>
                <input type="number" id="stok" name="stok" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan stok" required>
            </div>

            <div>
                <label for="foto" class="block mb-1 text-sm font-medium text-gray-700">Foto Produk</label>
                <input type="file" id="foto"name="foto" accept=".jpg, .jpeg, .png, .webp, gif" class="w-full rounded-lg border border-gray-300 px-3 py-2 file:mr-3 file:rounded file:border-0 file:bg-blue-100 file:px-3 file:py-1 file:text-blue-700">
            </div>

            <div>
                <label for="deskripsi" class="block mb-1 text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan deskripsi produk"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancelAddProductModal" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition hover:scale-105 hover:cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition hover:scale-105 duration-300 hover:cursor-pointer">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const addProductModal = document.getElementById('addProductModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const addProductModalContent = document.getElementById('addProductModalContent');
        const addProduct = document.getElementById('addProduct');
        const closeAddProductModal = document.getElementById('closeAddProductModal');
        const cancelAddProductModal = document.getElementById('cancelAddProductModal');

        function openModal() {
            addProductModal.classList.remove('hidden');
            addProductModal.classList.add('flex');
            
            requestAnimationFrame(() => {
                modalOverlay.classList.remove('opacity-0');
                modalOverlay.classList.add('opacity-100');
                
                addProductModalContent.classList.remove('opacity-0', 'scale-95');
                addProductModalContent.classList.add('opacity-100', 'scale-100');
            });
        }
        
        function closeModal() {
            modalOverlay.classList.remove('opacity-100');
            modalOverlay.classList.add('opacity-0');
            
            addProductModalContent.classList.remove('opacity-100', 'scale-100');
            addProductModalContent.classList.add('opacity-0', 'scale-95');
            
            setTimeout(() => {
                addProductModal.classList.add('hidden');
                addProductModal.classList.remove('flex');
            }, 300);
        }
        
        addProduct.addEventListener('click', openModal);
        closeAddProductModal.addEventListener('click', closeModal);
        cancelAddProductModal.addEventListener('click', closeModal);
        
        addProductModal.addEventListener('click', (e) => {
            if (e.target === addProductModal || e.target === modalOverlay) {
                closeModal();
            }
        });
        </script>
</div>
<div class="mt-10">
    @if ($produks->count())
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($produks as $produk)
                <div class="bg-white overflow-hidden rounded-2xl border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 p-4">
                    @if ($produk->foto)
                        <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" class="h-48 w-full object-cover rounded-t-2xl">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif
                    <p class="text-lg text-gray-800 font-bold mt-2">{{ $produk->nama_produk }}</p>
                    <div class="bg-blue-200 rounded-lg px-1 py-1 mt-1 w-fit">
                        <p class="text-xs font-semibold">{{ $produk->kategori }}</p>
                    </div>
                    <p class="text-2xl font-bold text-blue-400 mt-2">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Stok : {{ $produk->stok }}</p>
                    <div class="flex gap-2 mt-2">
                        <button class="flex-1 px-3 py-1 bg-green-300 text-white rounded-lg hover:bg-green-600 transition duration-300 hover:scale-105 hover:cursor-pointer border-2 border-green-400">Edit</button>
                        <button class="flex-1 px-3 py-1 bg-red-300 text-white rounded-lg hover:bg-red-600 transition duration-300 hover:scale-105 hover:cursor-pointer border-2 border-red-400">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection