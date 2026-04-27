<?php

use App\Http\Controllers\Web\FriendController;
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
    return view('login');
})->name('login');

Route::post('/login-account', [LoginController::class, 'login'])->name('login account');

Route::get('/register/input-otp', function () {
    return view('inputOtp');
})->name('input otp');

Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resend otp');

Route::get('/dashboard', function () {
    return view('test');
})->name('dashboard');

Route::middleware('auth:web')->group(function () {
    Route::delete('/logout', [UserController::class, 'logout']);

    Route::get('/friend/search', [FriendController::class, 'search'])->name('search new friend');

    Route::delete('/friend/requests/reject/{id_request}', [FriendController::class, 'rejectFriendRequest'])
            ->name('reject friend request');

    Route::post('/friend/requests/accept/{id_pengirim}', [FriendController::class, 'acceptFriendRequest'])
            ->name('accept friend request');

    Route::get('/friend/requests', [FriendController::class, 'friendRequest'])->name('friend requests');
});