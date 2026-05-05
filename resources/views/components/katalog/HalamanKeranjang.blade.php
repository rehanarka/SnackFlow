@props(['keranjangItems', 'cartCount'])

@php
    $totalHarga = $keranjangItems->sum(fn ($item) => ($item->produk->harga ?? 0) * $item->jumlah_produk);
@endphp

<div id="cartModal" class="fixed inset-0 z-[60] hidden">
    <div id="cartModalOverlay" class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-300 ease-out"></div>

    <div id="cartModalPanel" class="absolute right-0 top-0 h-full w-full max-w-md translate-x-full bg-white shadow-2xl transition-transform duration-300 ease-out">
        <div class="flex h-full flex-col">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Keranjang</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $cartCount }} item di keranjang anda</p>
                </div>
                <button type="button" id="closeCartModal" class="text-xl text-slate-400 transition duration-300 hover:text-slate-800 hover:cursor-pointer">&times;</button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                @if (session('success'))
                    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @error('keranjang')
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ $message }}
                    </div>
                @enderror

                @if ($keranjangItems->count())
                    <div class="space-y-4">
                        @foreach ($keranjangItems as $item)
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex gap-4">
                                    @if ($item->produk?->foto_produk)
                                        <img src="{{ asset('storage/' . $item->produk->foto_produk) }}" alt="{{ $item->produk->nama_produk }}" class="h-20 w-20 rounded-2xl object-cover">
                                    @else
                                        <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-200 text-xs font-semibold text-slate-500">No Image</div>
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-900">{{ $item->produk->nama_produk }}</p>
                                        <form action="{{ route('user.keranjang.update', $item->id) }}" method="POST" class="mt-3 flex items-center gap-3">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="jumlah_produk" value="{{ max(1, $item->jumlah_produk - 1) }}" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-base font-semibold text-slate-700 transition duration-300 hover:bg-slate-300 hover:cursor-pointer {{ $item->jumlah_produk <= 1 ? 'pointer-events-none opacity-50' : '' }}" {{ $item->jumlah_produk <= 1 ? 'disabled' : '' }}>-</button>
                                            <span class="min-w-6 text-center text-sm font-semibold text-slate-700">{{ $item->jumlah_produk }}</span>
                                            <button type="submit" name="jumlah_produk" value="{{ $item->jumlah_produk + 1 }}" class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-100 text-base font-semibold text-sky-700 transition duration-300 hover:bg-sky-200 hover:cursor-pointer {{ $item->jumlah_produk >= ($item->produk->stok ?? 0) ? 'pointer-events-none opacity-50' : '' }}" {{ $item->jumlah_produk >= ($item->produk->stok ?? 0) ? 'disabled' : '' }}>+</button>
                                        </form>
                                        <p class="mt-2 text-sm font-semibold text-sky-600">Rp {{ number_format(($item->produk->harga ?? 0) * $item->jumlah_produk, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('user.keranjang.hapus', $item->id) }}" method="POST" class="mt-4 flex justify-end">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-2xl bg-red-50 px-3 py-2 text-xs font-semibold text-red-500 transition duration-300 hover:bg-red-100 hover:cursor-pointer">Hapus</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center">
                        <h3 class="text-lg font-semibold text-slate-700">Keranjang masih kosong</h3>
                        <p class="mt-2 text-sm text-slate-500">Tambahkan produk dari katalog untuk mulai berbelanja.</p>
                    </div>
                @endif
            </div>

            <div class="border-t border-slate-200 px-6 py-5">
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-500">Total</span>
                    <span class="text-lg font-bold text-slate-900">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('user.checkout') }}" class="block w-full rounded-2xl bg-slate-900 px-4 py-3 text-center text-sm font-semibold text-white shadow-lg shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 {{ $keranjangItems->count() ? '' : 'pointer-events-none opacity-50' }}">Checkout</a>
            </div>
        </div>
    </div>
</div>
