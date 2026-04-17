<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserVerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
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
            return back()->withErrors([
                'message' => $e->getMessage()
            ]);
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
            };
        }
    }
}
