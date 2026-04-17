<?php

namespace Tests\Feature;

use App\Models\OtpCodes;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserRegisterTest extends TestCase {
    public function testRegisterSuccess() {
        $response = $this->post('/register', [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "test"
        ]);

        $this->assertDatabaseHas('otp_codes', [
            'email' => 'azkazafran78@gmail.com'
        ]);

        $response->assertStatus(200)->assertViewIs('test')
                ->assertViewHas('data', function ($data) {
                    return $data['username'] === 'azkazafran78' &&
                            $data['email'] === 'azkazafran78@gmail.com';
                });
    }

    public function testRegisterFailed() {
        $response = $this->from('/test')
                        ->post('register', [
                            'username' => '',
                            'email' => '',
                            'password' => ''
                        ]);
        $response->assertRedirect('/test')
                ->assertSessionHasErrors([
                    'username' => 'The username field is required.',
                    'email' => 'The email field is required.',
                    'password' => 'The password field is required.'
                ]);
    }

    public function testRegisterUsernameAlreadyExist() {
        $this->testRegisterSuccess();

        $response = $this->from('/test')
                        ->post('/register', [
                            'username' => 'azkazafran78',
                            'email' => "azkazafran79@gmail.com",
                            "password" => "test"
                        ]);
        
        $response->assertRedirect('/test')
                ->assertSessionHasErrors([
                    'message' => 'username sudah dipakai'
                ]);
    }

    public function testRegisterEmailAlreadyExistButVerified() {
        $data = [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "test"
        ];

        $user = User::create($data);
        $user->is_verified = true;
        $user->save();

        $response = $this->from('/test')
                        ->post('/register', [
                            'username' => 'newazka',
                            'email' => "azkazafran78@gmail.com",
                            "password" => "test"
                        ]);

        $response->assertRedirect('/test')
                ->assertSessionHasErrors([
                    'message' => 'email sudah dipakai'
                ]);
    }

    public function testRegisterEmailAlreadyExistButNotVerified() {
        $data = [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "test"
        ];

        $user = User::create($data);

        $response = $this->from('/test')
                        ->post('/register', [
                            'username' => 'newazka',
                            'email' => "azkazafran78@gmail.com",
                            "password" => "test"
                        ]);
        
        $this->assertDatabaseHas('otp_codes', [
            'email' => 'azkazafran78@gmail.com'
        ]);

        $response->assertStatus(200)->assertViewIs('test')
                ->assertViewHas('data', function ($data) {
                    return $data['username'] === 'newazka' &&
                            $data['email'] === 'azkazafran78@gmail.com';
                });
    }
}
