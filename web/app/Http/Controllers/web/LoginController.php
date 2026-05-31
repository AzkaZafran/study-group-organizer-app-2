<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Services\UserService;

class LoginController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function login(UserLoginRequest $request) {
        $data = $request->validated();

        try {
            $this->userService->login($data['username'], $data['password']);
            return redirect('/dashboard');
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USERNAME_OR_PASSWORD_WRONG' => back()->withErrors([
                    'message' => 'Username atau password salah.'
                ]),
                default => back()->withErrors([
                    'message' => 'Something went wrong.'
                ]),
            };
        }
    }
}
