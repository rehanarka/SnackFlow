@extends('layouts.normal')
@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="w-96 h-fit bg-slate-50 border-2 border-gray-500 shadow-2xl p-6 pb-10">
            <div class="px-4">
                <h1 class="text-4xl text-center font-bold">Reset Password</h1>
                <p class="text-gray-500 text-center text-sm">Masukan email akun anda yang ingin di reset</p>
                <form action="/send-email" method="post">
                    @csrf
                <div>
                    <label for="input-6" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mt-9">Email</label>
                    <div class="relative mt-2">
                        <input autocomplete="off" type="email" id="email" name="email" class="block w-full h-10 pl-8 pr-3 mt-1 text-sm text-gray-700 border focus:outline-none focus:ring-2 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="email@example.com"/>
                        <span class="absolute inset-y-0 left-0 flex items-center justify-center ml-2"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 text-blue-400 pointer-events-none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></span>
                    </div>
                </div>
                @if ($errors->has('email'))
                    <p class="text-red-500 text-center text-sm mt-3">{{ $errors->first('email') }}</p>
                @endif
                    <div class="flex justify-center items-center">
                        <button  type="submit" class="bg-blue-500 hover:cursor-pointer transition duration-300 hover:scale-110 hover:bg-blue-700 active:scale-90 active:bg-blue-500 text-white p-2 rounded mt-10"> Kirim Link Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection