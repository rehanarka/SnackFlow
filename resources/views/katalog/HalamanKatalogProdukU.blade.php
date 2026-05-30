@extends('layouts.sidebar')

@section('content')
@include('katalog.partials.toolbar-pencarian-produk', [
    'showTambahButton' => false,
    'showCartButton' => true,
    'cartCount' => $cartCount,
])

<div class="mt-10">
    @if (session('keranjang_warning'))
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-medium text-amber-700">
            {{ session('keranjang_warning') }}
        </div>
    @endif

    @if ($produks->count())
        <div id="produkGrid" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($produks as $produk)
                @include('katalog.partials.product-card-user', ['produk' => $produk])
            @endforeach
        </div>

        <div id="hasilPencarianKosong" class="mt-8 hidden rounded-2xl border border-dashed border-slate-300 bg-white py-14 text-center">
            <h2 class="text-lg font-semibold text-slate-700">Produk tidak ditemukan</h2>
            <p class="mt-2 text-sm text-slate-500">Coba gunakan kata kunci lain untuk nama atau kategori produk.</p>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center">
            <h2 class="text-lg font-semibold text-gray-700">Belum ada produk</h2>
            <p class="mt-2 text-sm text-gray-500">Produk akan muncul di sini setelah katalog tersedia.</p>
        </div>
    @endif
</div>

@include('katalog.partials.halaman-keranjang', ['keranjangItems' => $keranjangItems, 'cartCount' => $cartCount])

@if (session('checkout_success'))
    @php($checkoutSuccess = session('checkout_success'))
    <div id="checkoutSuccessModal" class="fixed inset-0 z-[75] flex items-center justify-center px-4">
        <div id="checkoutSuccessOverlay" class="absolute inset-0 bg-slate-950/45 opacity-100"></div>
        <div class="relative w-full max-w-lg rounded-[2rem] bg-white px-6 py-6 shadow-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">Pesanan Berhasil</p>
            <h2 class="mt-3 text-2xl font-bold text-slate-900">Pesanan kamu sudah masuk</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                {{ $checkoutSuccess['message'] ?? 'Pesanan berhasil dibuat dan sekarang menunggu konfirmasi dari admin.' }}
            </p>
            @if (!empty($checkoutSuccess['transaction_id']))
                <p class="mt-3 text-sm font-semibold text-slate-900">Nomor Pesanan #{{ $checkoutSuccess['transaction_id'] }}</p>
            @endif
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('user.transaksi') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:bg-slate-50 hover:cursor-pointer">
                    Lihat Riwayat Pesanan
                </a>
                <button type="button" id="closeCheckoutSuccessModal" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-slate-800 hover:cursor-pointer">
                    Oke
                </button>
            </div>
        </div>
    </div>
@endif

<div id="productDetailModal" class="fixed inset-0 z-[65] hidden">
    <div id="productDetailOverlay" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300"></div>

    <div id="productDetailPanel" class="absolute inset-x-4 top-1/2 mx-auto w-[min(100%,56rem)] -translate-y-[46%] scale-95 overflow-hidden rounded-[2rem] bg-white opacity-0 shadow-2xl transition duration-300 sm:inset-x-0">
        <div class="grid gap-0 md:grid-cols-[0.95fr_1.05fr]">
            <div class="bg-slate-100">
                <img id="productDetailImage" src="" alt="Detail Produk" class="h-full min-h-72 w-full object-cover">
            </div>

            <div class="flex flex-col px-6 py-6 sm:px-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Detail Produk</p>
                        <h2 id="productDetailName" class="mt-2 text-2xl font-bold text-slate-900"></h2>
                    </div>
                    <button type="button" id="closeProductDetailModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:cursor-pointer hover:bg-slate-200">&times;</button>
                </div>

                <p id="productDetailPrice" class="mt-4 text-3xl font-bold text-sky-600"></p>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Stok</p>
                        <p id="productDetailStock" class="mt-1 text-sm font-semibold text-slate-900"></p>
                    </div>
                    <div class="rounded-2xl bg-sky-50 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-sky-600">Berat</p>
                        <p id="productDetailWeight" class="mt-1 text-sm font-semibold text-sky-900"></p>
                    </div>
                </div>

                <div class="mt-6 rounded-[1.5rem] border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Deskripsi</p>
                    <p id="productDetailDescription" class="mt-3 text-sm leading-7 text-slate-600"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const checkoutSuccessModal = document.getElementById('checkoutSuccessModal');
        const closeCheckoutSuccessModal = document.getElementById('closeCheckoutSuccessModal');

        if (checkoutSuccessModal && closeCheckoutSuccessModal) {
            closeCheckoutSuccessModal.addEventListener('click', () => {
                checkoutSuccessModal.classList.add('hidden');
            });
        }
    })();

    (() => {
        const productCards = document.querySelectorAll('.productCard');
        const productDetailModal = document.getElementById('productDetailModal');
        const productDetailOverlay = document.getElementById('productDetailOverlay');
        const productDetailPanel = document.getElementById('productDetailPanel');
        const closeProductDetailModal = document.getElementById('closeProductDetailModal');
        const productDetailImage = document.getElementById('productDetailImage');
        const productDetailName = document.getElementById('productDetailName');
        const productDetailPrice = document.getElementById('productDetailPrice');
        const productDetailStock = document.getElementById('productDetailStock');
        const productDetailWeight = document.getElementById('productDetailWeight');
        const productDetailDescription = document.getElementById('productDetailDescription');

        if (
            !productCards.length ||
            !productDetailModal ||
            !productDetailOverlay ||
            !productDetailPanel ||
            !closeProductDetailModal ||
            !productDetailImage ||
            !productDetailName ||
            !productDetailPrice ||
            !productDetailStock ||
            !productDetailWeight ||
            !productDetailDescription
        ) {
            return;
        }

        const fallbackImage =
            'data:image/svg+xml;charset=UTF-8,' +
            encodeURIComponent(`
                <svg xmlns="http://www.w3.org/2000/svg" width="800" height="600">
                    <rect width="100%" height="100%" fill="#e2e8f0"/>
                    <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#64748b" font-family="Arial" font-size="28">
                        No Image
                    </text>
                </svg>
            `);

        const openProductDetailModal = (card) => {
            productDetailImage.src = card.dataset.foto || fallbackImage;
            productDetailName.textContent = card.dataset.nama || 'Produk';
            productDetailPrice.textContent = `Rp ${card.dataset.harga || '0'}`;
            productDetailStock.textContent = `${card.dataset.stok || '0'} item`;
            productDetailWeight.textContent = `${card.dataset.berat || '0'} gram`;
            productDetailDescription.textContent = card.dataset.deskripsi || 'Belum ada deskripsi produk.';

            productDetailModal.classList.remove('hidden');

            requestAnimationFrame(() => {
                productDetailOverlay.classList.remove('opacity-0');
                productDetailOverlay.classList.add('opacity-100');
                productDetailPanel.classList.remove('opacity-0', 'scale-95');
                productDetailPanel.classList.add('opacity-100', 'scale-100');
            });
        };

        const closeProductModal = () => {
            productDetailOverlay.classList.remove('opacity-100');
            productDetailOverlay.classList.add('opacity-0');
            productDetailPanel.classList.remove('opacity-100', 'scale-100');
            productDetailPanel.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                productDetailModal.classList.add('hidden');
            }, 300);
        };

        productCards.forEach((card) => {
            card.addEventListener('click', (event) => {
                if (event.target.closest('button, form, a, input')) {
                    return;
                }

                openProductDetailModal(card);
            });
        });

        closeProductDetailModal.addEventListener('click', closeProductModal);
        productDetailModal.addEventListener('click', (event) => {
            if (event.target === productDetailModal || event.target === productDetailOverlay) {
                closeProductModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !productDetailModal.classList.contains('hidden')) {
                closeProductModal();
            }
        });
    })();
</script>
@endsection
