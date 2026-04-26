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

        FriendRequests::create($friend_request_data);

        $response = $this->actingAs($auth_user)
                        ->from('/friend/requests')
                        ->delete("/friend/requests/reject/{$user->id}");

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

        $response = $this->delete("/friend/requests/reject/{$user2->id}");

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

        $nonexistent_user_id = (int) $auth_user->id + 1;

        $response = $this->actingAs($auth_user)
                        ->delete("/friend/requests/reject/{$nonexistent_user_id}");
        
        $response->assertViewIs('errors.error')
                ->assertSee('404 Not Found')
                ->assertSee('Friend Request Tidak Dapat Ditemukan.');
    }
}