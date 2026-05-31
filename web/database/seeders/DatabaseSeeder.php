<?php

namespace Database\Seeders;

use App\Models\FriendRequests;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        FriendRequests::factory()->count(10)->create([
            'id_pengirim' => $auth_user->id,
            'status' => 'mutual'
        ]);

        FriendRequests::factory()->count(10)->create([
            'id_penerima' => $auth_user->id,
            'status' => 'pending'
        ]);

        $strangers = User::factory()->count(10)->create();
    }
}
