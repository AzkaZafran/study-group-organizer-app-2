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

        $response = $this->actingAs($target_user)->get("/agenda/{$invite_code}/join");

        $response->assertViewIs('agendaInviteDialog')
                ->assertViewHas('data', function ($data) use ($new_agenda, $auth_user) {
                    return $data['agenda_name'] == $new_agenda->nama_agenda &&
                            $data['inviter_name'] == $auth_user->username &&
                            $data['id_agenda'] == $new_agenda->id_agenda;
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
            'expired_at' => now()->subMinutes(2)
        ]);

        $invite_code = $new_invite_code->invite_code;

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

        $new_invite_code = UndanganAgenda::create([
            'id_agenda' => $new_agenda->id_agenda,
            'invite_code' => 600681,
            'expired_at' => now()->addMinutes(5)
        ]);

        $invite_code = $new_invite_code->invite_code;

        $response = $this->actingAs($target_user)->get("/agenda/{$invite_code}/join");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna tidak termasuk partisipan dari agenda ini.'
                ]);
    }

    public function testAgendaInviteDialogWithUserAlreadyJoinAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

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

        $new_invite_code = UndanganAgenda::create([
            'id_agenda' => $new_agenda->id_agenda,
            'invite_code' => 600681,
            'expired_at' => now()->addMinutes(5)
        ]);

        $invite_code = $new_invite_code->invite_code;

        $response = $this->actingAs($auth_user)
                        ->get("/agenda/{$invite_code}/join");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna sudah mengikuti agenda ini.'
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
            'status' =>'tidak ikut'
        ]);

        $new_invite_code = UndanganAgenda::create([
            'id_agenda' => $new_agenda->id_agenda,
            'invite_code' => 600681,
            'expired_at' => now()->addMinutes(5)
        ]);

        $invite_code = $new_invite_code->invite_code;

        $response = $this->actingAs($target_user)->get("/agenda/{$invite_code}/join");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna telah menolak undangan ini.'
                ]);
    }
}