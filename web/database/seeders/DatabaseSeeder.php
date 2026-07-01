<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\UndanganAgenda;
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

        $data = [
            'username' => 'pelajar39',
            'email' => 'pelajar398@gmail.com',
            'password' => Hash::make('pelajar123456789'),
            'is_verified' => true
        ];

        $pelajar39_user = User::create($data);

        FriendRequests::factory()->count(10)->create([
            'id_pengirim' => $auth_user->id,
            'status' => 'mutual'
        ]);

        FriendRequests::factory()->count(10)->create([
            'id_penerima' => $auth_user->id,
            'status' => 'pending'
        ]);

        FriendRequests::create([
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $pelajar39_user->id,
            'status' => 'mutual'
        ]);

        FriendRequests::create([
            'id_pengirim' => $pelajar39_user->id,
            'id_penerima' => $auth_user->id,
            'status' => 'mutual'
        ]);

        $strangers = User::factory()->count(10)->create();
        
        $agenda_data = [
            'id_penyelenggara' => $auth_user->id,
            'nama_agenda' => 'belajar bareng siang',
            'lokasi' => 'Jl. Jaya Sukses No. 2',
            'waktu_mulai' => '2026-10-15 12:30:00',
            'waktu_berakhir' => '2026-10-15 15:30:00'
        ];

        $agenda = Agenda::create($agenda_data);

        Partisipan::create([
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        Partisipan::create([
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $pelajar39_user->id
        ]);

        UndanganAgenda::create([
            'id_agenda' => $agenda->id_agenda,
            'invite_code' => '3a75bIc4',
            'expired_at' => now()->addDay()
        ]);

        $new_agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id
        ]);

        Partisipan::create([
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);
        
        Partisipan::factory()->count(9)->create([
            'id_agenda' => $new_agenda->id_agenda
        ]);

        $new_agenda = Agenda::factory()->create([
            'status' => 'sedang berjalan'
        ]);

        Partisipan::create([
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        Partisipan::factory()->count(5)->create([
            'id_agenda' => $new_agenda->id_agenda
        ]);

        Partisipan::factory()->count(4)->create([
            'id_agenda' => $new_agenda->id_agenda,
            'status' => 'tidak ikut'
        ]);

        $new_agenda = Agenda::factory()->create([
            'status' => 'selesai'
        ]);

        Partisipan::create([
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        Partisipan::factory()->count(9)->create([
            'id_agenda' => $new_agenda->id_agenda
        ]);

        Partisipan::factory()->count(10)->create([
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);
    }
}
