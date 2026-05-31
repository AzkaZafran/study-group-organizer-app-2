<?php

namespace Tests\Unit;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSearchNewFriendTest extends TestCase {
    public function testSearchNewFriendWithBlank() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        User::factory()->count(15)->create();

        $friendRequestService = new FriendRequestService();

        $this->actingAs($auth_user);

        $users_page_1 = $friendRequestService->searchUser('', 1, 10);
        $this->assertTrue(
            $users_page_1['users']->count() === 10 &&
            $users_page_1['last_page'] === 2 &&
            $users_page_1['on_first_page'] === true &&
            $users_page_1['has_more_pages'] === true
        );

        $users_page_2 = $friendRequestService->searchUser('', 2, 10);
        $this->assertTrue(
            $users_page_2['users']->count() === 5 &&
            $users_page_2['last_page'] === 2 &&
            $users_page_2['on_first_page'] === false &&
            $users_page_2['has_more_pages'] === false
        );

        $all_users_result = $users_page_1['users']->merge($users_page_2['users']);

        $this->assertFalse(
            $all_users_result->contains('username', 'azkazafran78')
        );
    }

    public function testSearchNewFriendWithCurrentUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        User::factory()->count(15)->create();

        $friendRequestService = new FriendRequestService();

        $this->actingAs($auth_user);

        $users_page_1 = $friendRequestService->searchUser($auth_user->username, 1, 10);

        $this->assertCount(0, $users_page_1['users']);
    }

    public function testSearchNewFriendFailed() {
        $friendRequestService = new FriendRequestService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $users_page_1 = $friendRequestService->searchUser('test', 1, 10);
    }

    public function testSearchNewFriendWithUsername() {
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

        $user2 = User::create($data);

        User::factory()->count(15)->create();

        $friendRequestService = new FriendRequestService();

        $this->actingAs($auth_user);

        $users_page_1 = $friendRequestService->searchUser('budi', 1, 10);

        $this->assertTrue(
            $users_page_1['users']->isNotEmpty()
        );

        $this->assertTrue(
            $users_page_1['users']->contains('username', 'budipratama')
        );
    }

    public function testSearchNewFriendWithUnverifiedUser() {
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

        $user2 = User::create($data);

        User::factory()->count(15)->create();

        $friendRequestService = new FriendRequestService();

        $this->actingAs($auth_user);

        $users_page_1 = $friendRequestService->searchUser('budi', 1, 10);

        $this->assertFalse(
            $users_page_1['users']->contains('username', 'budipratama')
        );
    }

    public function testSearchNewFriendWithOpenInviteStatus() {
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
            $users_status_page_1['users']->contains(function ($user) {
                return $user->username == 'budipratama' &&
                        $user->friend_status == 'open invite';
            })
        );
    }

    public function testSearchNewFriendWithPendingStatus() {
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
            $users_status_page_1['users']->contains(function ($user) {
                return $user->username == 'budipratama' &&
                        $user->friend_status == 'pending';
            })
        );
    }

    public function testSearchNewFriendWithMutualStatus() {
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
            $users_status_page_1['users']->contains(function ($user) {
                return $user->username == 'budipratama' &&
                        $user->friend_status == 'mutual';
            })
        );
    }
}