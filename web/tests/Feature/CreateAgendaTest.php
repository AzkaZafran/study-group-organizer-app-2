<?php

namespace Tests\Feature;

use App\Models\FriendRequests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAgendaTest extends TestCase {
    public function testCreateAgendaSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $mutual_friends = FriendRequests::factory()->count(5)->create([
            'id_pengirim' => $auth_user->id,
            'status' => 'mutual'
            ]);

        $participants_id = $mutual_friends->pluck('id_penerima')->toArray();

        $this->actingAs($auth_user);

        $response = $this->from('/dashboard')->post('/agenda/add', [
            "nama_agenda" => 'Agenda #1',
            "lokasi_agenda" => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            "waktu_agenda" => '2026-10-15',
            "jam_awal" => '12:30',
            "jam_akhir" => '15:30',
            "participant_id" => $participants_id
        ]);

        $response->assertRedirect('/dashboard')
                    ->assertSessionHas('invite_code');
    }
}