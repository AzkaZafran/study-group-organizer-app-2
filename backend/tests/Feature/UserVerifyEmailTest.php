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
            'username' => 'azkazafran80',
            'email' => "azkazafran80@gmail.com",
            "password" => "test"
        ]);

        $response = $this->from('/test')->post('/verify-email', [
            'email' => 'azkazafran80@gmail.com',
            'otp_code' => 'salah1'
        ]);

        $response->assertRedirect('/test')
                ->assertSessionHasErrors(['message'])
                ->assertSessionHasInput([
                    'email' => 'azkazafran80@gmail.com'
                ]);
    }

    public function testVerifyEmailFailed() {
        $this->post('/register', [
            'username' => 'azkazafran78',
            'email' => "azkazafran81@gmail.com",
            "password" => "test"
        ]);

        $response = $this->from('/test')->post('/verify-email', [
            'email' => 'azkazafran81@gmail.com',
            'otp_code' => ''
        ]);

        $response->assertRedirect('/test')
                ->assertSessionHasErrors(['otp_code']);
    }

    public function testVerifyEmailWithUsedOtpCode() {
        $this->post('/register', [
            'username' => 'azkazafran79',
            'email' => "azkazafran79@gmail.com",
            "password" => "test"
        ]);

        $otp_code_data = OtpCodes::where('email', 'azkazafran79@gmail.com')->first();
        $otp_code = $otp_code_data->otp_codes;
        $otp_code_data->is_used = true;
        $otp_code_data->save();

        $response = $this->from('/test')->post('/verify-email', [
            'email' => 'azkazafran79@gmail.com',
            'otp_code' => $otp_code
        ]);

        $response->assertRedirect('/test')
                ->assertSessionHasErrors(['message'])
                ->assertSessionHasInput([
                    'email' => 'azkazafran79@gmail.com'
                ]);
    }
}