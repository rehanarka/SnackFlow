@extends('layouts.normal')

@section('content')
    <style>
        @keyframes authDrift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(0, -20px, 0) scale(1.04); }
        }

        @keyframes authReveal {
            from {
                opacity: 0;
                transform: translate3d(0, 26px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .auth-drift {
            animation: authDrift 7s ease-in-out infinite;
        }

        .auth-reveal {
            animation: authReveal .75s ease-out both;
        }
    </style>

    <div class="relative min-h-screen overflow-hidden bg-sky-300/25">
        <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(255,248,235,0.92)_0%,rgba(232,242,255,0.78)_52%,rgba(255,255,255,0.86)_100%)]"></div>
        <div class="auth-drift absolute bottom-10 right-8 h-72 w-72 rounded-full bg-sky-300/25 blur-3xl" style="animation-delay: -2.5s;"></div>

        <div class="relative z-10 grid min-h-screen lg:grid-cols-[1fr_0.95fr]">
            <section class="flex items-center px-6 py-12 sm:px-10 lg:px-16">
                <div class="auth-reveal mx-auto max-w-2xl lg:mx-0">
                    <a href="/" class="inline-flex items-center gap-3 rounded-full bg-white/70 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-slate-700 shadow-sm backdrop-blur-md transition duration-300 hover:-translate-y-0.5 hover:bg-white">
                        <span>Kembali ke Landing Page</span>
                    </a>

                    <p class="mt-8 text-xs font-semibold uppercase tracking-[0.34em] text-slate-500">SnackFlow Access</p>
                    <h1 class="mt-4 text-4xl font-black uppercase leading-[0.95] text-slate-900 sm:text-5xl lg:text-6xl">
                        Masuk dan lanjutkan pengelolaan pesananmu
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-slate-600 sm:text-lg">
                        Gunakan akunmu untuk membuka katalog, memantau transaksi, dan melanjutkan proses checkout dengan tampilan yang lebih tertata.
                    </p>
                 </div>
            </section>

            <section class="flex items-center px-6 pb-12 pt-2 sm:px-10 lg:px-12">
                <div class="auth-reveal mx-auto w-full max-w-xl rounded-[2rem] border border-white/55 bg-white/80 p-6 shadow-[0_25px_90px_rgba(15,23,42,0.15)] backdrop-blur-xl sm:p-8" style="animation-delay: .15s;">
                    <div class="rounded-[1.6rem] border border-slate-100 bg-white/90 p-6 shadow-inner shadow-slate-100 sm:p-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.30em] text-slate-500">Login</p>
                        <h2 class="mt-3 text-3xl font-black uppercase text-slate-900">Masuk ke akunmu</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500">
                            Isi email dan password untuk membuka dashboard produk serta melanjutkan aktivitasmu di SnackFlow.
                        </p>

                        <form action="/login" method="post" class="mt-8 space-y-5">
                            @csrf

                            <div>
                                <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.20em] text-slate-500">Email</label>
                                <input id="email" autocomplete="off" type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-900 outline-none transition duration-300 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between gap-4">
                                    <label for="password" class="block text-xs font-semibold uppercase tracking-[0.20em] text-slate-500">Password</label>
                                    <a href="/send-email" class="text-xs font-semibold uppercase tracking-[0.16em] text-sky-600 transition duration-300 hover:text-sky-800">
                                        Lupa Password?
                                    </a>
                                </div>
                                <input id="password" autocomplete="off" type="password" name="password" placeholder="Masukkan password" class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-900 outline-none transition duration-300 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
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

                            <button type="submit" class="w-full rounded-full bg-[linear-gradient(90deg,#0f172a_0%,#1d4ed8_100%)] px-5 py-3 text-sm font-bold uppercase tracking-[0.20em] text-white shadow-[0_18px_35px_rgba(29,78,216,0.28)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_40px_rgba(29,78,216,0.35)]">
                                Login
                            </button>

                            <p class="text-center text-sm text-slate-500">
                                Belum punya akun?
                                <a href="/registrasi" class="font-semibold text-sky-700 transition duration-300 hover:text-sky-900">Daftar di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
