<?php

namespace Tests\Feature;

use App\Models\OtpCodes;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegisterTest extends TestCase {
    public function testRegisterSuccess() {
        $response = $this->post('/register-account', [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "testestestest"
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
        $response = $this->from('/register')
                        ->post('/register-account', [
                            'username' => '',
                            'email' => '',
                            'password' => ''
                        ]);
        $response->assertRedirect('/register')
                ->assertSessionHasErrors([
                    'username' => 'The username field is required.',
                    'email' => 'The email field is required.',
                    'password' => 'The password field is required.'
                ]);
    }

    public function testRegisterUsernameAlreadyExist() {
        $this->testRegisterSuccess();

        $response = $this->from('/register')
                        ->post('/register-account', [
                            'username' => 'azkazafran78',
                            'email' => "azkazafran79@gmail.com",
                            "password" => "testestestest"
                        ]);
        
        $response->assertRedirect('/register')
                ->assertSessionHasErrors([
                    'message' => 'username sudah dipakai'
                ]);
    }

    public function testRegisterEmailAlreadyExistButVerified() {
        $data = [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => Hash::make('testestestest')
        ];

        $user = User::create($data);
        $user->is_verified = true;
        $user->save();

        $response = $this->from('/register')
                        ->post('/register-account', [
                            'username' => 'newazka',
                            'email' => "azkazafran78@gmail.com",
                            "password" => "testestestest"
                        ]);

        $response->assertRedirect('/register')
                ->assertSessionHasErrors([
                    'message' => 'email sudah dipakai'
                ]);
    }

    public function testRegisterEmailAlreadyExistButNotVerified() {
        $data = [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => Hash::make('testestestest')
        ];

        $user = User::create($data);

        $response = $this->from('/register')
                        ->post('/register-account', [
                            'username' => 'newazka',
                            'email' => "azkazafran78@gmail.com",
                            "password" => "testestestest"
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
