@extends('layouts.normal')

@section('content')
@php
    $user = auth()->user();
    $oldTujuanPengiriman = old('tujuan_pengiriman', $selectedDestination['label'] ?? '');
    $namaPenerima = old('nama_penerima', $checkoutForm['nama_penerima'] ?? $user->name);
    $noTelpPenerima = old('no_telp_penerima', $checkoutForm['no_telp_penerima'] ?? $user->no_telp);
    $alamatPenerima = old('alamat_penerima', $checkoutForm['alamat_penerima'] ?? '');
@endphp

<div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.10),_transparent_32%),linear-gradient(180deg,_#f8fbff_0%,_#eef4fb_100%)] px-4 py-8">
    <div class="mx-auto max-w-6xl">
        @if (session('success'))
            <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @error('checkout')
            <div id="checkoutErrorAlert" class="mb-6 rounded-3xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $message }}
            </div>
        @enderror
        <div id="checkoutAjaxAlert" class="mb-6 hidden rounded-3xl px-5 py-4 text-sm font-medium"></div>

        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Checkout</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Ringkasan Pesanan</h1>
                <p class="mt-2 text-sm text-slate-500">Cek ulang item, data penerima, dan total belanja sebelum lanjut ke pengiriman.</p>
            </div>
            <a href="{{ route('user.katalog') }}" class="rounded-2xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:bg-slate-50">Kembali ke Katalog</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <section class="space-y-6">
                <div class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/90 shadow-xl shadow-sky-100/40 backdrop-blur">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Item Checkout</h2>
                    </div>

                    <div class="px-6 py-5">
                        @if ($keranjangItems->count())
                            <div class="space-y-4">
                                @foreach ($keranjangItems as $item)
                                    <div class="flex gap-4 rounded-[1.75rem] border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm shadow-slate-100/70">
                                        @if ($item->produk?->foto)
                                            <img src="{{ asset('storage/' . $item->produk->foto) }}" alt="{{ $item->produk->nama_produk }}" class="h-24 w-24 rounded-2xl object-cover">
                                        @else
                                            <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-slate-200 text-xs font-semibold text-slate-500">No Image</div>
                                        @endif

                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-base font-semibold text-slate-900">{{ $item->produk->nama_produk }}</p>
                                            <p class="mt-1 text-sm text-slate-500">Jumlah: {{ $item->jumlah_produk }}</p>
                                            <p class="mt-1 text-sm text-slate-500">Berat: {{ number_format(($item->produk->berat ?? 0) * $item->jumlah_produk, 0, ',', '.') }} gram</p>
                                            <p class="mt-3 text-sm font-semibold text-sky-600">Rp {{ number_format(($item->produk->harga ?? 0) * $item->jumlah_produk, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-16 text-center">
                                <h3 class="text-lg font-semibold text-slate-700">Belum ada item untuk checkout</h3>
                                <p class="mt-2 text-sm text-slate-500">Tambahkan produk ke keranjang terlebih dahulu.</p>
                            </div>
                        @endif
                    </div>
                </div>
                 <div class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-sky-100/40 backdrop-blur">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Ringkasan Belanja</h2>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <span>Total Item</span>
                            <span>{{ $keranjangItems->sum('jumlah_produk') }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <span>Total Berat</span>
                            <span>{{ number_format($totalBerat, 0, ',', '.') }} gram</span>
                        </div>

                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <span>Ongkir</span>
                            <span id="shippingCostSummary">
                                {{ !empty($selectedShipping['ongkir'])
                                    ? 'Rp ' . number_format($selectedShipping['ongkir'], 0, ',', '.')
                                    : 'Belum dipilih' }}
                            </span>
                        </div>

                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-700">Total Sementara</span>
                                <span id="estimatedTotalSummary" class="text-xl font-bold text-slate-900">Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                                <div id="shippingOptionsSection" class="{{ !empty($rajaongkirRates) ? '' : 'hidden ' }}overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-sky-100/40 backdrop-blur">
                        <div class="border-b border-slate-200 px-6 py-5">
                            <h2 class="text-lg font-semibold text-slate-900">Opsi Pengiriman</h2>
                            <p class="mt-1 text-sm text-slate-500">Pilihan layanan sudah siap. Buka modal untuk melihat semua kurir dengan tampilan yang lebih lega.</p>
                        </div>

                        <div class="space-y-4 px-6 py-5">
                            <div id="selectedShippingCard" class="{{ !empty($selectedShipping) ? '' : 'hidden ' }}rounded-[1.5rem] border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white px-5 py-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Layanan Terpilih</p>
                                    <div class="mt-3 flex items-start justify-between gap-4">
                                        <div>
                                            <p id="selectedShippingCourier" class="text-base font-semibold text-slate-900">{{ $selectedShipping['kurir'] ?? '' }}</p>
                                            <p id="selectedShippingService" class="mt-1 text-sm text-slate-500">{{ $selectedShipping['service_pengiriman'] ?? '' }}</p>
                                            <p id="selectedShippingEstimate" class="mt-1 text-xs text-slate-500">{{ !empty($selectedShipping) ? 'Estimasi ' . $selectedShipping['estimasi_pengiriman'] : '' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">Ongkir</p>
                                            <p id="selectedShippingCost" class="mt-1 text-xl font-bold text-slate-900">{{ !empty($selectedShipping) ? 'Rp ' . number_format($selectedShipping['ongkir'], 0, ',', '.') : '' }}</p>
                                        </div>
                                    </div>
                                </div>

                            <button type="button" id="openShippingOptionsModal" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-200 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:scale-105 hover:cursor-pointer transition duration-300">Lihat Pilihan Kurir</button>
                            <p class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-700">Klik salah satu layanan kurir, lalu lanjutkan dengan tombol Checkout untuk masuk ke halaman pembayaran.</p>
                        </div>
                    </div>

                <div id="shippingEmptyState" class="{{ empty($rajaongkirRates) && $selectedDestination ? '' : 'hidden ' }}rounded-[2rem] border border-dashed border-slate-300 bg-white/90 px-6 py-8 text-center shadow-xl shadow-slate-100/70 backdrop-blur">
                        <h2 class="text-lg font-semibold text-slate-900">Pilihan Kurir Belum Muncul</h2>
                        <p id="shippingEmptyStateText" class="mt-2 text-sm text-slate-500">Setelah klik `Cek Ongkir`, layanan pengiriman akan tampil di panel sebelah kanan. Kalau tetap kosong, biasanya memang tidak ada layanan yang tersedia untuk kombinasi origin, tujuan, atau berat paket saat ini.</p>
                    </div>
            </section>

            <aside class="space-y-6">
                <div class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-sky-100/40 backdrop-blur">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Data Penerima</h2>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        <div class="rounded-[1.5rem] border border-sky-100 bg-gradient-to-br from-sky-50 to-white px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Dikirim Dari</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $originLocation['label'] ?? config('services.rajaongkir.origin_search') ?? 'Belum terdeteksi' }}</p>
                        </div>

                        <div class="space-y-2" autocomplete="off">
                            <div class="relative">
                                <label for="tujuan_pengiriman" class="mb-1 block text-sm font-medium text-slate-700">Cari Tujuan Pengiriman</label>
                                <input type="text" id="tujuan_pengiriman" name="tujuan_pengiriman" value="{{ $oldTujuanPengiriman }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Ketik minimal 3 huruf, mis. Sumbersari Jember" autocomplete="off" required>
                                <ul id="destinationAutocompleteList" class="absolute left-0 right-0 top-[calc(100%+0.5rem)] z-20 hidden max-h-72 overflow-y-auto rounded-[1.25rem] border border-slate-200 bg-white p-2 shadow-xl shadow-slate-200/80"></ul>
                            </div>
                            <p id="destinationAutocompleteHelp" class="text-xs font-medium text-slate-500">Ketik nama kecamatan atau kota, lalu pilih salah satu hasil yang muncul.</p>
                            @error('tujuan_pengiriman')
                                <p class="text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="selectedDestinationCard" class="{{ $selectedDestination ? '' : 'hidden ' }}rounded-[1.5rem] border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white px-4 py-4 shadow-sm shadow-emerald-100/40">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Tujuan Terpilih</p>
                                <p id="selectedDestinationLabel" class="mt-2 text-sm font-semibold text-slate-900">{{ $selectedDestination['label'] ?? '' }}</p>
                                <p id="selectedDestinationPostalCode" class="mt-1 text-xs font-medium uppercase tracking-[0.16em] text-emerald-700 {{ !empty($selectedDestination['postal_code']) ? '' : 'hidden' }}">{{ !empty($selectedDestination['postal_code']) ? 'Kode Pos ' . $selectedDestination['postal_code'] : '' }}</p>
                            </div>

                        <form id="ratesForm" action="{{ route('user.checkout.rates') }}" method="POST" class="space-y-4" autocomplete="off">
                            @csrf
                            <input type="hidden" id="selected_destination_id" name="selected_destination_id" value="{{ $selectedDestination['id'] ?? '' }}">
                            <input type="hidden" id="selected_destination_label" name="selected_destination_label" value="{{ $selectedDestination['label'] ?? '' }}">
                            <input type="hidden" id="selected_destination_postal_code" name="selected_destination_postal_code" value="{{ $selectedDestination['postal_code'] ?? '' }}">
                            <input type="hidden" name="tujuan_pengiriman" value="{{ $oldTujuanPengiriman }}">

                            <div>
                                <label for="nama_penerima" class="mb-1 block text-sm font-medium text-slate-700">Nama Penerima</label>
                                <input type="text" id="nama_penerima" name="nama_penerima" value="{{ $namaPenerima }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" required>
                                @error('nama_penerima')
                                    <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_telp_penerima" class="mb-1 block text-sm font-medium text-slate-700">No. Telp</label>
                                <input type="text" id="no_telp_penerima" name="no_telp_penerima" value="{{ $noTelpPenerima }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" required>
                                @error('no_telp_penerima')
                                    <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="alamat_penerima" class="mb-1 block text-sm font-medium text-slate-700">Alamat Penerima</label>
                                <textarea id="alamat_penerima" name="alamat_penerima" rows="4" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Masukkan alamat lengkap pengiriman" required>{{ $alamatPenerima }}</textarea>
                                @error('alamat_penerima')
                                    <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('selected_destination_id')
                                <p class="text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror

                            <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 px-4 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Alur Checkout</p>
                                <p class="mt-2 text-sm text-slate-500">Alur Checkout, isi data tujuan pengiriman, cek ongkir, lalu pilih kurir. Setelah itu klik tombol Checkout untuk membuat transaksi dan masuk ke lanjut ke pembayaran.</p>
                            </div>

                            <button type="submit" id="checkOngkirButton" class="w-full rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-semibold text-sky-700 shadow-sm transition duration-300 hover:scale-105 hover:cursor-pointer hover:-translate-y-0.5 hover:bg-sky-100 {{ $keranjangItems->count() ? '' : 'cursor-not-allowed opacity-50' }}" {{ $keranjangItems->count() ? '' : 'disabled' }}>Cek Ongkir</button>
                        </form>
                    </div>
                </div>

               



                @if (!empty($selectedShipping))
                    <form id="checkoutProceedForm" action="{{ route('user.checkout.proceed') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full rounded-[2rem] bg-emerald-600 px-5 py-4 text-base font-semibold text-white shadow-xl shadow-emerald-200 transition duration-300 hover:-translate-y-0.5 hover:bg-emerald-500">Checkout</button>
                    </form>
                @endif
            </aside>
        </div>
    </div>
</div>

    <div id="shippingOptionsModal" class="fixed inset-0 z-[70] hidden">
        <div id="shippingOptionsOverlay" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300"></div>
        <div class="absolute inset-x-0 bottom-0 top-auto mx-auto h-[88vh] w-full max-w-5xl translate-y-10 overflow-hidden rounded-t-[2rem] bg-white shadow-2xl transition duration-300 sm:bottom-auto sm:left-1/2 sm:top-1/2 sm:h-[82vh] sm:-translate-x-1/2 sm:-translate-y-[46%] sm:rounded-[2rem]" id="shippingOptionsPanel">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Opsi Pengiriman</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Pilih Kurir yang Paling Cocok</h2>
                    <p class="mt-1 text-sm text-slate-500">Bandingkan biaya, layanan, dan estimasi tanpa bikin halaman checkout penuh.</p>
                </div>
                <button type="button" id="closeShippingOptionsModal" class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-slate-200 hover:scale-105 hover:cursor-pointer transition duration-300">Tutup</button>
            </div>
            <div class="h-[calc(88vh-92px)] overflow-y-auto px-6 py-6 sm:h-[calc(82vh-92px)]">
                <div id="shippingRatesGrid" class="grid gap-4 lg:grid-cols-2">
                    @foreach ($rajaongkirRates as $rate)
                        <div class="rounded-[1.75rem] border border-slate-200 bg-gradient-to-br from-white to-slate-50 px-5 py-5 shadow-sm shadow-slate-100/80 transition duration-300 hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-sky-100/80">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-base font-semibold text-slate-900">{{ $rate['name'] ?? ($rate['courier'] ?? 'Kurir') }}</p>
                                        <span class="rounded-full bg-slate-900 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-white">{{ $rate['service'] ?? '' }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-500">{{ $rate['description'] ?? ($rate['service_name'] ?? 'Layanan') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Ongkir</p>
                                    <p class="mt-1 text-2xl font-bold text-slate-900">Rp {{ number_format($rate['cost'] ?? ($rate['shipping_cost'] ?? 0), 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl bg-slate-100 px-4 py-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Estimasi</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-900">{{ strtoupper($rate['etd'] ?? '-') }}</p>
                                </div>
                                <div class="rounded-2xl bg-sky-50 px-4 py-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-sky-600">Kode Kurir</p>
                                    <p class="mt-1 text-sm font-semibold text-sky-900">{{ strtoupper($rate['code'] ?? '-') }}</p>
                                </div>
                            </div>

                            <form action="{{ route('user.checkout.shipping') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="ongkir" value="{{ $rate['cost'] ?? ($rate['shipping_cost'] ?? 0) }}">
                                <input type="hidden" name="kurir" value="{{ $rate['name'] ?? ($rate['courier'] ?? 'Kurir') }}">
                                <input type="hidden" name="service_pengiriman" value="{{ $rate['service'] ?? ($rate['service_name'] ?? 'Layanan') }}">
                                <input type="hidden" name="estimasi_pengiriman" value="{{ $rate['etd'] ?? '-' }}">
                                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800">Pilih Layanan Ini</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

<script>
    (() => {
        const subtotalValue = @json((int) $subtotal);
        const input = document.getElementById('tujuan_pengiriman');
        const list = document.getElementById('destinationAutocompleteList');
        const helpText = document.getElementById('destinationAutocompleteHelp');
        const destinationIdInput = document.getElementById('selected_destination_id');
        const destinationLabelInput = document.getElementById('selected_destination_label');
        const destinationPostalCodeInput = document.getElementById('selected_destination_postal_code');
        const selectedDestinationCard = document.getElementById('selectedDestinationCard');
        const selectedDestinationLabel = document.getElementById('selectedDestinationLabel');
        const selectedDestinationPostalCode = document.getElementById('selectedDestinationPostalCode');
        const ratesForm = document.getElementById('ratesForm');
        const checkOngkirButton = document.getElementById('checkOngkirButton');
        const checkoutAjaxAlert = document.getElementById('checkoutAjaxAlert');
        const checkoutErrorAlert = document.getElementById('checkoutErrorAlert');
        const shippingEmptyState = document.getElementById('shippingEmptyState');
        const shippingEmptyStateText = document.getElementById('shippingEmptyStateText');
        const shippingOptionsSection = document.getElementById('shippingOptionsSection');
        const shippingRatesGrid = document.getElementById('shippingRatesGrid');
        const shippingCostSummary = document.getElementById('shippingCostSummary');
        const estimatedTotalSummary = document.getElementById('estimatedTotalSummary');
        const selectedShippingCard = document.getElementById('selectedShippingCard');
        const checkoutProceedForm = document.getElementById('checkoutProceedForm');
        const selectedShippingCourier = document.getElementById('selectedShippingCourier');
        const selectedShippingService = document.getElementById('selectedShippingService');
        const selectedShippingEstimate = document.getElementById('selectedShippingEstimate');
        const selectedShippingCost = document.getElementById('selectedShippingCost');
        const endpoint = @json(route('user.checkout.destination.autocomplete', [], false));
        const ratesEndpoint = @json(route('user.checkout.rates', [], false));
        const shippingEndpoint = @json(route('user.checkout.shipping', [], false));
        const csrfToken = @json(csrf_token());
        const initialRates = @json($rajaongkirRates);

        if (!input || !list || !helpText || !destinationIdInput || !destinationLabelInput || !destinationPostalCodeInput || !ratesForm || !checkOngkirButton) {
            return;
        }

        let debounceTimer = null;
        let activeRequest = 0;

        const hideList = () => {
            list.classList.add('hidden');
            list.innerHTML = '';
        };

        const setSelectedDestination = (destination) => {
            input.value = destination.label;
            destinationIdInput.value = destination.id;
            destinationLabelInput.value = destination.label;
            destinationPostalCodeInput.value = destination.postal_code || '';
            helpText.textContent = destination.postal_code
                ? `Tujuan dipilih: ${destination.label} (${destination.postal_code})`
                : `Tujuan dipilih: ${destination.label}`;
            if (selectedDestinationCard && selectedDestinationLabel && selectedDestinationPostalCode) {
                selectedDestinationCard.classList.remove('hidden');
                selectedDestinationLabel.textContent = destination.label;

                if (destination.postal_code) {
                    selectedDestinationPostalCode.textContent = `Kode Pos ${destination.postal_code}`;
                    selectedDestinationPostalCode.classList.remove('hidden');
                } else {
                    selectedDestinationPostalCode.textContent = '';
                    selectedDestinationPostalCode.classList.add('hidden');
                }
            }
            hideList();
        };

        const formatRupiah = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;
        const showAlert = (message, type = 'success') => {
            if (!checkoutAjaxAlert) {
                return;
            }

            checkoutAjaxAlert.className = `mb-6 rounded-3xl px-5 py-4 text-sm font-medium ${type === 'error'
                ? 'border border-red-200 bg-red-50 text-red-700'
                : 'border border-emerald-200 bg-emerald-50 text-emerald-700'}`;
            checkoutAjaxAlert.textContent = message;
            checkoutAjaxAlert.classList.remove('hidden');

            if (checkoutErrorAlert) {
                checkoutErrorAlert.classList.add('hidden');
            }
        };

        const hideAlert = () => {
            if (checkoutAjaxAlert) {
                checkoutAjaxAlert.classList.add('hidden');
                checkoutAjaxAlert.textContent = '';
            }
        };

        const renderRateCard = (rate) => `
            <div class="rounded-[1.75rem] border border-slate-200 bg-gradient-to-br from-white to-slate-50 px-5 py-5 shadow-sm shadow-slate-100/80 transition duration-300 hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-sky-100/80">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-base font-semibold text-slate-900">${rate.name ?? rate.courier ?? 'Kurir'}</p>
                            <span class="rounded-full bg-slate-900 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-white">${rate.service ?? ''}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">${rate.description ?? rate.service_name ?? 'Layanan'}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Ongkir</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">${formatRupiah(rate.cost ?? rate.shipping_cost ?? 0)}</p>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-100 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Estimasi</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">${String(rate.etd ?? '-').toUpperCase()}</p>
                    </div>
                    <div class="rounded-2xl bg-sky-50 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-sky-600">Kode Kurir</p>
                        <p class="mt-1 text-sm font-semibold text-sky-900">${String(rate.code ?? '-').toUpperCase()}</p>
                    </div>
                </div>

                <form action="${shippingEndpoint}" method="POST" class="mt-4">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="ongkir" value="${rate.cost ?? rate.shipping_cost ?? 0}">
                    <input type="hidden" name="kurir" value="${rate.name ?? rate.courier ?? 'Kurir'}">
                    <input type="hidden" name="service_pengiriman" value="${rate.service ?? rate.service_name ?? 'Layanan'}">
                    <input type="hidden" name="estimasi_pengiriman" value="${rate.etd ?? '-'}">
                    <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800">Pilih Layanan Ini</button>
                </form>
            </div>
        `;

        const renderRates = (rates) => {
            if (!shippingRatesGrid || !shippingOptionsSection) {
                return;
            }

            shippingRatesGrid.innerHTML = rates.map(renderRateCard).join('');
            shippingOptionsSection.classList.remove('hidden');
        };

        const renderItems = (items) => {
            if (!items.length) {
                list.innerHTML = '<li class="px-4 py-3 text-sm text-slate-500">Tujuan tidak ditemukan.</li>';
                list.classList.remove('hidden');
                return;
            }

            list.innerHTML = items.map((item) => {
                const postal = item.postal_code ? `<span class="mt-1 block text-xs font-medium uppercase tracking-[0.14em] text-slate-500">Kode Pos ${item.postal_code}</span>` : '';

                return `
                    <li>
                        <button
                            type="button"
                            class="w-full rounded-2xl px-4 py-3 text-left transition hover:bg-sky-50"
                            data-destination-id="${item.id}"
                            data-destination-label="${item.label.replace(/"/g, '&quot;')}"
                            data-destination-postal="${(item.postal_code || '').replace(/"/g, '&quot;')}"
                        >
                            <span class="block text-sm font-semibold text-slate-900">${item.label}</span>
                            ${postal}
                        </button>
                    </li>
                `;
            }).join('');

            list.classList.remove('hidden');

            list.querySelectorAll('button[data-destination-id]').forEach((button) => {
                button.addEventListener('click', () => {
                    setSelectedDestination({
                        id: button.dataset.destinationId,
                        label: button.dataset.destinationLabel,
                        postal_code: button.dataset.destinationPostal,
                    });
                });
            });
        };

        input.addEventListener('input', () => {
            window.clearTimeout(debounceTimer);

            const query = input.value.trim();
            destinationIdInput.value = '';
            destinationLabelInput.value = '';
            destinationPostalCodeInput.value = '';
            if (selectedDestinationCard) {
                selectedDestinationCard.classList.add('hidden');
            }

            if (query.length < 3) {
                helpText.textContent = 'Ketik nama kecamatan atau kota, lalu pilih salah satu hasil yang muncul.';
                hideList();
                return;
            }

            helpText.textContent = 'Mencari tujuan pengiriman...';

            debounceTimer = window.setTimeout(async () => {
                activeRequest += 1;
                const currentRequest = activeRequest;

                try {
                    const response = await fetch(`${endpoint}?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const payload = await response.json();

                    if (currentRequest !== activeRequest) {
                        return;
                    }

                    if (!response.ok) {
                        helpText.textContent = payload.message || 'Gagal mencari tujuan pengiriman.';
                        hideList();
                        return;
                    }

                    helpText.textContent = payload.data.length
                        ? 'Pilih salah satu tujuan yang paling sesuai.'
                        : 'Tujuan tidak ditemukan.';

                    renderItems(payload.data || []);
                } catch (error) {
                    if (currentRequest !== activeRequest) {
                        return;
                    }

                    helpText.textContent = 'Gagal mengambil data tujuan pengiriman.';
                    hideList();
                }
            }, 500);
        });

        document.addEventListener('click', (event) => {
            if (!list.contains(event.target) && event.target !== input) {
                hideList();
            }
        });

        input.addEventListener('focus', () => {
            if (list.children.length) {
                list.classList.remove('hidden');
            }
        });

        ratesForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            hideAlert();

            checkOngkirButton.disabled = true;
            checkOngkirButton.classList.add('cursor-not-allowed', 'opacity-70');
            checkOngkirButton.textContent = 'Memuat Ongkir...';

            try {
                const response = await fetch(ratesEndpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: new FormData(ratesForm),
                });

                const payload = await response.json();

                if (!response.ok) {
                    showAlert(payload.message || 'Gagal memuat ongkir.', 'error');

                    if (shippingOptionsSection) {
                        shippingOptionsSection.classList.add('hidden');
                    }

                    if (shippingEmptyState && shippingEmptyStateText) {
                        shippingEmptyState.classList.remove('hidden');
                        shippingEmptyStateText.textContent = payload.message || 'Gagal memuat ongkir.';
                    }

                    shippingCostSummary.textContent = 'Belum dipilih';
                    estimatedTotalSummary.textContent = formatRupiah(subtotalValue);
                    return;
                }

                renderRates(payload.rates || []);
                showAlert(payload.message || 'Opsi pengiriman berhasil dimuat.');

                if (shippingEmptyState) {
                    shippingEmptyState.classList.add('hidden');
                }

                if (selectedShippingCard) {
                    selectedShippingCard.classList.add('hidden');
                }

                if (checkoutProceedForm) {
                    checkoutProceedForm.classList.add('hidden');
                }

                shippingCostSummary.textContent = 'Belum dipilih';
                estimatedTotalSummary.textContent = formatRupiah(subtotalValue);
            } catch (error) {
                showAlert('Gagal terhubung ke server saat memuat ongkir.', 'error');
            } finally {
                checkOngkirButton.disabled = false;
                checkOngkirButton.classList.remove('cursor-not-allowed', 'opacity-70');
                checkOngkirButton.textContent = 'Cek Ongkir';
            }
        });

        if (Array.isArray(initialRates) && initialRates.length) {
            renderRates(initialRates);
        }

        const shippingModal = document.getElementById('shippingOptionsModal');
        const shippingOverlay = document.getElementById('shippingOptionsOverlay');
        const shippingPanel = document.getElementById('shippingOptionsPanel');
        const openShippingModalButton = document.getElementById('openShippingOptionsModal');
        const closeShippingModalButton = document.getElementById('closeShippingOptionsModal');

        if (shippingModal && shippingOverlay && shippingPanel && openShippingModalButton && closeShippingModalButton) {
            const openShippingModal = () => {
                shippingModal.classList.remove('hidden');

                requestAnimationFrame(() => {
                    shippingOverlay.classList.remove('opacity-0');
                    shippingPanel.classList.remove('translate-y-10');
                    shippingPanel.classList.add('sm:-translate-y-1/2');
                });
            };

            const closeShippingModal = () => {
                shippingOverlay.classList.add('opacity-0');
                shippingPanel.classList.add('translate-y-10');
                shippingPanel.classList.remove('sm:-translate-y-1/2');

                window.setTimeout(() => {
                    shippingModal.classList.add('hidden');
                }, 250);
            };

            openShippingModalButton.addEventListener('click', openShippingModal);
            closeShippingModalButton.addEventListener('click', closeShippingModal);
            shippingOverlay.addEventListener('click', closeShippingModal);

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !shippingModal.classList.contains('hidden')) {
                    closeShippingModal();
                }
            });
        }
    })();
</script>
@endsection
