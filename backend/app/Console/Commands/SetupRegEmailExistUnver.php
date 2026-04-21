<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

#[Signature('app:setup-reg-email-exist-unver')]
#[Description('Command description')]
class SetupRegEmailExistUnver extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789')
        ];

        $user = User::create($data);
    }
}
