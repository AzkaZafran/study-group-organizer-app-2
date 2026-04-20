<?php

namespace Tests\Feature;

use App\Models\OtpCodes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserResendOtpTest extends TestCase {
    public function testResendOtpSuccess() {
        $this->post('/register-account', [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "testestestest"
        ]);

        $response = $this->post('/resend-otp', [
            'email' => 'azkazafran78@gmail.com'
        ]);

        $this->assertDatabaseHas('otp_codes', [
            'email' => "azkazafran78@gmail.com",
            'is_used' => true
        ]);

        $this->assertDatabaseHas('otp_codes', [
            'email' => "azkazafran78@gmail.com",
            'is_used' => false
        ]);

        $response->assertRedirect('/register/input-otp')
                ->assertSessionHasInput([
                    'email' => 'azkazafran78@gmail.com'
                ]);
    }

    public function testResendOtpFailed() {
        $response = $this->from('/register/input-otp')->post('/resend-otp', [
            'email' => ''
        ]);

        $response->assertRedirect('/register/input-otp')
                ->assertSessionHasErrors([
                    'email' => 'The email field is required.'
                ]);
    }
}