<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\InvalidCredentialsException;
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
        } catch (InvalidCredentialsException $e) {
            return back()->withErrors([
                    'message' => $e->getMessage()
                ]);
        }
    }
}
