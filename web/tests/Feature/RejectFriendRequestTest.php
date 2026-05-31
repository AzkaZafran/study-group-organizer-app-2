<?php

namespace Tests\Feature;

use App\Models\FriendRequests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RejectFriendRequestTest extends TestCase {
    public function testRejectFriendRequestSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran80@gmail.com',
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

        $response = $this->actingAs($auth_user)
                        ->from('/friend/requests')
                        ->delete("/friend/requests/reject/{$new_friend_request->id_request}");

        $response->assertRedirect('/friend/requests');

        $this->assertDatabaseEmpty(FriendRequests::class);
    }

    public function testRejectFriendRequestFailed() {
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

        $response = $this->delete("/friend/requests/reject/{$new_friend_request->id_request}");

        $response->assertRedirect('/login');
    }

    public function testRejectFriendRequestNotFound() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $response = $this->actingAs($auth_user)
                        ->delete("/friend/requests/reject/9999");
        
        $response->assertViewIs('errors.error')
                ->assertSee('404 Not Found')
                ->assertSee('Friend Request Tidak Dapat Ditemukan.');
    }

    public function testRejectFriendRequestWithMutualFriendRequest() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $new_friend_request_data = FriendRequests::factory()->create([
            'id_pengirim' => $auth_user->id,
            'status' => 'mutual'
        ]);

        $response = $this->actingAs($auth_user)
                        ->followingRedirects()
                        ->from('/friend/requests')
                        ->delete("/friend/requests/reject/{$new_friend_request_data->id_request}");

        $response->assertViewIs('friendRequest')
                ->assertSee('Kamu sudah saling berteman.');
    }

    public function testRejectFriendRequestButUserAlreadyFriend() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $new_friend_request_data = FriendRequests::factory()->create([
            'id_pengirim' => $auth_user->id,
            'status' => 'mutual'
        ]);

        // Saling mutual pada kedua sisi user dalam database
        FriendRequests::create([
            'id_pengirim' => $new_friend_request_data->id_penerima,
            'id_penerima' => $auth_user->id,
            'status' => 'mutual'
        ]);

        // menciptkan situasi ketika data duplikat seperti ini berhasil dibuat dan tampil pada ui
        $inconsistent_friend_request_data = FriendRequests::create([
            'id_pengirim' => $new_friend_request_data->id_penerima,
            'id_penerima' => $auth_user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($auth_user)
                        ->followingRedirects()
                        ->from('/friend/requests')
                        ->delete("/friend/requests/reject/{$inconsistent_friend_request_data->id_request}");

        $this->assertDatabaseMissing(FriendRequests::class, [
            'id_pengirim' => $new_friend_request_data->id_penerima,
            'id_penerima' => $auth_user->id,
            'status' => 'pending'
        ]);

        $response->assertViewIs('friendRequest')
                ->assertSee('Kamu sudah saling berteman.');
    }
}