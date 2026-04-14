<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
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
}
