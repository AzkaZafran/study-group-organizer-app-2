<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\UndanganAgenda;
use App\Models\User;
use App\Services\AgendaService;
use App\Services\PartisipanService;
use App\Services\UndanganAgendaService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RejectAgendaInviteTest extends TestCase {
    public function testRejectAgendaInviteSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $target_user = User::create($data);

        $agendaService = new AgendaService();
        $partisipanService = new PartisipanService();
        $undanganAgendaService = new UndanganAgendaService();

        $this->actingAs($auth_user);

        $friend_request_data = [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $target_user->id,
            'status' => 'mutual'
        ];

        FriendRequests::create($friend_request_data);

        $new_agenda = Agenda::create([
            'id_penyelenggara' => $auth_user->id,
            'nama_agenda' => 'test agenda',
            'lokasi' => 'Jl. Jaya Sukses No. 2',
            'waktu_mulai' => '2026-12-20 09:00:00',
            'waktu_berakhir' => '2026-12-20 12:00:00'
        ]);

        Partisipan::create([
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' =>'ikut'
        ]);

        Partisipan::create([
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $target_user->id,
            'status' =>'pending'
        ]);

        $new_invite_code = UndanganAgenda::create([
            'id_agenda' => $new_agenda->id_agenda,
            'invite_code' => 600681,
            'expired_at' => now()->addMinutes(5)
        ]);

        $invite_code = $new_invite_code->invite_code;

        $response = $this->actingAs($target_user)->from("/agenda/{$invite_code}/join")
                        ->patch("/agenda/{$new_agenda->id_agenda}/reject-invite");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasNoErrors();
    }
}