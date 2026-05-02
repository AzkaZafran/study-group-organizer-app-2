<?php

namespace Tests\Feature;

use App\Models\FriendRequests;
use App\Models\User;
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

        $response = $this->actingAs($auth_user)
                        ->get('/friend/list');
            
        $response->assertViewIs('friendList')
                ->assertViewHas('data', function ($data) {
                    return $data['friends']->count(10) &&
                            $data['friends']->contains(function ($friend) {
                                return $friend['username'] !== null &&
                                        $friend['email'] !== null;
                            });
                });
    }

    public function testGetUserFriendsFailed() {
        $response = $this->get('/friend/list');

        $response->assertRedirect('/login');
    }
}