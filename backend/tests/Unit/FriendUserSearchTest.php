<?php

namespace Tests\Unit;

use App\Models\FriendRequests;
use App\Models\User;
use App\Services\FriendRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FriendUserSearchTest extends TestCase {
    public function testSearchUserFriendStatusWithBlank() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $users = User::factory()->count(15)->create();

        $this->actingAs($auth_user);

        $friendRequestService = new FriendRequestService();

        $users_status_page_1 = $friendRequestService->searchUser('', 1, 10);
        $users_status_page_2 = $friendRequestService->searchUser('', 2, 10);
        $all_users = $users_status_page_1->merge($users_status_page_2);
        
        $this->assertCount(10, $users_status_page_1);
        $this->assertCount(5, $users_status_page_2);

        $this->assertFalse(
            $all_users->contains(function ($user) {
                return $user->username == 'azkazafran78';
            })
        );
    }
}