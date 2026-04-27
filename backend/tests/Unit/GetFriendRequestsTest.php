<?php

namespace Tests\Unit;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetFriendRequestsTest extends TestCase {
    public function testGetFriendRequestsSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        FriendRequests::factory()->count(10)->create([
            'id_penerima' => $auth_user->id
        ]);

        $friendRequestService = new FriendRequestService();

        $result = $friendRequestService->friendRequest();

        $this->assertCount(10, $result);

        $this->assertTrue(
            $result->contains(function ($friend_request) {
                return $friend_request->username !== null &&
                        $friend_request->email !== null;
            })
        );
    }

    public function testGetFriendRequestsFailed() {
        $friendRequestService = new FriendRequestService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $friendRequestService->friendRequest();
    }

    public function testGetFriendRequestsHasMutual() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        FriendRequests::factory()->count(8)->create([
            'id_penerima' => $auth_user->id
        ]);

        $data = [
            'username' => 'pelajar40',
            'email' => 'pelajar40@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $mutual_user = User::create($data);

        $sender_side = [
            'id_pengirim' => $mutual_user->id,
            'id_penerima' => $auth_user->id,
            'status' => 'mutual'
        ];
        FriendRequests::create($sender_side);
        $receiver_side = [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $mutual_user->id,
            'status' => 'mutual'
        ];
        FriendRequests::create($receiver_side);

        $friendRequestService = new FriendRequestService();

        $result = $friendRequestService->friendRequest();

        $this->assertCount(8, $result);

        $this->assertTrue(
            $result->contains(function ($friend_request) {
                return $friend_request->status !== "mutual";
            })
        );
    }
}