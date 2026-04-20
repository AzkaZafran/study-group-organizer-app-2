<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserGetCurrentTest extends TestCase {
    public function testGetCurrentUserSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user = User::create($data);

        $userService = new UserService();

        $this->actingAs($user);

        $current_user = $userService->getCurrentUser();

        $this->assertEquals($user, $current_user);
    }

    public function testGetCurrentUserFailed() {
        $userService = new UserService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $current_user = $userService->getCurrentUser();
    }
}