@extends('layouts.normal')
@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="bg-slate-50 p-18 rounded-lg shadow-lg w-140">
            <h1 class="text-4xl font-bold text-center">Reset Password</h1>
            <form action="/sendResetPassword" method="post">
                @csrf
                <input name="password" type="password" placeholder="Password Baru" autocomplete="off" class="w-full focus:ring-2 p-2 mt-5 shadow-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white border rounded-sm active:border-gray-700 px-2 border-gray-400">
                <input name="passwordConfirm" type="password" placeholder="Konfirmasi Password" autocomplete="off" class="w-full focus:ring-2 p-2 mt-3 shadow-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white border rounded-sm active:border-gray-700 px-2 border-gray-400">
                @if ($errors->first())
                    <p class="text-red-500 text-center text-sm mt-1">{{ $errors->first() }}</p>
                
                @endif
                <div class="flex justify-center items-center mt-8">
                    <button type="submit" class="px-10 py-1 hover:cursor-pointer rounded-md font-bold bg-blue-400 hover:bg-blue-600 transition-all duration-300 hover:scale-105 active:scale-95 ">Reset</button>
                </div>
            </form>
        </div>
    </div>
@endsection