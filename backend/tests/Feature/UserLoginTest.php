<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserLoginTest extends TestCase {
    public function testLoginSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        User::create($data);

        $response = $this->post('/login-account', [
            'username' => 'azkazafran78',
            'password' => 'testestestest'
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function testLoginWithUnverifiedEmail() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest')
        ];

        User::create($data);

        $response = $this->from('/login')->post('/login-account', [
            'username' => 'azkazafran78',
            'password' => 'testestestest'
        ]);

        $response->assertRedirect('/login')->assertSessionHasErrors([
            'message' => 'Username atau password salah.'
        ]);
    }

    public function testLoginWithIncorrectUsernameOrPassword() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        User::create($data);

        $response = $this->from('/login')->post('/login-account', [
            'username' => 'salah1',
            'password' => 'salah1'
        ]);

        $response->assertRedirect('/login')->assertSessionHasErrors([
            'message' => 'Username atau password salah.'
        ]);
    }

    public function testLoginFailed() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        User::create($data);

        $response = $this->from('/login')->post('/login-account', [
            'username' => '',
            'password' => ''
        ]);

        $response->assertRedirect('/login')->assertSessionHasErrors([
            'username' => 'The username field is required.',
            'password' => 'The password field is required.'
        ]);
    }
}