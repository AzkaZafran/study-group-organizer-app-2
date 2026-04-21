<?php

use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('test');
});

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register-account', [RegisterController::class, 'register'])->name('register account');

Route::post('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify email');

Route::get('/login', function () {
    return view('test');
})->name('login');

Route::post('/login-account', [LoginController::class, 'login']);

Route::get('/register/input-otp', function () {
    return view('test');
})->name('input otp');

Route::post('/resend-otp', [RegisterController::class, 'resendOtp']);

Route::get('/dashboard', function () {
    return view('test');
});

Route::delete('/logout', [UserController::class, 'logout']);