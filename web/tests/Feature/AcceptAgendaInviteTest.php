<?php

namespace Tests\Feature;

use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use App\Services\PartisipanService;
use App\Services\UndanganAgendaService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AcceptAgendaInviteTest extends TestCase {
    public function testAcceptAgendaInviteSuccess() {
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

        $new_agenda = $agendaService->createAgenda(
                            'test agenda',
                            'Jl. Jaya Sukses No. 2',
                            '2026-12-20 09:00:00',
                            '2026-12-20 12:00:00'
                        );

        $partisipanService->addParticipants($new_agenda->id_agenda, [$target_user->id]);

        $new_invite_data = $undanganAgendaService->createAgendaInviteCode($new_agenda->id_agenda);

        $invite_code = $new_invite_data->invite_code;

        $response = $this->actingAs($target_user)->from("/agenda/{$invite_code}/join")
                        ->patch("/agenda/{$new_agenda->id_agenda}/accept-invite");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasNoErrors();
    }

    public function testAcceptAgendaInviteWithUserAlreadyJoinAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $agendaService = new AgendaService();

        $this->actingAs($auth_user);

        $new_agenda = $agendaService->createAgenda(
                            'test agenda',
                            'Jl. Jaya Sukses No. 2',
                            '2026-12-20 09:00:00',
                            '2026-12-20 12:00:00'
                        );

        $response = $this->patch("agenda/{$new_agenda->id_agenda}/accept-invite");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna sudah mengikuti agenda ini.'
                ]);
    }
}