<?php

namespace Tests\Unit;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SendFriendRequestTest extends TestCase {
    public function testSendFriendRequestSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'pelajar40',
            'email' => 'pelajar40@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $target_user = User::create($data);

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $friendRequestService->sendFriendRequest($target_user->id);

        $this->assertDatabaseHas(FriendRequests::class, [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $target_user->id,
            'status' => 'pending'
        ]);
    }

    public function testSendFriendRequestFailed() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $user1 = User::create($data);

        $data = [
            'username' => 'pelajar40',
            'email' => 'pelajar40@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $user2 = User::create($data);

        $friendRequestService = new FriendRequestService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $friendRequestService->sendFriendRequest($user2->id);
    }

    public function testSendFriendRequestToNonexistentUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_FOUND');

        $friendRequestService->sendFriendRequest(9999);
    }
}