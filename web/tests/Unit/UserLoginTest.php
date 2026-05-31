<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserLoginTest extends TestCase {
    public function testLoginSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('test'),
            'is_verified' => true
        ];

        User::create($data);

        $userService = new UserService();

        $userService->login('azkazafran78', 'test');

        $this->assertAuthenticated();
    }

    public function testLoginWithUnverifiedEmail() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('test')
        ];

        User::create($data);

        $userService = new UserService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USERNAME_OR_PASSWORD_WRONG');

        $userService->login('azkazafran78', 'test');
    }

    public function testLoginWithIncorrectUsernameOrPassword() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('test'),
            'is_verified' => true
        ];

        User::create($data);

        $userService = new UserService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USERNAME_OR_PASSWORD_WRONG');

        $userService->login('salah1', 'salah2');
    }
}