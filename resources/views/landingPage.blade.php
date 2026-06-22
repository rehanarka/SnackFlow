@extends('layouts.normal')

@section('content')
    <style>
        html {
            scroll-behavior: smooth;
        }

        @keyframes drift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(0, -20px, 0) scale(1.04); }
        }

        @keyframes floatSoft {
            0%, 100% { transform: translate3d(0, 0, 0); }
            50% { transform: translate3d(0, -12px, 0); }
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translate3d(0, 30px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .landing-drift {
            animation: drift 8s ease-in-out infinite;
        }

        .landing-float {
            animation: floatSoft 6s ease-in-out infinite;
        }

        .landing-reveal {
            animation: reveal .8s ease-out both;
        }
    </style>

    <div class="min-h-screen bg-[#f6f1e7] text-slate-900">
        <x-headerLanding />

        <a
            href="https://wa.me/6281515400001"
            target="_blank"
            rel="noreferrer"
            aria-label="Hubungi SnackFlow lewat WhatsApp"
            class="fixed bottom-6 right-6 z-50 flex h-16 w-16 items-center justify-center rounded-full bg-[#25D366] text-white shadow-[0_18px_40px_rgba(37,211,102,0.36)] ring-4 ring-white/80 transition duration-300 hover:-translate-y-1 hover:bg-[#1ebe5d]"
        >
            <svg class="h-8 w-8" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                <path d="M16.03 3.2A12.67 12.67 0 0 0 5.2 22.43L3.6 28.8l6.52-1.52A12.67 12.67 0 1 0 16.03 3.2Zm0 2.33a10.33 10.33 0 0 1 8.78 15.78 10.33 10.33 0 0 1-13.82 3.66l-.45-.25-3.63.85.9-3.5-.3-.47A10.33 10.33 0 0 1 16.03 5.53Zm-4.4 4.98c-.25 0-.64.1-.98.47-.34.37-1.3 1.27-1.3 3.1s1.33 3.6 1.52 3.85c.19.25 2.58 4.12 6.36 5.6 3.15 1.24 3.79.99 4.47.93.68-.06 2.2-.9 2.5-1.77.31-.87.31-1.61.22-1.77-.09-.16-.34-.25-.71-.43-.37-.19-2.2-1.09-2.54-1.21-.34-.13-.59-.19-.84.18-.25.37-.96 1.21-1.18 1.46-.22.25-.43.28-.8.09-.37-.19-1.56-.58-2.98-1.84-1.1-.98-1.84-2.2-2.06-2.57-.22-.37-.02-.57.16-.75.17-.17.37-.43.56-.65.19-.22.25-.37.37-.62.13-.25.06-.47-.03-.65-.09-.19-.84-2.02-1.15-2.76-.3-.72-.61-.62-.84-.63h-.71Z"/>
            </svg>
        </a>

        <section id="home" class="relative isolate overflow-hidden">
            <img
                src="/images/backgroundLanding-opt.jpg"
                alt="Produk SnackFlow"
                class="absolute inset-0 h-full w-full object-cover object-center"
                width="1400"
                height="661"
                fetchpriority="high"
                decoding="async"
            >
            <div class="absolute inset-0 bg-[linear-gradient(100deg,rgba(8,15,26,0.80)_0%,rgba(8,15,26,0.52)_42%,rgba(8,15,26,0.20)_100%)]"></div>
            <div class="landing-drift absolute -left-24 top-16 h-72 w-72 rounded-full bg-sky-300/18 blur-3xl"></div>
            <div class="landing-drift absolute bottom-12 right-10 h-80 w-80 rounded-full bg-blue-400/18 blur-3xl" style="animation-delay: -2s;"></div>

            <div class="relative z-10 mx-auto grid min-h-[calc(100vh-88px)] max-w-7xl items-center gap-8 px-6 py-8 lg:grid-cols-[1.08fr_0.92fr] lg:px-10">
                <div class="landing-reveal max-w-3xl">
                    <div class="mb-5 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.28em] text-white/85 backdrop-blur-md">
                        Sistem katalog dan transaksi UMKM
                    </div>

                    <h1 class="text-4xl font-black uppercase leading-[0.92] text-white sm:text-5xl lg:text-6xl">
                        Camilan lokal,
                        <span class="block text-sky-300">alur digital</span>
                        yang lebih rapi
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/85 sm:text-base">
                        SnackFlow membantu Matrix Jaya mengelola katalog produk, checkout, pembayaran, dan riwayat transaksi dalam satu alur yang lebih tertib dan lebih enak dipakai.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="/login" class="rounded-full bg-[linear-gradient(90deg,#38bdf8_0%,#2563eb_100%)] px-7 py-3 text-sm font-bold uppercase tracking-[0.18em] text-white shadow-[0_18px_40px_rgba(37,99,235,0.32)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_46px_rgba(37,99,235,0.40)]">
                            Masuk Sekarang
                        </a>
                    </div>
                </div>

                <div class="landing-float landing-reveal lg:justify-self-end" style="animation-delay: .15s;">
                    <div class="max-w-xl overflow-hidden rounded-[2rem] border border-white/22 bg-white/14 p-3 shadow-[0_24px_90px_rgba(15,23,42,0.32)] backdrop-blur-xl">
                        <div class="rounded-[1.6rem] border border-white/20 bg-[linear-gradient(180deg,rgba(255,255,255,0.24)_0%,rgba(255,255,255,0.10)_100%)] p-7 text-white sm:p-8">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.26em] text-white/60">Matrix Jaya</p>
                                    <h2 class="mt-3 text-2xl font-black uppercase leading-tight">UMKM lokal dengan pengalaman belanja yang lebih modern</h2>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-[1.4rem] bg-slate-950/22 px-5 py-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-white/60">Checkout</p>
                                    <p class="mt-3 text-lg font-bold text-white">Pengiriman lebih jelas</p>
                                    <p class="mt-2 text-sm leading-7 text-white/80">Pencarian tujuan, ongkir, dan pembayaran tersusun dalam satu alur.</p>
                                </div>
                                <div class="rounded-[1.4rem] bg-slate-950/22 px-5 py-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-white/60">Transaksi</p>
                                    <p class="mt-3 text-lg font-bold text-white">Riwayat lebih tertata</p>
                                    <p class="mt-2 text-sm leading-7 text-white/80">Online dan operasional admin bisa dipantau dengan struktur yang lebih rapi.</p>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="#tentang" class="rounded-full bg-white px-6 py-3 text-sm font-bold uppercase tracking-[0.16em] text-slate-900 transition duration-300 hover:-translate-y-1 hover:bg-sky-50">
                                    Jelajahi Tentang Kami
                                </a>
                                <a href="#kontak" class="rounded-full border border-white/30 px-6 py-3 text-sm font-bold uppercase tracking-[0.16em] text-white transition duration-300 hover:-translate-y-1 hover:bg-white/10">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="tentang" class="relative overflow-hidden bg-[#f7f3ea] px-6 py-20 lg:px-10">
            <div class="absolute -right-20 top-10 h-72 w-72 rounded-full bg-sky-300/14 blur-3xl"></div>
            <div class="absolute -left-16 bottom-4 h-64 w-64 rounded-full bg-blue-200/20 blur-3xl"></div>

            <div class="relative mx-auto max-w-7xl">
                <div class="landing-reveal max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.30em] text-slate-500">Tentang Kami</p>
                    <h2 class="mt-4 text-3xl font-black uppercase leading-tight text-slate-900 sm:text-5xl">
                        Matrix Jaya, usaha lokal dari Jember dengan fokus pada camilan berkualitas
                    </h2>
                    <p class="mt-6 text-base leading-8 text-slate-600 sm:text-lg">
                        Matrix Jaya memproduksi makanan ringan berbahan dasar ikan lele dan aneka olahan lain seperti abon lele, sumpia ikat, widaran, keripik tape, serta keripik buah. SnackFlow hadir untuk mendukung alur penjualan dan transaksi digitalnya.
                    </p>
                </div>

                <div class="mt-12 grid gap-8 lg:grid-cols-[0.86fr_1.14fr]">
                    <div class="landing-reveal overflow-hidden rounded-[2rem] border border-white/60 bg-white p-5 shadow-[0_22px_60px_rgba(15,23,42,0.10)]" style="animation-delay: .1s;">
                        <div class="overflow-hidden rounded-[1.5rem]">
                            <img class="h-full w-full object-cover" src="/images/produk-opt.jpg" alt="Produk Matrix Jaya" width="640" height="640" loading="lazy" decoding="async">
                        </div>
                        <div class="mt-5 rounded-[1.5rem] bg-[#0f172a] px-5 py-5 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/55">Nilai Utama</p>
                            <p class="mt-3 text-base font-semibold leading-8">
                                Menyajikan camilan yang gurih, renyah, aman, dan layak dibawa ke pasar yang lebih luas dengan proses yang lebih tertata.
                            </p>
                        </div>
                    </div>

                    <div class="landing-reveal rounded-[2rem] border border-white/60 bg-white/88 p-6 shadow-[0_22px_60px_rgba(15,23,42,0.10)] backdrop-blur-xl sm:p-8" style="animation-delay: .18s;">
                        <div class="grid gap-4">
                            <div class="rounded-[1.6rem] bg-[#edf5ff] px-5 py-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">01</p>
                                <h3 class="mt-3 text-xl font-black uppercase text-slate-900">Bahan baku pilihan</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    Produk dibuat dari bahan yang diperhatikan kualitasnya, sehingga rasa, kebersihan, dan konsistensi hasil lebih terjaga.
                                </p>
                            </div>

                            <div class="rounded-[1.6rem] bg-pink-100/50 px-5 py-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">02</p>
                                <h3 class="mt-3 text-xl font-black uppercase text-slate-900">Produksi lebih higienis</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    Alur produksi ditata agar produk yang diterima pelanggan tetap aman, layak konsumsi, dan memiliki kualitas yang lebih stabil.
                                </p>
                            </div>

                            <div class="rounded-[1.6rem] bg-[#eefbf2] px-5 py-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">03</p>
                                <h3 class="mt-3 text-xl font-black uppercase text-slate-900">Siap dipasarkan lebih luas</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-600">
                                    Dengan kemasan yang rapi dan dukungan sistem transaksi, Matrix Jaya lebih siap menjangkau pelanggan online maupun pembeli langsung.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="produk" class="relative overflow-hidden bg-white px-6 py-20 lg:px-10">
            <div class="relative mx-auto max-w-7xl">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.30em] text-slate-500">Produk Pilihan</p>
                        <h2 class="mt-4 text-3xl font-black uppercase leading-tight text-slate-900 sm:text-5xl">
                            Lihat beberapa produk unggulan Matrix Jaya yang siap menemani hari kamu
                        </h2>
                    </div>

                </div>

                @if (($produkLanding ?? collect())->isEmpty())
                    <div class="mt-12 rounded-[2rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center">
                        <h3 class="text-lg font-semibold text-slate-800">Produk belum tersedia</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Produk dari katalog akan tampil di sini setelah admin menambahkan data produk.</p>
                    </div>
                @else
                    <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($produkLanding as $produk)
                            <article class="group overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-[0_18px_48px_rgba(15,23,42,0.09)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_58px_rgba(15,23,42,0.13)]">
                                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                                    @if ($produk->foto_produk)
                                        <img src="{{ asset('storage/' . $produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                                    @else
                                        <div class="flex h-full items-center justify-center text-sm font-semibold text-slate-400">No Image</div>
                                    @endif
                                    <div class="absolute left-4 top-4 rounded-full bg-white/92 px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-slate-700 shadow-sm">
                                        Stok {{ $produk->stok }}
                                    </div>
                                </div>

                                <div class="space-y-4 p-5">
                                    <div>
                                        <h3 class="text-xl font-black uppercase leading-tight text-slate-900">{{ $produk->nama_produk }}</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-500 line-clamp-2">{{ $produk->deskripsi ?: 'Camilan Matrix Jaya siap menemani pesanan kamu.' }}</p>
                                    </div>

                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Harga</p>
                                            <p class="mt-1 text-xl font-black text-sky-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <a href="{{ route('login') }}" class="shrink-0 rounded-full bg-[linear-gradient(90deg,#38bdf8_0%,#2563eb_100%)] px-5 py-3 text-sm font-bold uppercase tracking-[0.14em] text-white shadow-[0_14px_32px_rgba(37,99,235,0.24)] transition duration-300 hover:-translate-y-0.5">
                                            Beli
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section id="artikel" class="relative overflow-hidden bg-[#f7f3ea] px-6 py-20 lg:px-10">
            <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-sky-300/14 blur-3xl"></div>
            <div class="relative mx-auto max-w-7xl">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.30em] text-slate-500">Artikel</p>
                        <h2 class="mt-4 text-3xl font-black uppercase leading-tight text-slate-900 sm:text-5xl">
                            Wawasan camilan dan produk Matrix Jaya
                        </h2>
                        <p class="mt-5 text-base leading-8 text-slate-600">
                            Baca ringkasan artikel dari SnackFlow tanpa perlu login. Untuk melihat semua artikel di dashboard, masuk sebagai user terlebih dahulu.
                        </p>
                    </div>

                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-bold uppercase tracking-[0.16em] text-slate-800 shadow-sm transition duration-300 hover:-translate-y-1 hover:bg-slate-50">
                        Lihat Semua
                    </a>
                </div>

                @if (($artikelLanding ?? collect())->isEmpty())
                    <div class="mt-12 rounded-[2rem] border border-dashed border-slate-300 bg-white/70 px-6 py-14 text-center">
                        <h3 class="text-lg font-semibold text-slate-800">Artikel belum tersedia</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Artikel akan tampil di sini setelah admin menambahkan konten wawasan.</p>
                    </div>
                @else
                    <div class="mt-12 grid gap-6 md:grid-cols-3">
                        @foreach ($artikelLanding as $artikel)
                            <article class="overflow-hidden rounded-[1.6rem] border border-white/70 bg-white shadow-[0_18px_48px_rgba(15,23,42,0.09)]">
                                <div class="aspect-[4/3] bg-slate-100">
                                    @if ($artikel->gambar_artikel)
                                        <img src="{{ asset('storage/' . $artikel->gambar_artikel) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
                                    @else
                                        <div class="flex h-full items-center justify-center text-sm font-semibold text-slate-400">Tidak ada gambar</div>
                                    @endif
                                </div>

                                <div class="p-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Artikel</p>
                                    <h3 class="mt-2 text-xl font-black leading-tight text-slate-900">{{ $artikel->judul }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-slate-600 line-clamp-4">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($artikel->konten_artikel), 180) }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section id="kontak" class="relative overflow-hidden bg-[#0f172a] px-6 py-20 text-white lg:px-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.18),transparent_32%),radial-gradient(circle_at_bottom_right,rgba(37,99,235,0.18),transparent_30%)]"></div>

            <div class="relative mx-auto grid max-w-7xl gap-10 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="landing-reveal max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.30em] text-white/55">Kontak</p>
                    <h2 class="mt-4 text-3xl font-black uppercase leading-tight sm:text-5xl">
                        Mau tanya produk, pesan langsung, atau lihat lokasi usaha?
                    </h2>
                    <p class="mt-6 text-base leading-8 text-white/75 sm:text-lg">
                        Hubungi Matrix Jaya lewat kanal yang paling nyaman. Bagian ini sengaja dibuat tetap satu halaman supaya user tidak merasa pindah-pindah context.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="https://wa.me/6281515400001" class="rounded-full bg-[linear-gradient(90deg,#38bdf8_0%,#2563eb_100%)] px-7 py-3 text-sm font-bold uppercase tracking-[0.18em] text-white transition duration-300 hover:-translate-y-1">
                            Hubungi Sekarang
                        </a>
                    </div>
                </div>

                <div class="landing-reveal grid gap-4 sm:grid-cols-3" style="animation-delay: .14s;">
                    <a href="https://maps.app.goo.gl/Lz2xwQD3p16hbf3c6" class="rounded-[1.8rem] border border-white/12 bg-white/8 px-5 py-6 backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:bg-white/12">
                        <img src="{{ asset('images/Place Marker.png') }}" alt="Lokasi" class="h-10 w-10 object-contain">
                        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.24em] text-white/55">Lokasi</p>
                        <h3 class="mt-3 text-lg font-black uppercase">Kunjungi usaha</h3>
                        <p class="mt-3 text-sm leading-7 text-white/75">Buka maps untuk melihat lokasi Matrix Jaya secara langsung.</p>
                    </a>

                    <a href="https://www.instagram.com/matrixjaya?igsh=M2ltaG1hd3Rudmkx" class="rounded-[1.8rem] border border-white/12 bg-white/8 px-5 py-6 backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:bg-white/12">
                        <img src="{{ asset('images/Instagram Circle.png') }}" alt="Instagram" class="h-10 w-10 object-contain">
                        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.24em] text-white/55">Instagram</p>
                        <h3 class="mt-3 text-lg font-black uppercase">Lihat update</h3>
                        <p class="mt-3 text-sm leading-7 text-white/75">Pantau promosi, katalog visual, dan aktivitas UMKM di Instagram.</p>
                    </a>

                    <a href="https://wa.me/6281515400001" class="rounded-[1.8rem] border border-white/12 bg-white/8 px-5 py-6 backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:bg-white/12">
                        <img src="{{ asset('images/Facebook.png') }}" alt="Facebook" class="h-10 w-10 object-contain">
                        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.24em] text-white/55">Kontak</p>
                        <h3 class="mt-3 text-lg font-black uppercase">Hubungi langsung</h3>
                        <p class="mt-3 text-sm leading-7 text-white/75">Arahkan user ke kanal komunikasi yang cepat untuk tanya atau pesan.</p>
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
