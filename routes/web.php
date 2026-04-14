<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\KatalogController;

Route::get('/', function () {
    return view('registrasi');
});
Route::get('/login', function(){
    return view('authView.login');})->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
    
Route::get('/auth/google', [AuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'callback']);

    
Route::middleware(['auth', 'RoleLogin'])->prefix('admin')->group(function(){
    Route::get('/dashboard', function(){
        return view('dashboard.dashboardAdmin');
        })->name('admin.dashboard');
    Route::get('/profile', function(){
        return view('profile.profile');
        })->name('admin.profile');
    Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/katalog/tambah', [KatalogController::class, 'tambahProduk'])->name('admin.katalog.tambah');
    Route::get('/katalog', [KatalogController::class, 'viewKatalog'])->name('admin.katalog');
    Route::put('/katalog/update/{id}', [KatalogController::class, 'updateProduk'])->name('admin.katalog.update');
    Route::delete('/katalog/hapus/{id}', [KatalogController::class, 'hapusProduk'])->name('admin.katalog.hapus');
});
Route::middleware(['auth'])->prefix('user')->group(function(){
        Route::get('/dashboard', function(){
            return view('dashboard.dashboardUser');
        })->name('user.dashboard');
        Route::get('/profile', function(){
            return view('profile.profile');
        })->name('user.profile');
        Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('user.profile.update');
        Route::get('/katalog', [KatalogController::class, 'viewKatalogUser'])->name('user.katalog');
    });

Route::get('/send-email', function(){return view('resetPassword.sendEmail');});
Route::post('/send-email', [ResetPasswordController::class, 'sendOtp']);

Route::middleware('otp.access')->group(function(){
    Route::get('/otp', [ResetPasswordController::class, 'showOtp'])->name('otp.page');
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifikasiOtp']);
    Route::get('/resetPassword', function(){
        return view('resetPassword.inputPassword');
    });
    Route::post('/sendResetPassword', [ResetPasswordController::class, 'resetPassword']);
});
