<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function logout(Request $request) {
        try {
            $this->userService->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login')->withErrors([
                    'message' => 'Pengguna harus terautentikasi.'
                ]),
                default => redirect('/login')->withErrors([
                    'message' => 'Something went wrong.'
                ]),
            };
        }
    }
}
