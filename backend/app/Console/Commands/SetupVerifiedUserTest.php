<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

#[Signature('app:setup-verified-user-test')]
#[Description('Command description')]
class SetupVerifiedUserTest extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $user = User::create($data);

        if($user) {
            $this->info('Verified user successfully created');
        } else {
            $this->info('Failed to create verified user');
        }
    }
}
