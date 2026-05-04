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
