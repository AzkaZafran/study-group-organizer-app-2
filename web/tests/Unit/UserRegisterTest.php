<?php

namespace Tests\Unit;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Services\UserService;
use Tests\TestCase;

class UserRegisterTest extends TestCase {
    public function testRegisterSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => 'password123456789'
        ];

        $userService = new UserService();

        $result = $userService->register(
                    username:   $data['username'],
                    email:      $data['email'],
                    password:   $data['password']
                );

        $this->assertTrue(
            $result->username == $data['username'] &&
            $result->email == $data['email']
        );
    }

    public function testRegisterWithUsernameAlreadyExist() {
        $user_already_exist = User::factory()->create([
            'username' => 'user1',
            'email' => 'user1@gmail.com'
        ]);

        $new_user_data = [
            'username' => 'user1',
            'email' => 'testing@gmail.com',
            'password' => 'password123456789'
        ];

        $userService = new UserService();

        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage('Username ini sudah terdaftar.');

        $result = $userService->register(
                    username:   $new_user_data['username'],
                    email:      $new_user_data['email'],
                    password:   $new_user_data['password']
                );
    }

    public function testRegisterWithEmailAlreadyExistAndVerified() {
        $user_already_exist = User::factory()->create([
            'username' => 'user1',
            'email' => 'user1@gmail.com',
            'is_verified' => true
        ]);

        $new_user_data = [
            'username' => 'user2',
            'email' => 'user1@gmail.com',
            'password' => 'password123456789'
        ];

        $userService = new UserService();

        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage('Email ini sudah terdaftar.');

        $result = $userService->register(
                    username:   $new_user_data['username'],
                    email:      $new_user_data['email'],
                    password:   $new_user_data['password']
                );
    }
}