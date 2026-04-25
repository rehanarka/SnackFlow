@extends('layouts.normal')

@section('content')
    <x-headerLanding/>
            <div class=" relative min-h-[calc(100vh-97px)] w-full bg-top bg-no-repeat" style="background-image: url('{{ asset('images/backgroundLanding.jpg') }}'); background-size: 100% auto;">
                <div class="flex justify-between">
                    <div class="bg-white/55 flex flex-col p-10 w-92 h-fit mt-20 ml-40 rounded-lg">
                        <h1 class="font-serif font-bold text-4xl text-center mb-8">Tentang Kami</h1>
                        <p class="text-justify mb-7">UD. Matrix Jaya, usaha lokal dari Kabupaten Jember yang menghadirkan cemilan sehat, lezat, dan berkualitas untuk keluarga Indonesia.</p>
                        <img class="w-92" src="/images/produk.png" alt="">
                    </div>
                    <div class="bg-[#273356]/90 p-10 w-210 mt-20 h-fit mr-20 rounded-lg">
                        <p class="text-white text-lg font-semibold mb-5 ">UD. Matrix Jaya, usaha lokal dari Kabupaten Jember yang menghadirkan cemilan sehat, lezat, dan berkualitas untuk keluarga Indonesia.</p>
                        <div class="relative">
                            <img src="/images/awan.png" class="h-64 w-full" alt="">
                            <div class="absolute inset-y-0">
                                <div class="flex flex-col">
                                    <div class="inline-flex mt-3 ml-3 gap-3">
                                        <span>
                                            <svg width="20px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM16.0303 8.96967C16.3232 9.26256 16.3232 9.73744 16.0303 10.0303L11.0303 15.0303C10.7374 15.3232 10.2626 15.3232 9.96967 15.0303L7.96967 13.0303C7.67678 12.7374 7.67678 12.2626 7.96967 11.9697C8.26256 11.6768 8.73744 11.6768 9.03033 11.9697L10.5 13.4393L12.7348 11.2045L14.9697 8.96967C15.2626 8.67678 15.7374 8.67678 16.0303 8.96967Z" fill="#1C274C"></path> </g></svg>
                                        </span>
                                        <p class="font-bold">Bahan baku ikan budidaya sendiri <br> <span class="font-normal"> Dengan bahan baku piiihan hasil budidaya sendiri, setiap produk kami terjamin segar, higienis, dan bernilai gizi tinggi.</span></p>
                                    </div>
                                    <div class="inline-flex mt-3 ml-3 gap-3">
                                        <span>
                                            <svg width="20px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM16.0303 8.96967C16.3232 9.26256 16.3232 9.73744 16.0303 10.0303L11.0303 15.0303C10.7374 15.3232 10.2626 15.3232 9.96967 15.0303L7.96967 13.0303C7.67678 12.7374 7.67678 12.2626 7.96967 11.9697C8.26256 11.6768 8.73744 11.6768 9.03033 11.9697L10.5 13.4393L12.7348 11.2045L14.9697 8.96967C15.2626 8.67678 15.7374 8.67678 16.0303 8.96967Z" fill="#1C274C"></path> </g></svg>
                                        </span>
                                        <p class="font-bold">Proses produksi higienis <br> <span class="font-normal"> Kami menerapkan proses produksi yang higienis untuk memastikan kualitas dan keamanan produk yang terjarnin.</span></p>
                                    </div>
                                    <div class="inline-flex mt-3 ml-3 gap-3">
                                        <span>
                                            <svg width="20px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM16.0303 8.96967C16.3232 9.26256 16.3232 9.73744 16.0303 10.0303L11.0303 15.0303C10.7374 15.3232 10.2626 15.3232 9.96967 15.0303L7.96967 13.0303C7.67678 12.7374 7.67678 12.2626 7.96967 11.9697C8.26256 11.6768 8.73744 11.6768 9.03033 11.9697L10.5 13.4393L12.7348 11.2045L14.9697 8.96967C15.2626 8.67678 15.7374 8.67678 16.0303 8.96967Z" fill="#1C274C"></path> </g></svg>
                                        </span>
                                        <p class="font-bold">Dikemas dengan rapi <br> <span class="font-normal">Dikemas dengan aluminium foil berkualitas dan proses packing rapi, produk kami siap dikirim ke seluruh Indo- nesia dengan aman.</span></p>
                                    </div>
                                </div>
                            </div>
                            <h1 class="text-white font-lg font-semibold mt-8">UD. Matrix Jaya hadir sebagai solusi cemilan gurih, renyah, enak, dan sehat dengan harga yang bersahabat untuk semua kalangan.</h1>
                        </div>
                    </div>
                </div>
            </div>
@endsection