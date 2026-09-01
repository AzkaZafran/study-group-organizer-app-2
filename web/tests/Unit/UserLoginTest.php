<?php

namespace Tests\Unit;

use App\Exceptions\InvalidCredentialsException;
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

        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage('Username atau password salah.');

        $userService->login(
            username: 'azkazafran78', 
            password: 'test'
        );
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

        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage('Username atau password salah.');

        $userService->login('salah1', 'salah2');
    }
}