<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\KatalogProdukController;
use App\Http\Controllers\ReviewProdukController;
use App\Http\Controllers\TransaksiController;
use App\Models\Artikel;
use App\Models\KatalogProduk;

Route::get('/', function () {
    try {
        $produkLanding = KatalogProduk::query()
            ->where('stok', '>', 0)
            ->orderByDesc('id')
            ->limit(6)
            ->get();
    } catch (\Throwable) {
        $produkLanding = collect();
    }

    try {
        $artikelLanding = Artikel::query()
            ->orderByDesc('id')
            ->limit(3)
            ->get();
    } catch (\Throwable) {
        $artikelLanding = collect();
    }

    return view('landingPage', compact('produkLanding', 'artikelLanding'));
})->name('landingPage');

Route::get('/tentangKami', function () {
    return redirect('/#tentang');
});

Route::get('/registrasi', function () {
    return view('authView.FormRegister');
});


Route::get('/login', function(){
    return view('authView.FormLogin');})->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');

    
Route::middleware(['auth', 'RoleLogin'])->prefix('admin')->group(function(){
    Route::get('/profile', function(){return view('profile.HalProfil');})->name('admin.profile');
    Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/katalog/tambah', [KatalogProdukController::class, 'tambahProduk'])->name('admin.katalog.tambah');
    Route::get('/katalog', [KatalogProdukController::class, 'viewKatalog'])->name('admin.katalog');
    Route::put('/katalog/update/{id}', [KatalogProdukController::class, 'updateProduk'])->name('admin.katalog.update');
    Route::delete('/katalog/hapus/{id}', [KatalogProdukController::class, 'hapusProduk'])->name('admin.katalog.hapus');
    Route::get('/katalog/{produk}/review', [ReviewProdukController::class, 'adminIndex'])->name('admin.katalog.review');
    Route::get('/artikel', [ArtikelController::class, 'index'])->name('admin.artikel');
    Route::get('/artikel/tambah', [ArtikelController::class, 'create'])->name('admin.artikel.create');
    Route::post('/artikel', [ArtikelController::class, 'store'])->name('admin.artikel.store');
    Route::get('/artikel/{artikel}', [ArtikelController::class, 'show'])->name('admin.artikel.show');
    Route::get('/artikel/{artikel}/edit', [ArtikelController::class, 'edit'])->name('admin.artikel.edit');
    Route::put('/artikel/{artikel}', [ArtikelController::class, 'update'])->name('admin.artikel.update');
    Route::delete('/artikel/{artikel}', [ArtikelController::class, 'destroy'])->name('admin.artikel.destroy');
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('admin.laporan.penjualan');
    Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('admin.laporan.keuangan');
    Route::post('/laporan/keuangan/pengeluaran', [LaporanController::class, 'storePengeluaran'])->name('admin.laporan.keuangan.pengeluaran.store');
    Route::put('/laporan/keuangan/pengeluaran/{pengeluaran}', [LaporanController::class, 'pengeluaranUpdate'])->name('admin.laporan.keuangan.pengeluaran.update');
    Route::delete('/laporan/keuangan/pengeluaran/{pengeluaran}', [LaporanController::class, 'pengeluaranDestroy'])->name('admin.laporan.keuangan.pengeluaran.destroy');
    Route::get('/transaksi', [TransaksiController::class, 'adminIndex'])->name('admin.transaksi');
    Route::post('/transaksi/offline', [TransaksiController::class, 'storeOffline'])->name('admin.transaksi.store-offline');
    Route::put('/transaksi/offline/{transaksi}', [TransaksiController::class, 'updateOffline'])->name('admin.transaksi.update-offline');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'adminShow'])->name('admin.transaksi.show');
    Route::post('/transaksi/{transaksi}/approve', [TransaksiController::class, 'approveByAdmin'])->name('admin.transaksi.approve');
    Route::post('/transaksi/{transaksi}/reject', [TransaksiController::class, 'rejectByAdmin'])->name('admin.transaksi.reject');
});
Route::middleware(['auth'])->prefix('user')->group(function(){
        Route::get('/profile', function(){return view('profile.HalProfil');})->name('user.profile');
        Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('user.profile.update');
        Route::get('/katalog', [KatalogProdukController::class, 'viewKatalogUser'])->name('user.katalog');
        Route::get('/katalog/{produk}/review', [ReviewProdukController::class, 'userProductIndex'])->name('user.katalog.review');
        Route::get('/artikel', [ArtikelController::class, 'index'])->name('user.artikel');
        Route::get('/artikel/{artikel}', [ArtikelController::class, 'show'])->name('user.artikel.show');
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('user.checkout');
        Route::get('/checkout/destination-autocomplete', [CheckoutController::class, 'autocompleteDestination'])->name('user.checkout.destination.autocomplete');
        Route::post('/checkout/rates', [CheckoutController::class, 'rates'])->name('user.checkout.rates');
        Route::post('/checkout/proceed', [CheckoutController::class, 'proceedToPayment'])->name('user.checkout.proceed');
        Route::get('/checkout/payment/{transaksi}', [CheckoutController::class, 'payment'])->name('user.checkout.payment');
        Route::post('/checkout/payment/{transaksi}/refresh-status', [CheckoutController::class, 'refreshPaymentStatus'])->name('user.checkout.payment.refresh-status');
        Route::post('/transaksi/{transaksi}/received', [TransaksiController::class, 'markAsReceived'])->name('user.transaksi.received');
        Route::get('/transaksi/{transaksi}/review', [ReviewProdukController::class, 'create'])->name('user.transaksi.review');
        Route::post('/transaksi/{transaksi}/review', [ReviewProdukController::class, 'store'])->name('user.transaksi.review.store');
        Route::delete('/transaksi/{transaksi}/review/{review}', [ReviewProdukController::class, 'destroy'])->name('user.transaksi.review.destroy');
        Route::post('/keranjang', [KatalogProdukController::class, 'tambahKeKeranjang'])->name('user.keranjang.tambah');
        Route::patch('/keranjang/{id}', [KatalogProdukController::class, 'updateJumlahKeranjang'])->name('user.keranjang.update');
        Route::delete('/keranjang/{id}', [KatalogProdukController::class, 'hapusDariKeranjang'])->name('user.keranjang.hapus');
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('user.transaksi');
    });

Route::get('/send-email', function(){return view('resetPassword.FormResetPassword');});
Route::post('/send-email', [ResetPasswordController::class, 'sendOtp']);

Route::middleware('otp.access')->group(function(){
    Route::get('/otp', [ResetPasswordController::class, 'showOtp'])->name('otp.page');
    Route::post('/verify-otp', [ResetPasswordController::class, 'verifikasiOtp']);
    Route::get('/resetPassword', function(){
        return view('resetPassword.FormPasswordBaru');
    });
    Route::post('/sendResetPassword', [ResetPasswordController::class, 'resetPassword']);
});
