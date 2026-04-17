<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\TransaksiController;

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
    Route::get('/transaksi', [TransaksiController::class, 'adminIndex'])->name('admin.transaksi');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'adminShow'])->name('admin.transaksi.show');
    Route::post('/transaksi/{transaksi}/approve', [TransaksiController::class, 'approveByAdmin'])->name('admin.transaksi.approve');
    Route::post('/transaksi/{transaksi}/reject', [TransaksiController::class, 'rejectByAdmin'])->name('admin.transaksi.reject');
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
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('user.checkout');
        Route::get('/checkout/destination-autocomplete', [CheckoutController::class, 'autocompleteDestination'])->name('user.checkout.destination.autocomplete');
        Route::post('/checkout/rates', [CheckoutController::class, 'rates'])->name('user.checkout.rates');
        Route::post('/checkout/shipping', [CheckoutController::class, 'selectShipping'])->name('user.checkout.shipping');
        Route::post('/checkout/proceed', [CheckoutController::class, 'proceedToPayment'])->name('user.checkout.proceed');
        Route::get('/checkout/payment/{transaksi}', [CheckoutController::class, 'payment'])->name('user.checkout.payment');
        Route::post('/checkout/payment/{transaksi}/refresh-status', [CheckoutController::class, 'refreshPaymentStatus'])->name('user.checkout.payment.refresh-status');
        Route::post('/keranjang', [KatalogController::class, 'tambahKeKeranjang'])->name('user.keranjang.tambah');
        Route::patch('/keranjang/{id}', [KatalogController::class, 'updateJumlahKeranjang'])->name('user.keranjang.update');
        Route::delete('/keranjang/{id}', [KatalogController::class, 'hapusDariKeranjang'])->name('user.keranjang.hapus');
<<<<<<< HEAD
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('user.transaksi');
=======
        Route::get('/transaksi', function(){
            return view('transactions.transaksi');})->name('user.transaksi');
>>>>>>> a0857944c1323bf5d692780605c3767ea29deef2
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
