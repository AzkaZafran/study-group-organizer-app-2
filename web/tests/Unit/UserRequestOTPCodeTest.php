<?php

namespace Tests\Unit;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\InvalidOTPCodeException;
use App\Mail\OtpCodeMail;
use App\Models\OtpCodes;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserRequestOTPCodeTest extends TestCase {
    public function testRequestOtpCodeSuccess() {
        $unverified_user = User::factory()->create([
            'is_verified' => false
        ]);

        $userService = new UserService();

        Mail::fake();

        $userService->requestOtp(email: $unverified_user->email);

        $this->assertDatabaseHas(OtpCodes::class, [
            'email' => $unverified_user->email
        ]);

        Mail::assertSent(OtpCodeMail::class, function ($mail) use ($unverified_user) {
            return $mail->hasTo($unverified_user->email);
        });
    }
}