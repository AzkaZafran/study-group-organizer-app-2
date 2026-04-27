<?php

namespace Tests\Feature;

use App\Models\FriendRequests;
use App\Models\User;
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

        $response = $this->actingAs($auth_user)
                        ->from(route('search new friend', [
                            'username' => $target_user->username
                        ]))
                        ->post("/friend/requests/send/{$target_user->id}");

        $response->assertRedirect(route('search new friend', [
                                'username' => $target_user->username
                            ]));
        
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

        $response = $this->post("/friend/requests/send/{$user2->id}");

        $response->assertRedirect('/login');
    }

    public function testSendFriendRequestToNonexistentUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $response = $this->actingAs($auth_user)
                        ->post("/friend/requests/send/9999");
            
        $response->assertViewIs('errors.error')
                ->assertSee('404 Not Found')
                ->assertSee('User Tidak Dapat Ditemukan.');
    }
}