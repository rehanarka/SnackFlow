@extends('layouts.normal')

@section('content')
    <div>
        <div class="flex flex-col justify-center items-center h-screen gap-4 ">
            <h1 class="font-bold text-5xl pb-5">LOGIN</h1>
            <input autocomplete="off" type="text" placeholder="Username/Email" class="border border-black rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-black w-96">
            <input autocomplete="off" type="password" placeholder="Password" class="border border-black rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-black w-96">
            <p>Already have an account? <a href="/" class="text-blue-600">Register here!</a></p>
            <button class="rounded-3xl bg-blue-300 w-32 py-2">Sign In</button>
            <a href="/auth/google/redirect" class="flex items-center justify-center bg-white border border-gray-300 rounded-lg px-4 py-2 mt-4 hover:bg-gray-100 shadow">
                <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png" class="w-6 h-6 mr-2" alt="Google Logo">
                <span class="font-medium text-gray-700">Sign in with Google</span>
            </a>
        </div>
    </div>
@endsection