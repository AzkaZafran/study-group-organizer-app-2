<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\InvalidOTPCodeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserResendOtpRequest;
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
            
            return redirect()->route('input otp')->with('email', $user->email);
        } catch (InvalidCredentialsException $e) {
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
        } catch (InvalidOTPCodeException $e) {
            return back()->withErrors([
                    'message' => $e->getMessage()
                ])->withInput(
                    $request->only('email')
                );
        }
    }

    public function resendOtp(UserResendOtpRequest $request) {
        $data = $request->validated();

        try {
            $this->userService->requestOtp($data['email']);
            return redirect('/register/input-otp')->withInput();
        } catch (\Exception $e) {
            return redirect('/register/input-otp');
        }
    }
}
