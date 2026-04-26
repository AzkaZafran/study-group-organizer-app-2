<?php

namespace Tests\Feature;

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
                        ->post("/friend/requests/accept/{$user->id}");

        $response->assertRedirect('/friend/requests');

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

        FriendRequests::create($friend_request_data);

        $response = $this->post("/friend/requests/accept/{$user2->id}");

        $response->assertRedirect('/login');
    }

    public function testAcceptFriendRequestNotFound() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $nonexistent_user_id = (int) $auth_user->id + 1;

        $response = $this->actingAs($auth_user)
                        ->post("/friend/requests/accept/{$nonexistent_user_id}");
        
        $response->assertViewIs('errors.error')
                ->assertSee('404 Not Found')
                ->assertSee('Friend Request Tidak Dapat Ditemukan.');
    }
}