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
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user){
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->onlyInput('email');
        }

        $otp = random_int(100000, 999999);
        $user->update([
            'otp' => Hash::make($otp),
            'otp_expired_at' => now()->addMinutes(10),
        ]);
        
        Mail::raw("SnackFlow - Gunakan Kode OTP {$otp} Untuk Ubah Password. Hanya berlaku 10 menit. Jangan berikan kode ini kepada siapa pun.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Reset Password OTP');
        });
        return redirect('/otp')->with('email', $request->email);
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
            return back()->withErrors(['otpExpired' => 'Kode OTP telah kedaluwarsa.'])->onlyInput('otp');
        }

        if (!Hash::check($request->otp, $user->otp)){
            return back()->withErrors(['otp' => 'Kode OTP salah.'])->onlyInput('otp');
        }
        return back()->with('success', 'OTP berhasil diverifikasi.');
    }
}
