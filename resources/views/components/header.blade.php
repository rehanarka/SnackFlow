@php
    $user = auth()->user();
@endphp 

<div class="relative w-full h-[122px] rounded-2xl overflow-hidden shadow-xl">
    <div class="absolute inset-0">
        <img src="/images/header.png" class="w-full h-full shadow-2xl"alt="">
    </div>
    <div class="relative z-10 flex items-center justify-between h-full px-6">
        
        <div class="flex flex-col">
            <h1 class="text-2xl font-bold text-black">Profile</h1>
            <p class="text-black text-sm">Kelola informasi akun anda</p>
        </div>

        <div>
            <img 
                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : '/images/avatar-default.png' }}" 
                alt="Avatar" 
                class="w-[60px] h-[60px] object-cover rounded-full  shadow hover:cursor-pointer"
            >
        </div>

    </div>

</div>