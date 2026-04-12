<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Request;
use UserService;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register(UserRegisterRequest $request) {
        $data = $request->validated();

        try {
            $user = $this->userService->register($data);
            return view('test', ['$data' => $data]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
