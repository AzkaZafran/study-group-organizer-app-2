<?php

namespace Tests\Unit;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\InvalidOTPCodeException;
use App\Models\OtpCodes;
use App\Models\User;
use App\Services\UserService;
use Tests\TestCase;

class UserVerifyEmailTest extends TestCase {
    public function testVerifyEmailSuccess() {
        $unverified_user = User::factory()->create([
            'is_verified' => false
        ]);

        $otp_codes = (string) random_int(100000, 999999);

        $data = [
            'email' => $unverified_user->email,
            'otp_codes' => $otp_codes,
            'expired_at' => now()->addMinutes(5)
        ];

        OtpCodes::create($data);

        $userService = new UserService();

        $userService->verifyEmail(
            email: $unverified_user->email,
            otp_code: $otp_codes
        );

        $this->assertDatabaseHas(User::class, [
            'username' => $unverified_user->username,
            'email' => $unverified_user->email,
            'is_verified' => true
        ]);
    }

    public function testVerifyEmailWithInvalidOTPCode() {
        $unverified_user = User::factory()->create([
            'is_verified' => false
        ]);

        $userService = new UserService();

        $this->expectException(InvalidOTPCodeException::class);
        $this->expectExceptionMessage('Kode OTP tidak valid.');

        $userService->verifyEmail(
            email: $unverified_user->email,
            otp_code: 999999
        );
    }

    public function testVerifyEmailWithExpiredOTPCode() {
        $unverified_user = User::factory()->create([
            'is_verified' => false
        ]);

        $otp_codes = (string) random_int(100000, 999999);

        $data = [
            'email' => $unverified_user->email,
            'otp_codes' => $otp_codes,
            'expired_at' => now()->subMinute()
        ];

        OtpCodes::create($data);

        $userService = new UserService();

        $this->expectException(InvalidOTPCodeException::class);
        $this->expectExceptionMessage('Kode OTP sudah kadaluarsa');

        $userService->verifyEmail(
            email: $unverified_user->email,
            otp_code: $otp_codes
        );
    }
}