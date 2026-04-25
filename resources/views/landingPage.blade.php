@extends('layouts.normal')

@section('content')
   <div class="min-h-screen w-full">
    <x-headerLanding/>
        <div class="relative min-h-[calc(100vh-97px)] w-full bg-top bg-no-repeat" style="background-image: url('{{ asset('images/backgroundLanding.jpg') }}'); background-size: 100% auto;">
            <div class="flex justify-between">
                <div class="flex flex-col items-center justify-center ml-20 mt-50">
                    <h1 class="text-5xl font-bold text-white">SELAMAT DATANG DI</h1>
                    <img class="-mt-40 " src="/images/logoBersih.png" alt="Logo SnackFlow">
                </div>
                <div class="bg-white/55 rounded-lg w-160 h-fit mt-30 border border-white mr-50 p-12 ngambang shadow-white shadow-2xl">
                    <h1 class="text-white text-[18px] text-justify font-semibold">Matrix Jaya merupakan UMKM yang bergerak di bidang kuliner (makanan ringan). UMKM Matrix Jaya memproduksi aneka pruduk berbahan dasar ikan lele seperti : Abon Lele, Sumpia Ikat, Widaran dll. Selain itu juga memproduksi Keripik Tape & Keripik Buah</h1>
                    <div class="flex mt-10 justify-center items-center gap-10">
                        <a class="bg-[#9DBBFB] text-lg rounded-2xl px-18 py-2 font-semibold border-white border hover:-translate-y-1 hover:shadow-white hover:shadow-md hover:bg-blue-400 transition duration-300" href="/login">Login</a>
                        <a class="bg-black/70 border text-lg rounded-2xl px-18 py-2 text-white font-semibold border-white hover:-translate-y-1 hover:shadow-white hover:shadow-md transition duration-300 hover:bg-gray-600" href="/register">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
