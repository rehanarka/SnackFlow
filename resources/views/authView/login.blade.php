@extends('layouts.normal')

@section('content')
    <div class="">
    <img src="/images/Background-opt.jpg" alt="Background" class="absolute top-0 left-0 w-full h-full object-cover opacity-50 -z-10" width="1400" height="933" fetchpriority="high" decoding="async">
    <img src="/images/Logo-opt.png" alt="Logo" class="absolute -left-12 -top-15 w-[18rem]" width="320" height="213" fetchpriority="high" decoding="async">
        <div class="flex flex-col justify-center items-center h-screen gap-4">
    <img src="/images/Logo-opt.png" alt="Logo" class="absolute top-[45%] left-1/2 transform -translate-x-1/2 -translate-y-1/2 -z-10 w-3xl opacity-80" width="320" height="213" decoding="async">
            <h1 class="font-bold text-5xl  pb-5">LOGIN</h1>
            <form action="/login" method="post">
                @csrf
                <div class="flex flex-col">
                <input autocomplete="off" type="email" placeholder="email@example.com" value="{{ old('email') }}" class="border border-black rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-black w-96" name="email">
                <input autocomplete="off" type="password" placeholder="Password" class="border mt-4 border-black rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-black w-96" name="password">
                <a href="/send-email" class="text-start text-blue-600 hover:text-blue-900 active:text-blue-600">Lupa Password?</a>
                <div class="flex justify-center items-center">
                    <button class="text-center transform hover:transition-transform duration-150 hover:scale-110 hover:bg-blue-400 active:transition-transform active:scale-90 cursor-pointer rounded-3xl bg-blue-300 w-32 py-2 mt-2">Sign In</button>
                </div>
                <p class="text-center mt-2">Don't have an account? <a href="/registrasi" class="text-blue-600 mt-2 hover:text-blue-900 active:text-blue-600">Register here!</a></p>
                @if($errors->first())
                    <div  class="text-center text-red-400 mt-2 bg-red-100/80 py-2 rounded-lg border border-white">
                        {{ $errors->first() }}
                    </div>
                @elseif(session('success'))
                    <div class="text-center text-green-400 mt-2 bg-green-100/80 py-2 rounded-lg border border-white">
                        {{ session('success') }}
                    </div>
                @endif
                </div>
            </form>
        </div>
    </div>
@endsection
