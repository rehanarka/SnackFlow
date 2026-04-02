@extends('layouts.normal')

@section('content')
    <div class="">
        <img src="/images/Background.jpeg" alt="Background" class="absolute top-0 left-0 w-full h-full object-cover opacity-50 -z-10">
        <img src="/images/Logo.png" alt="Logo" class="absolute -left-12 -top-15 w-[18rem]">
        <div class="flex flex-col justify-center items-center h-screen gap-4">
            <img src="/images/Logo.png" alt="Logo" class="absolute top-[45%] left-1/2 transform -translate-x-1/2 -translate-y-1/2 -z-10 w-3xl opacity-80">
            <h1 class="font-bold text-5xl pb-5">LOGIN</h1>
            <form action="/login" method="post">
                @csrf
                <div class="flex flex-col items-center justify-center">
                <input autocomplete="off" type="text" placeholder="Username/Email" class="border border-black rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-black w-96" name="username">
                <input autocomplete="off" type="password" placeholder="Password" class="border mt-4 border-black rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-black w-96" name="password">
                <p>Doesn't have an account? <a href="/register" class="text-blue-600 mt-2">Register here!</a></p>
                <button class="transform hover:transition-transform duration-150 hover:scale-110 hover:bg-blue-400 active:transition-transform active:scale-90 cursor-pointer rounded-3xl bg-blue-300 w-32 py-2 mt-7">Sign In</button>
                @if($errors->first())
                    <div class="text-center text-red-400 mt-2">
                        {{ $errors->first() }}
                    </div>
                @elseif(session('success'))
                    <div class="text-center text-green-400 mt-2">
                        {{ session('success') }}
                    </div>
                @endif
                </div>
            </form>
            <a href="/auth/google/redirect" class="flex items-center justify-center bg-white border border-gray-300 rounded-lg px-4 py-2 mt-4 hover:scale-110 hover:transition-transform transform duration-150 active:scale-90 hover:bg-gray-100 shadow">
                <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png" class="w-6 h-6 mr-2" alt="Google Logo">
                <span class="font-medium text-gray-700">Sign in with Google</span>
            </a>
        </div>
    </div>
@endsection