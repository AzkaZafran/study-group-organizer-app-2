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

class UserCreateAgendaInviteCodeTest extends TestCase {
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

        $new_agenda = Agenda::factory()->create([
                                'id_penyelenggara' => $auth_user->id,
                                'nama_agenda' => 'test agenda',
                                'lokasi' => 'Jl. Jaya Sukses No. 2',
                                'waktu_mulai' => '2026-12-20 09:00:00',
                                'waktu_berakhir' => '2026-12-20 12:00:00'
                            ]);

        $new_invite_code = $undanganAgendaService
                            ->createAgendaInviteCode(
                                auth_user: $auth_user,
                                agenda: $new_agenda
                            );

        $this->assertTrue(
            $new_invite_code->id_agenda == $new_agenda->id_agenda &&
            Str::length($new_invite_code->invite_code) == 8 &&
            $new_agenda->waktu_mulai == $new_invite_code->expired_at
        );
    }
}