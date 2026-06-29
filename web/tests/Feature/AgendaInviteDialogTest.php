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

class AgendaInviteDialogTest extends TestCase {
    public function testAgendaInviteDialogSuccess() {
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

        $response = $this->actingAs($target_user)->get("/agenda/{$invite_code}/join");

        $response->assertViewIs('agendaInviteDialog')
                ->assertViewHas('data', function ($data) use ($new_agenda, $auth_user) {
                    return $data['agenda_name'] == $new_agenda->nama_agenda &&
                            $data['inviter_name'] == $auth_user->username;
                });
    }

    public function testAgendaInviteDialogFailed() {
        $response = $this->get('/agenda/123/join');

        $response->assertRedirect('/login');
    }

    public function testAgendaInviteDialogWithIncorrectCode() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $response = $this->actingAs($auth_user)->get('/agenda/123/join');

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Tautan undangan yang digunakan tidak valid.'
                ]);
    }

    public function testAgendaInviteDialogWithExpiredCode() {
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

        $new_invite_data->expired_at = now()->subDays(2);

        $new_invite_data->save();

        $invite_code = $new_invite_data->invite_code;

        $response = $this->actingAs($target_user)->get("/agenda/{$invite_code}/join");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Tautan undangan yang digunakan tidak valid.'
                ]);
    }

    public function testAgendaInviteDialogWithNonParticipatedUser() {
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
        $undanganAgendaService = new UndanganAgendaService();

        $this->actingAs($auth_user);

        $new_agenda = $agendaService->createAgenda(
                            'test agenda',
                            'Jl. Jaya Sukses No. 2',
                            '2026-12-20 09:00:00',
                            '2026-12-20 12:00:00'
                        );

        $new_invite_data = $undanganAgendaService->createAgendaInviteCode($new_agenda->id_agenda);
        
        $invite_code = $new_invite_data->invite_code;

        $response = $this->actingAs($target_user)->get("/agenda/{$invite_code}/join");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna tidak termasuk partisipan dari agenda ini.'
                ]);
    }

    public function testAgendaInviteDialogWithUserAlreadyRejectInvite() {
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

        Partisipan::create([
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $target_user->id,
            'status' => 'tidak ikut'
        ]);

        $new_invite_data = $undanganAgendaService->createAgendaInviteCode($new_agenda->id_agenda);

        $invite_code = $new_invite_data->invite_code;

        $response = $this->actingAs($target_user)->get("/agenda/{$invite_code}/join");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna telah menolak undangan ini.'
                ]);
    }
}