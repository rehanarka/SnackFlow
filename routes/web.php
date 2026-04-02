<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('registrasi');
});
Route::get('/login', function(){
    return view('authView.login');
});

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);