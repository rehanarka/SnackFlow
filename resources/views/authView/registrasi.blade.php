@extends('layouts.normal')

@section('content')
    <style>
        @keyframes authRegisterDrift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(0, -18px, 0) scale(1.05); }
        }

        @keyframes authRegisterReveal {
            from {
                opacity: 0;
                transform: translate3d(0, 26px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .auth-register-drift {
            animation: authRegisterDrift 7s ease-in-out infinite;
        }

        .auth-register-reveal {
            animation: authRegisterReveal .75s ease-out both;
        }
    </style>

    <div class="relative min-h-screen overflow-hidden bg-sky-300/25">
        <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(240,249,255,0.9)_0%,rgba(255,248,235,0.88)_55%,rgba(255,255,255,0.84)_100%)]"></div>
        <div class="auth-register-drift absolute left-0 top-16 h-80 w-80 rounded-full bg-sky-300/25 blur-3xl"></div>

        <div class="relative z-10 grid min-h-screen lg:grid-cols-[0.94fr_1.06fr] mt-8">
            <section class="order-2 flex items-center px-6 pb-12 pt-2 sm:px-10 lg:order-1 lg:px-12">
                <div class="auth-register-reveal mx-auto w-full max-w-xl rounded-[2rem] border border-white/55 bg-white/82 p-6 shadow-[0_25px_90px_rgba(15,23,42,0.14)] backdrop-blur-xl sm:p-8">
                    <div class="rounded-[1.6rem] border border-slate-100 bg-white/90 p-6 shadow-inner shadow-slate-100 sm:p-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.30em] text-slate-500">Registrasi</p>
                        <h2 class="mt-3 text-3xl font-black uppercase text-slate-900">Buat akun baru</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500">
                            Daftarkan akunmu untuk mulai menjelajahi katalog, melakukan checkout, dan memantau transaksi di SnackFlow.
                        </p>

                        <form action="/register" method="post" class="mt-8 space-y-5">
                            @csrf

                            <div>
                                <label for="nama_lengkap" class="mb-2 block text-xs font-semibold uppercase tracking-[0.20em] text-slate-500">Nama Lengkap</label>
                                <input id="nama_lengkap" autocomplete="off" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap" class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-900 outline-none transition duration-300 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.20em] text-slate-500">Email</label>
                                <input id="email" autocomplete="off" type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-900 outline-none transition duration-300 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-[0.20em] text-slate-500">Password</label>
                                <input id="password" autocomplete="off" type="password" name="password" placeholder="Minimal 6 karakter" class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-900 outline-none transition duration-300 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                            </div>

                            @if($errors->first())
                                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                                    {{ $errors->first() }}
                                </div>
                            @elseif(session('success'))
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <button type="submit" class="w-full rounded-full bg-[linear-gradient(90deg,#0284c7_0%,#0f172a_100%)] px-5 py-3 text-sm font-bold uppercase tracking-[0.20em] text-white shadow-[0_18px_35px_rgba(2,132,199,0.25)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_40px_rgba(2,132,199,0.35)]">
                                Create Account
                            </button>

                            <p class="text-center text-sm text-slate-500">
                                Sudah punya akun?
                                <a href="/login" class="font-semibold text-sky-700 transition duration-300 hover:text-sky-900">Masuk di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </section>

            <section class="order-1 flex items-center px-6 py-12 sm:px-10 lg:order-2 lg:px-16">
                <div class="auth-register-reveal mx-auto max-w-2xl lg:mx-0" style="animation-delay: .12s;">
                    <a href="/" class="inline-flex items-center gap-3 rounded-full bg-white/70 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-slate-700 shadow-sm backdrop-blur-md transition duration-300 hover:-translate-y-0.5 hover:bg-white">
                        <span>Kembali ke Landing Page</span>
                    </a>

                    <h1 class="mt-8 text-4xl font-black uppercase leading-[0.95] text-slate-900 sm:text-5xl lg:text-6xl">
                        Buka pintu ke katalog dan alur pesanan SnackFlow
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-8 text-slate-600 sm:text-lg">
                        Registrasi ini dibuat sederhana, tapi hasil akhirnya menghubungkan user ke katalog, checkout, transaksi, dan pengalaman belanja yang lebih tertib.
                    </p>
            </section>
        </div>
    </div>
@endsection
