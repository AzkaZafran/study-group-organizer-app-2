<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\UndanganAgenda;
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

        $this->actingAs($auth_user);

        $undanganAgendaService = new UndanganAgendaService();

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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('INVALID_INVITE_CODE');

        $result = $undanganAgendaService->searchAgendaByInviteCode('test');
    }
}