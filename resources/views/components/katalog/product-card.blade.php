@props(['produk'])

<div class="productCard bg-white overflow-hidden rounded-2xl border border-gray-200 p-4 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl" data-search="{{ \Illuminate\Support\Str::lower(trim($produk->nama_produk . ' ' . ($produk->kategori ?? ''))) }}">
    @if ($produk->foto_produk)
        <img src="{{ asset('storage/' . $produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" class="h-48 w-full object-cover rounded-t-2xl">
    @else
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
            <span class="text-gray-500">No Image</span>
        </div>
    @endif

    <p class="text-lg text-gray-800 font-bold mt-2">{{ $produk->nama_produk }}</p>
    <p class="text-2xl font-bold text-blue-400 mt-2">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
    <p class="text-xs text-gray-500">Stok : {{ $produk->stok }}</p>

    <div class="flex gap-2 mt-2">
        <button type="button" class="updateProductBtn flex-1 px-3 py-1 bg-green-300 text-white rounded-lg hover:bg-green-600 transition duration-300 hover:scale-105 hover:cursor-pointer border-2 border-green-400" data-id="{{ $produk->id }}" data-nama="{{ $produk->nama_produk }}" data-harga="{{ $produk->harga }}" data-stok="{{ $produk->stok }}" data-berat="{{ $produk->berat }}" data-deskripsi="{{ $produk->deskripsi }}">Edit</button>
        <button type="button" class="hapusProductBtn flex-1 px-3 py-1 bg-red-300 text-white rounded-lg hover:bg-red-600 transition duration-300 hover:scale-105 hover:cursor-pointer border-2 border-red-400" data-id="{{ $produk->id }}" data-nama="{{ $produk->nama_produk }}">Hapus</button>
    </div>
</div>
