<?php

use App\Http\Controllers\Web\AgendaInviteController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\FriendController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\UserController;
use App\Models\FriendRequests;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register-account', [RegisterController::class, 'register'])->name('register account');

Route::post('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify email');

Route::get('/login', function () {
    if (auth()->user()) {
        return redirect('/dashboard');
    }

    return view('login');
})->name('login');

Route::post('/login-account', [LoginController::class, 'login'])->name('login account');

Route::get('/register/input-otp', function () {
    return view('inputOtp');
})->name('input otp');

Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resend otp');

Route::get('/test/invite-code-modal', function () {
    return view('drafts/inviteCodeModal');
});

Route::get('/drafts/inviteDialog', function () {
    return view('drafts/agendaInviteDialog');
});

Route::middleware('auth:web')->group(function () {
    Route::delete('/logout', [UserController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/agenda/add', [DashboardController::class, 'createAgenda'])->name('create agenda');

    Route::get('/agenda/{invite_code}/join', [AgendaInviteController::class, 'agendaInviteDialog'])
            ->name('agenda invite dialog');
    
    Route::patch('/agenda/{id_agenda}/accept-invite', [AgendaInviteController::class, 'acceptAgendaInvite'])
            ->name('accept agenda invite');
    
    Route::patch('/agenda/{id_agenda}/reject-invite', [AgendaInviteController::class, 'rejectAgendaInvite'])
            ->name('reject agenda invite');

    Route::get('/friend/search', [FriendController::class, 'search'])->name('search new friend');

    Route::delete('/friend/requests/reject/{id_request}', [FriendController::class, 'rejectFriendRequest'])
            ->name('reject friend request');

    Route::post('/friend/requests/accept/{id_request}', [FriendController::class, 'acceptFriendRequest'])
            ->name('accept friend request');

    Route::post('/friend/requests/send/{id_target}', [FriendController::class, 'sendFriendRequest'])
            ->name('send friend request');

    Route::get('/friend/list', [FriendController::class, 'friends'])->name('friend list');

    Route::get('/friend/requests', [FriendController::class, 'friendRequest'])->name('friend requests');

    Route::get('/test', function () {
        $mutual_friend_request = FriendRequests::where('id_pengirim', auth()->id())->where('status', 'mutual')->first();
        $data = [
            'id_mutual_request' => $mutual_friend_request->id_request
        ];
        return view('test', ['data' => $data]);
    });
});