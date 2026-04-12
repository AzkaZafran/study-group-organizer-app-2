<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Membuat user baru ke dalam database
     *
     * @param array $data Data user yang sudah divalidasi
     * @return \App\Models\User
     */
    public function register(array $data): User {
        if(User::where('username', $data['username'])->count() == 1){
            throw new Exception("username sudah dipakai", 400);
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}