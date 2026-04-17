<?php

namespace App\Services;

use App\Mail\OtpCodeMail;
use App\Models\OtpCodes;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService
{
    /**
     * Membuat user baru ke dalam database
     *
     * @param $username username yang sudah divalidasi
     * @param $email email yang sudah divalidasi
     * @param $password password yang sudah divalidasi
     * @return \App\Models\User
     */
    public function register($username, $email, $password): User {
        if(User::where('username', $username)->count() == 1){
            throw new Exception('USERNAME_ALREADY_EXIST');
        } elseif (User::where('email', $email)->where('is_verified', 1)->count() == 1) {
            throw new Exception("EMAIL_ALREADY_EXIST", 400);
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password)
        ];

        $user = User::updateOrCreate(['email' => $email], $data);

        return $user;
    }

    public function requestOtp($email) {

        if ($otp_found = OtpCodes::where('email', $email)->where('expired_at', '>', now())->first()) {
            $otp_found->is_used = true;
            $otp_found->save();
        }

        $otp_codes = (string) random_int(100000, 999999);

        $data = [
            'email' => $email,
            'otp_codes' => $otp_codes,
            'expired_at' => now()->addMinutes(5)
        ];

        $new_otp = OtpCodes::create($data);

        Mail::to($email)->send(new OtpCodeMail($otp_codes));

        return true;
    }

    public function verifyEmail($email, $otp_code) {
        $valid_otp = OtpCodes::where('email', $email)
                                ->where('otp_codes', $otp_code)
                                ->where('is_used', 0)
                                ->get();
        
        if ($valid_otp->isEmpty()) {
            throw new Exception('INVALID_OTP');
        }

        $otp_not_expired = $valid_otp->firstWhere('expired_at', '>', now());

        if ($otp_not_expired === null) {
            throw new Exception('EXPIRED_OTP');
        }

        $otp_not_expired->is_used = true;
        $otp_not_expired->save();

        $user = User::where('email', $email)->first(); 
        $user->is_verified = true;
        $user->save();
        
        return true;
    }
}