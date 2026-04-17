<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\MidtransController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/areas', [MapsController::class, 'searchArea']);
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
