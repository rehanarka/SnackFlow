<div
    class="productCard bg-white overflow-hidden rounded-2xl border border-gray-200 p-4 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl hover:cursor-pointer"
    data-search="{{ \Illuminate\Support\Str::lower(trim($produk->nama_produk . ' ' . ($produk->kategori ?? ''))) }}"
    data-stok="{{ $produk->stok }}"
    data-nama="{{ $produk->nama_produk }}"
    data-harga="{{ number_format($produk->harga, 0, ',', '.') }}"
    data-deskripsi="{{ $produk->deskripsi ?: 'Belum ada deskripsi produk.' }}"
    data-berat="{{ number_format($produk->berat ?? 0, 0, ',', '.') }}"
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
    <div class="mt-2 flex items-center justify-between">
        <p class="text-xs text-gray-500">Stok : {{ $produk->stok }}</p>
        <span class="rounded-full {{ $produk->stok > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }} px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em]">{{ $produk->stok > 0 ? 'Siap Dipesan' : 'Stok Habis' }}</span>
    </div>
    

    <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-3">
        <div class="flex items-center justify-between gap-3">
            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Jumlah</span>

            <div class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-2 py-1 shadow-sm">
                <button type="button" class="quantityMinusBtn flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-lg font-semibold text-slate-700 transition duration-300 hover:bg-slate-200 hover:cursor-pointer disabled:cursor-not-allowed disabled:opacity-50" {{ $produk->stok < 1 ? 'disabled' : '' }}>-</button>
                <span class="quantityValue min-w-6 text-center text-sm font-semibold text-slate-800">{{ $produk->stok > 0 ? 1 : 0 }}</span>
                <button type="button" class="quantityPlusBtn flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-lg font-semibold text-blue-700 transition duration-300 hover:bg-blue-200 hover:cursor-pointer disabled:cursor-not-allowed disabled:opacity-50" {{ $produk->stok < 1 ? 'disabled' : '' }}>+</button>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3">
            <form action="{{ route('user.keranjang.tambah') }}" method="POST">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                <input type="hidden" name="jumlah_produk" value="{{ $produk->stok > 0 ? 1 : 0 }}" class="quantityInput">
                <button type="submit" class="w-full rounded-2xl border border-slate-300 bg-white px-2 py-1 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-100 hover:cursor-pointer disabled:translate-y-0 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400" {{ $produk->stok < 1 ? 'disabled' : '' }}>Add to Cart</button>
            </form>
            <form action="{{ route('user.keranjang.tambah') }}" method="POST">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                <input type="hidden" name="jumlah_produk" value="{{ $produk->stok > 0 ? 1 : 0 }}" class="quantityInput">
                <input type="hidden" name="redirect_to_checkout" value="1">
                <button type="submit" class="w-full rounded-2xl bg-blue-900 px-3 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:cursor-pointer disabled:translate-y-0 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none" {{ $produk->stok < 1 ? 'disabled' : '' }}>Checkout</button>
            </form>
        </div>
        <a href="{{ route('user.katalog.review', $produk) }}" class="mt-3 inline-flex w-full items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-700 transition duration-300 hover:-translate-y-0.5 hover:bg-amber-100 hover:cursor-pointer">
            Lihat Review
        </a>
    </div>
</div>
