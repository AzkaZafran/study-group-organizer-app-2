<?php

namespace Tests\Unit;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetUserFriendsTest extends TestCase {
    public function testGetUserFriendsSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        FriendRequests::factory()->count(10)->create([
            'id_pengirim' => $auth_user->id,
            'status' => 'mutual'
        ]);

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $result = $friendRequestService->friends();

        $this->assertCount(10, $result);

        $this->assertTrue(
            $result->contains(function ($friend) {
                return $friend['username'] !== null &&
                        $friend['email'] !== null;
            })
        );
    }

    public function testGetUserFriendsFailed() {
        $friendRequestService = new FriendRequestService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $friendRequestService->friends();
    }
}