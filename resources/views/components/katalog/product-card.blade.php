@props(['produk'])

<button
    type="button"
    class="productCard w-full overflow-hidden rounded-2xl border border-gray-200 bg-white p-4 text-left shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:cursor-pointer"
    data-search="{{ \Illuminate\Support\Str::lower(trim($produk->nama_produk . ' ' . ($produk->kategori ?? ''))) }}"
    data-id="{{ $produk->id }}"
    data-nama="{{ $produk->nama_produk }}"
    data-harga="{{ $produk->harga }}"
    data-stok="{{ $produk->stok }}"
    data-berat="{{ $produk->berat }}"
    data-deskripsi="{{ $produk->deskripsi }}"
    data-foto="{{ $produk->foto_produk ? asset('storage/' . $produk->foto_produk) : '' }}"
>
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
</button>
