@extends('layouts.normal')

@section('content')
@php
    $user = auth()->user();
    $oldTujuanPengiriman = old('tujuan_pengiriman', $selectedDestination['label'] ?? '');
    $namaPenerima = old('nama_penerima', $checkoutForm['nama_penerima'] ?? $user->nama_lengkap);
    $noTelpPenerima = old('no_telp_penerima', $checkoutForm['no_telp_penerima'] ?? $user->no_telepon);
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
                                        @if ($item->produk?->foto_produk)
                                            <img src="{{ asset('storage/' . $item->produk->foto_produk) }}" alt="{{ $item->produk->nama_produk }}" class="h-24 w-24 rounded-2xl object-cover">
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

                <div id="shippingOptionsSection" class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-sky-100/40 backdrop-blur">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Pengiriman Otomatis</h2>
                        <p class="mt-1 text-sm text-slate-500">Setelah tujuan pengiriman dipilih dan data penerima lengkap, sistem akan langsung memasang ongkir JNE Reguler otomatis.</p>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        <div id="selectedShippingCard" class="{{ !empty($selectedShipping) ? '' : 'hidden ' }}rounded-[1.5rem] border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Layanan Terpasang</p>
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

                        <div id="shippingEmptyState" class="{{ empty($selectedShipping) ? '' : 'hidden ' }}rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 px-5 py-5 text-center">
                            <h2 class="text-base font-semibold text-slate-900">Ongkir Belum Terpasang</h2>
                            <p id="shippingEmptyStateText" class="mt-2 text-sm text-slate-500">Lengkapi nama penerima, no. telp, alamat, lalu pilih tujuan pengiriman agar ongkir JNE Reguler dipasang otomatis.</p>
                        </div>
                    </div>
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
                            <input type="hidden" id="selected_destination_province" name="selected_destination_province" value="{{ $selectedDestination['province_name'] ?? '' }}">
                            <input type="hidden" id="selected_destination_city" name="selected_destination_city" value="{{ $selectedDestination['city_name'] ?? '' }}">
                            <input type="hidden" id="selected_destination_district" name="selected_destination_district" value="{{ $selectedDestination['district_name'] ?? '' }}">
                            <input type="hidden" id="selected_destination_subdistrict" name="selected_destination_subdistrict" value="{{ $selectedDestination['subdistrict_name'] ?? '' }}">
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
                                <p class="mt-2 text-sm text-slate-500">Alur checkout: isi data penerima, pilih tujuan dari autocomplete, lalu sistem akan langsung memasang ongkir JNE Reguler. Setelah itu klik tombol Checkout untuk membuat transaksi dan lanjut ke pembayaran.</p>
                            </div>
                        </form>
                    </div>
                </div>

               



                <form id="checkoutProceedForm" action="{{ route('user.checkout.proceed') }}" method="POST" class="{{ !empty($selectedShipping) ? '' : 'hidden' }}">
                    @csrf
                    <button type="submit" class="w-full rounded-[2rem] bg-emerald-600 px-5 py-4 text-base font-semibold text-white shadow-xl shadow-emerald-200 transition duration-300 hover:-translate-y-0.5 hover:bg-emerald-500">Checkout</button>
                </form>
            </aside>
        </div>
    </div>
</div>

<script id="checkoutPageData" type="application/json">
    {!! json_encode([
        'subtotal' => (int) $subtotal,
        'autocompleteEndpoint' => route('user.checkout.destination.autocomplete', [], false),
        'ratesEndpoint' => route('user.checkout.rates', [], false),
        'csrfToken' => csrf_token(),
        'hasSelectedShipping' => !empty($selectedShipping),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection
