<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserLogoutTest extends TestCase {
    public function testLogoutSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user = User::create($data);

        $response = $this->actingAs($user)->delete('/logout');

        $this->assertGuest();

        $response->assertRedirect('/login');
    }

    public function testLogoutFailed() {

        $response = $this->delete('/logout');

        $response->assertRedirect('/login');
    }
}