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

        $this->actingAs($user)
            ->withSession(['foo' => 'bar']);

        $old_token = session()->token();

        $response = $this->delete('/logout');

        $this->assertGuest();

        $response->assertSessionMissing('foo');

        $this->assertNotEquals($old_token, session()->token());

        $response->assertRedirect('/login');
    }

    public function testLogoutFailed() {

        $response = $this->delete('/logout');

        $response->assertRedirect('/login');
    }
}