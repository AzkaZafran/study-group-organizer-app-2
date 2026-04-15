<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

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
            throw new Exception("username sudah dipakai", 400);
        } elseif (User::where('email', $email)->where('is_verified', 1)->count() == 1) {
            throw new Exception("email sudah dipakai", 400);
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password)
        ];

        $user = User::updateOrCreate(['email' => $email], $data);

        return $user;
    }
}