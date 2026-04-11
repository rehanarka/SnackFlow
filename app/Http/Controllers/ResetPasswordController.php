<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ],
        [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user){
            return back()->withErrors(['email' => 'Akun anda tidak ditemukan.'])->onlyInput('email');
        }
        
        $otp = random_int(100000, 999999);
        $user->update([
            'otp' => Hash::make($otp),
            'otp_expired_at' => now()->addMinutes(10),
        ]);
        
        $sisaWaktu = 0;
        if ($user && $user->otp_expired_at){
            $sisaWaktu = $user->otp_expired_at->diffInSeconds(now());
            $sisaWaktu = max(0, $sisaWaktu);
        }

        Mail::raw("SnackFlow - Gunakan Kode OTP {$otp} Untuk Ubah Password. Hanya berlaku 10 menit. Jangan berikan kode ini kepada siapa pun.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Reset Password OTP');
        });
        session(['email' => $request->email]);
        return redirect()->route('otp.page');
    }
    public function verifikasiOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ],
        [
            'digits' => 'Kode OTP harus terdiri dari 6 digit.',
            'required' => 'Kode OTP wajib diisi.',
        ]);
        
        $user = User::where('email', $request->email)->first();
        if ($user->otp_expired_at->isPast()){
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa.'])
            ->withInput($request->only('email'));
        }

        if (!Hash::check($request->otp, $user->otp)){
            return back()->withErrors(['otp' => 'Kode OTP salah.'])
            ->withInput($request->only('email'));
        }
        $user->otp_expired_at = now();
        session()->forget('email');
        $user->otp = null;
        $user->save();
        return redirect('/resetPassword');
    }

    public function showOtp()
    {
        $email = session('email');
        if (!$email){
            return redirect('/send-email');
        }
        $user = User::where('email', $email)->first();
        $waktuSisa = 0;
        if ($user && $user->otp_expired_at){
            $waktuSisa = now()->diffInSeconds($user->otp_expired_at, false);
            $waktuSisa = max(0, (int) $waktuSisa);
        }
        return view('resetPassword.SendOtp', ['countdown' => $waktuSisa]);
    }
}
