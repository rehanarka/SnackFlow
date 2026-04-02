<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('registrasi');
});
Route::get('/login', function(){
    return view('authView.login');
});