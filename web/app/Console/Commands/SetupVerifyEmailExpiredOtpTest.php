<?php

namespace App\Console\Commands;

use App\Models\OtpCodes;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:setup-verify-email-expired-otp-test')]
#[Description('Command description')]
class SetupVerifyEmailExpiredOtpTest extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $otp_code = OtpCodes::where('email', 'azkazafran80@gmail.com')->first();
        $otp_code->expired_at = now()->subMinutes(5);
        $otp_code->save();
    }
}
