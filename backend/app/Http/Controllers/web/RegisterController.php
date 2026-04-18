<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserVerifyEmailRequest;
use App\Services\UserService;

class RegisterController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register(UserRegisterRequest $request) {
        $data = $request->validated();

        try {
            $user = $this->userService->register($data['username'], $data['email'], $data['password']);
            $this->userService->requestOtp($data['email']);
            $data = collect([
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email
            ]);
            return view('test', ['data' => $data]);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USERNAME_ALREADY_EXIST' => back()->withErrors([
                    'message' => 'username sudah dipakai'
                ]),
                'EMAIL_ALREADY_EXIST' => back()->withErrors([
                    'message' => 'email sudah dipakai'
                ]),
                default => back()->withErrors([
                    'message' => 'something went wrong'
                ])
            };
        }
    }

    public function verifyEmail(UserVerifyEmailRequest $request) {
        $data = $request->validated();

        try {
            $this->userService->verifyEmail($data['email'], $data['otp_code']);
            return redirect('/login');
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'INVALID_OTP' => back()->withErrors([
                    'message' => 'Kode OTP tidak valid. Coba lagi atau kirim ulang kode.'
                ])->withInput(
                    $request->only('email')
                ),
                'EXPIRED_OTP' => back()->withErrors([
                    'message' => 'Kode OTP sudah tidak berlaku. Silakan kirim ulang kode untuk mendapatkan OTP baru.'
                ])->withInput(
                    $request->only('email')
                ),
                default => back()->withErrors([
                    'message' => 'something went wrong'
                ])
            };
        }
    }
}
