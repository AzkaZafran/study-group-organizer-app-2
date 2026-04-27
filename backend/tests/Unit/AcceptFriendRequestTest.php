<?php

namespace Tests\Unit;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AcceptFriendRequestTest extends TestCase {
    public function testAcceptFriendRequestSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
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

        $friend_request_data = [
            'id_pengirim' => $user->id,
            'id_penerima' => $auth_user->id
        ];

        $new_friend_request = FriendRequests::create($friend_request_data);

        $friendRequestService = new FriendRequestService();

        $this->actingAs($auth_user);

        $friendRequestService->acceptFriendRequest($new_friend_request->id_request);

        $this->assertDatabaseHas(FriendRequests::class, [
            'id_pengirim' => $user->id,
            'id_penerima' => $auth_user->id,
            'status' => 'mutual'
        ]);

        $this->assertDatabaseHas(FriendRequests::class, [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $user->id,
            'status' => 'mutual'
        ]);
    }

    public function testAcceptFriendRequestFailed() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $user1 = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user2 = User::create($data);

        $friend_request_data = [
            'id_pengirim' => $user2->id,
            'id_penerima' => $user1->id
        ];

        $new_friend_request = FriendRequests::create($friend_request_data);

        $friendRequestService = new FriendRequestService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $friendRequestService->acceptFriendRequest($new_friend_request->id_request);

        $this->assertDatabaseMissing(FriendRequests::class, [
            'id_pengirim' => $user2->id,
            'id_penerima' => $user1->id,
            'status' => 'mutual'
        ]);
    }

    public function testAcceptFriendRequestNotFound() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $friendRequestService = new FriendRequestService();

        $this->actingAs($auth_user);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('FRIEND_REQUEST_NOT_FOUND');

        $friendRequestService->acceptFriendRequest(9999);
    }
}