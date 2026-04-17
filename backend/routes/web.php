<?php

use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

Route::post('/register', [UserController::class, 'register'])->name('register');

Route::post('/verify-email', [UserController::class, 'verifyEmail'])->name('verify email');

Route::get('/login', function () {
    return view('test');
})->name('login');