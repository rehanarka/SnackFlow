<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('registrasi');
});
Route::get('/login', function(){
    return view('authView.login');})->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
    
Route::get('/auth/google', [AuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'callback']);

    
Route::middleware('auth')->group(function(){
    Route::get('/dashboard', function(){
        return view('dashboard.dashboardUser');
        });
    });

Route::get('/send-email', function(){
    return view('resetPassword.sendEmail');
});
Route::post('/send-email', [ResetPasswordController::class, 'sendOtp']);

Route::middleware('otp.access')->group(function(){
    Route::get('/otp', [ResetPasswordController::class, 'showOtp'])->name('otp.page');
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifikasiOtp']);
    Route::get('/resetPassword', function(){
        return view('resetPassword.inputPassword');
    });
    Route::post('/sendResetPassword', [ResetPasswordController::class, 'resetPassword']);
});
