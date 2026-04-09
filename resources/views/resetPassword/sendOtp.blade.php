@extends('layouts.normal')

@section('content')
<div class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Verify OTP</h1>
        <p class="mb-6">
            Enter the OTP sent to your email:
            <b>{{ session('email') }}</b>
        </p>

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="#" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') }}">

            <div class="mb-4">
                <input type="text" maxlength="6" name="otp" placeholder="Enter OTP" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-300">Verify OTP</button>
        </form>
        @error('otpExpired')
            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
        @enderror
        @error('otpInvalid')
            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
        @enderror
        <p class="text-sm text-gray-500 mt-4">
            OTP expires in <span id="countdown">{{ $countdown ?? 90 }}</span> seconds.
        </p>
    </div>
</div>

<script>
    let countdown = {{ $countdown ?? 800 }};
    let countdownElem = document.getElementById('countdown');

    function updateCountdown() {
        if (countdown > 0) {
            countdown--;
            countdownElem.textContent = countdown;
            setTimeout(updateCountdown, 1000);
        }
    }

    updateCountdown();
</script>
@endsection