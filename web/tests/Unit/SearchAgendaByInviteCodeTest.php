<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AgendaService;
use App\Services\UndanganAgendaService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SearchAgendaByInviteCodeTest extends TestCase {
    public function testSearchAgendaByInviteCodeSuccess() {
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

        $result = $undanganAgendaService->searchAgendaByInviteCode($new_invite_code->invite_code);

        $this->assertTrue($new_agenda->id_agenda == $result->id_agenda);
    }

    public function testSearchAgendaByInviteCodeFailed() {
        $undanganAgendaService = new UndanganAgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $undanganAgendaService->searchAgendaByInviteCode('test123');
    }

    public function testSearchAgendaByInviteCodeWithInvalidCode() {
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('INVALID_INVITE_CODE');

        $result = $undanganAgendaService->searchAgendaByInviteCode('test');
    }
}