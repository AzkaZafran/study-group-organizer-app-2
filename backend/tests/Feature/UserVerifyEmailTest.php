<?php

namespace Tests\Feature;

use App\Models\OtpCodes;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserVerifyEmailTest extends TestCase {
    public function testVerifyEmailSuccess() {
        $this->post('/register', [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "test"
        ]);

        $otp_code = OtpCodes::where('email', 'azkazafran78@gmail.com')->first()->otp_codes;

        $response = $this->post('/verify-email', [
            'email' => 'azkazafran78@gmail.com',
            'otp_code' => $otp_code
        ]);

        $this->assertDatabaseMissing('otp_codes', [
            'email' => 'azkazafran78@gmail.com',
            'is_used' => 0
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'azkazafran78@gmail.com',
            'is_verified' => 1
        ]);

        $response->assertRedirect('/login');
    }

    public function testVerifyEmailWithWrongOtpCode() {
        $this->post('/register', [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "test"
        ]);

        $response = $this->from('/test')->post('/verify-email', [
            'email' => 'azkazafran78@gmail.com',
            'otp_code' => 'salah1'
        ]);

        $response->assertRedirect('/test')
                ->assertSessionHasErrors(['message'])
                ->assertSessionHasInput([
                    'email' => 'azkazafran78@gmail.com'
                ]);
    }
}