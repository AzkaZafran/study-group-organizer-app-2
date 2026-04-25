<?php

namespace Tests\Unit;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FriendUserSearchTest extends TestCase {
    public function testSearchUserFriendStatusWithBlank() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $users = User::factory()->count(15)->create();

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $users_status_page_1 = $friendRequestService->searchUser('', 1, 10);
        $users_status_page_2 = $friendRequestService->searchUser('', 2, 10);
        $all_users = $users_status_page_1->merge($users_status_page_2);
        
        $this->assertCount(10, $users_status_page_1);
        $this->assertCount(5, $users_status_page_2);

        $this->assertFalse(
            $all_users->contains(function ($user) {
                return $user->username == 'azkazafran78';
            })
        );
    }

    public function testSearchUserFriendStatusWithCurrentUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        User::factory()->count(15)->create();

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $users_status_page_1 = $friendRequestService->searchUser($auth_user->username, 1, 10);

        $this->assertCount(0, $users_status_page_1);
    }

    public function testSearchUserFriendStatusFailed() {
        $friendRequestService = new FriendRequestService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $users_status_page_1 = $friendRequestService->searchUser('test', 1, 10);
    }

    public function testSearchUserFriendStatusWithUsername() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user = User::create($data);

        User::factory()->count(15)->create();

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $users_status_page_1 = $friendRequestService->searchUser('budi', 1, 10);

        $this->assertTrue(
            $users_status_page_1->isNotEmpty()
        );

        $this->assertTrue(
            $users_status_page_1->contains('username', 'budipratama')
        );
    }

    public function testSearchUserFriendStatusWithUnverifiedUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest')
        ];

        $user = User::create($data);

        User::factory()->count(15)->create();

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $users_status_page_1 = $friendRequestService->searchUser('budi', 1, 10);

        $this->assertFalse(
            $users_status_page_1->contains('username', 'budipratama')
        );
    }

    public function testSearchUserWithOpenInviteStatus() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $target_user = User::create($data);

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $users_status_page_1 = $friendRequestService->searchUser('budi', 1, 10);

        $this->assertTrue(
            $users_status_page_1->contains(function ($user) {
                return $user->username == 'budipratama' &&
                        $user->friend_status == 'open invite';
            })
        );
    }

    public function testSearchUserWithPendingStatus() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $target_user = User::create($data);

        $friend_status_data = [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $target_user->id
        ];

        FriendRequests::create($friend_status_data);

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $users_status_page_1 = $friendRequestService->searchUser('budi', 1, 10);

        $this->assertTrue(
            $users_status_page_1->contains(function ($user) {
                return $user->username == 'budipratama' &&
                        $user->friend_status == 'pending';
            })
        );
    }

    public function testSearchUserWithMutualStatus() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $target_user = User::create($data);

        $friend_status_data = [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $target_user->id,
            'status' => 'mutual'
        ];

        FriendRequests::create($friend_status_data);

        $friend_status_data = [
            'id_pengirim' => $target_user->id,
            'id_penerima' => $auth_user->id,
            'status' => 'mutual'
        ];

        FriendRequests::create($friend_status_data);

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $users_status_page_1 = $friendRequestService->searchUser('budi', 1, 10);

        $this->assertTrue(
            $users_status_page_1->contains(function ($user) {
                return $user->username == 'budipratama' &&
                        $user->friend_status == 'mutual';
            })
        );
    }
}