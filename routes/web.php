<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('registrasi');
});
Route::get('/login', function(){
    return view('authView.login');
});

route::get('/maps', function(){
    return view('transactions.checkout');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auth/google', [AuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'callback']);

Route::get('/dashboard', function(){
    return view('dashboard.dashboardUser');
});

Route::get('/send-email', function(){
    return view('resetPassword.sendEmail');
});

Route::get('/otp', function(){
    return view('resetPassword.sendOtp');
})->name('otp.page');

Route::get('/otp', [ResetPasswordController::class, 'showOtp'])->name('otp.page');

Route::post('/send-email', [ResetPasswordController::class, 'sendOtp']);
Route::post('/verify-otp', [ResetPasswordController::class, 'verifikasiOtp']);