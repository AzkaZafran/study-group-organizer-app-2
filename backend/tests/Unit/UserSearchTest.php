<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSearchTest extends TestCase {
    public function testSearchUserWithBlank() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user = User::create($data);

        User::factory()->count(15)->create();

        $userService = new UserService();

        $this->actingAs($user);

        $users_page_1 = $userService->search('', 1, 10);
        $users_page_2 = $userService->search('', 2, 10);
        $all_result = $users_page_1->merge($users_page_2);

        $this->assertCount(10, $users_page_1);
        $this->assertCount(5, $users_page_2);

        $this->assertFalse(
            $all_result->contains('username', 'azkazafran78')
        );
    }

    public function testSearchUserWithCurrentUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user = User::create($data);

        User::factory()->count(15)->create();

        $userService = new UserService();

        $this->actingAs($user);

        $users_page_1 = $userService->search($user->username, 1, 10);

        $this->assertCount(0, $users_page_1);
    }

    public function testSearchUserFailed() {
        $userService = new UserService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $users_page_1 = $userService->search('test', 1, 10);
    }

    public function testSearchUserWithUsername() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $current_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user = User::create($data);

        User::factory()->count(15)->create();

        $userService = new UserService();

        $this->actingAs($current_user);

        $users_page_1 = $userService->search('budi', 1, 10);

        $this->assertTrue(
            $users_page_1->isNotEmpty()
        );

        $this->assertTrue(
            $users_page_1->contains('username', 'budipratama')
        );
    }

    public function testSearchUserWithUnverifiedUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $current_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest')
        ];

        $user = User::create($data);

        User::factory()->count(15)->create();

        $userService = new UserService();

        $this->actingAs($current_user);

        $users_page_1 = $userService->search('budi', 1, 10);

        $this->assertFalse(
            $users_page_1->contains('username', 'budipratama')
        );
    }
}