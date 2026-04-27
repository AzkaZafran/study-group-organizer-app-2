<?php

namespace Tests\Feature;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Illuminate\Database\Eloquent\Collection;
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

        $response = $this->get('/friend/requests');

        $response->assertViewIs('test')
                ->assertViewHas('data', function ($data) {
                    return $data['friend_requests']->count() === 10 &&
                            $data['friend_requests']->contains(function ($friend_request) {
                                return $friend_request['id_request'] !== null &&
                                        $friend_request['username'] !== null &&
                                        $friend_request['email'] !== null;
                            });
                });
    }

    public function testGetFriendRequestsFailed() {
        $response = $this->get('/friend/requests');

        $response->assertRedirect('/login');
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

        $response = $this->get('/friend/requests');

        $response->assertViewIs('test')
                ->assertViewHas('data', function ($data) {
                    return $data['friend_requests']->count() === 8;
                });
    }
}