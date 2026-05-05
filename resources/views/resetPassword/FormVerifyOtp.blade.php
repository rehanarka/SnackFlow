@extends('layouts.normal')

@section('content')
<div class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-slate-50 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Verify OTP</h1>
        <p class="mb-6">
            Masukan kode OTP yang telah dikirim ke email Anda:
            <b>{{ session('email') }}</b>
        </p>

        <form action="/verify-otp" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', session('email')) }}">
            <div class="mb-4">
                <input type="text" maxlength="6" name="otp" value="{{ old('otp') }}" placeholder="Masukan OTP" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit" class="w-full hover:cursor-pointer hover:scale-110 active:scale-90 bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 active:bg-blue-500">Verify OTP</button>
        </form>
        @if ($errors->first())
            <p class="text-red-500 text-center text-sm mt-4">{{ $errors->first() }}</p>
        @endif
        <p class="text-sm text-gray-500 mt-4">
            OTP expires in <span id="countdown">{{ session('countdown') ?? 600 }}</span> seconds.
        </p>
    </div>
</div>

<script>
    let countdown = {{ $countdown ?? 600 }};
    let countdownElem = document.getElementById('countdown');

    function formatTime(seconds) {
        let minutes = Math.floor(seconds / 60);
        let secs = seconds % 60;

        return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    function updateCountdown() {
        if (countdown >= 0) {
            countdownElem.textContent = formatTime(countdown);
            countdown--;
            setTimeout(updateCountdown, 1000);
        } else {
            countdownElem.textContent = "00:00";
        }
    }

    updateCountdown();
</script>
@endsection