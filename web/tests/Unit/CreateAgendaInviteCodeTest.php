<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\User;
use App\Services\AgendaService;
use App\Services\UndanganAgendaService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Str;
use Tests\TestCase;

class CreateAgendaInviteCodeTest extends TestCase {
    public function testCreateAgendaInviteCodeSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $agendaService = new AgendaService();
        $undanganAgendaService = new UndanganAgendaService();

        $this->actingAs($auth_user);

        $new_agenda = $agendaService->createAgenda(
                            'test agenda',
                            'Jl. Jaya Sukses No. 2',
                            '2026-12-20 09:00:00',
                            '2026-12-20 12:00:00'
                        );

        $new_invite_code = $undanganAgendaService->createAgendaInviteCode($new_agenda->id_agenda);

        $this->assertTrue(
            $new_invite_code->id_agenda == $new_agenda->id_agenda &&
            Str::length($new_invite_code->invite_code) == 8
        );
    }

    public function testCreateAgendaInviteCodeFailed() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $undanganAgendaService = new UndanganAgendaService();

        $agenda_data = [
            'id_penyelenggara' => $auth_user->id,
            'nama_agenda' => 'test agenda',
            'lokasi' => 'Jl. Jaya Sukses No. 2',
            'waktu_mulai' => '2026-12-20 09:00:00',
            'waktu_berakhir' => '2026-12-20 12:00:00'
        ];

        $new_agenda = Agenda::create($agenda_data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $new_invite_code = $undanganAgendaService->createAgendaInviteCode($new_agenda->id_agenda);
    }

    public function testCreateAgendaInviteCodeWithUnknownAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $undanganAgendaService = new UndanganAgendaService();

        $this->actingAs($auth_user);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('AGENDA_NOT_FOUND');

        $new_invite_code = $undanganAgendaService->createAgendaInviteCode(9999);
    }
}