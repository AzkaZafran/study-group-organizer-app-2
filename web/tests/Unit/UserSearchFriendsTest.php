<?php

namespace Tests\Unit;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSearchFriendsTest extends TestCase {
    public function testSearchFriendsSuccess() {
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

        FriendRequests::factory()->create([
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $user->id,
            'status' => 'mutual'
        ]);

        FriendRequests::factory()->count(5)->create([
            'id_pengirim' => $auth_user->id,
            'status' => 'mutual'
        ]);

        $friendRequestService = new FriendRequestService();

        $this->actingAs($auth_user);

        $result = $friendRequestService->searchFriends('budi');

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains(function ($friend) {
            return $friend['username'] == 'budipratama' &&
                    $friend['email'] == 'budipratama@gmail.com';
        }));
    }

    public function testSearchFriendFailed() {
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
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $user->id,
            'status' => 'mutual'
        ];

        $new_friend_request = FriendRequests::create($friend_request_data);

        $friendRequestService = new FriendRequestService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $friendRequestService->searchFriends('budi');
    }
}