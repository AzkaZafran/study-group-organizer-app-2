<?php

namespace Tests\Feature;

use App\Models\OtpCodes;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserVerifyEmailTest extends TestCase {
    public function testVerifyEmailSuccess() {
        $this->post('/register-account', [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "testestestest"
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
        $this->post('/register-account', [
            'username' => 'azkazafran80',
            'email' => "azkazafran80@gmail.com",
            "password" => "testestestest"
        ]);

        $otp_code = (int) OtpCodes::where('email', 'azkazafran80@gmail.com')->first()->otp_codes;

        $wrong_otp_code = 0;

        if($otp_code > 999999) {
            $wrong_otp_code = $otp_code - 1;
        } else {
            $wrong_otp_code = $otp_code + 1;
        }

        $response = $this->from('/register/input-otp')->post('/verify-email', [
            'email' => 'azkazafran80@gmail.com',
            'otp_code' => (string) $wrong_otp_code
        ]);

        $response->assertRedirect('/register/input-otp')
                ->assertSessionHasErrors([
                    'message' => 'Kode OTP tidak valid. Coba lagi atau kirim ulang kode.'
                ])
                ->assertSessionHasInput([
                    'email' => 'azkazafran80@gmail.com'
                ]);
    }

    public function testVerifyEmailFailed() {
        $this->post('/register-account', [
            'username' => 'azkazafran78',
            'email' => "azkazafran81@gmail.com",
            "password" => "testestestest"
        ]);

        $response = $this->from('/register/input-otp')->post('/verify-email', [
            'email' => 'azkazafran81@gmail.com',
            'otp_code' => ''
        ]);

        $response->assertRedirect('/register/input-otp')
                ->assertSessionHasErrors([
                    'otp_code' => 'The otp code field is required.'
                ]);
    }

    public function testVerifyEmailWithUsedOtpCode() {
        $this->post('/register-account', [
            'username' => 'azkazafran79',
            'email' => "azkazafran79@gmail.com",
            "password" => "testestestest"
        ]);

        $otp_code_data = OtpCodes::where('email', 'azkazafran79@gmail.com')->first();
        $otp_code = $otp_code_data->otp_codes;
        $otp_code_data->is_used = true;
        $otp_code_data->save();

        $response = $this->from('/register/input-otp')->post('/verify-email', [
            'email' => 'azkazafran79@gmail.com',
            'otp_code' => $otp_code
        ]);

        $response->assertRedirect('/register/input-otp')
                ->assertSessionHasErrors([
                    'message' => 'Kode OTP tidak valid. Coba lagi atau kirim ulang kode.'
                ])
                ->assertSessionHasInput([
                    'email' => 'azkazafran79@gmail.com'
                ]);
    }

    public function testVerifyEmailWithExpiredOtpCode() {
        $this->post('/register-account', [
            'username' => 'azkazafran79',
            'email' => "azkazafran82@gmail.com",
            "password" => "testestestest"
        ]);

        $otp_code_data = OtpCodes::where('email', 'azkazafran82@gmail.com')->first();
        $otp_code = $otp_code_data->otp_codes;
        $otp_code_data->expired_at = now()->subMinutes(10);
        $otp_code_data->save();

        $response = $this->from('/register/input-otp')->post('/verify-email', [
            'email' => 'azkazafran82@gmail.com',
            'otp_code' => $otp_code
        ]);

        $response->assertRedirect('/register/input-otp')
                ->assertSessionHasErrors([
                    'message' => 'Kode OTP sudah tidak berlaku. Silakan kirim ulang kode untuk mendapatkan OTP baru.'
                ])->assertSessionHasInput([
                    'email' => 'azkazafran82@gmail.com'
                ]);
    }
}