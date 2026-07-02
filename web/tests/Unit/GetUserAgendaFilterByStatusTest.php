<?php

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetUserAgendaFilterByStatusTest extends TestCase {
    public function testGetUserAgendaFilterByStatusSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $list_agenda = Agenda::factory()->count(2)->create([
            'status' => 'selesai'
        ]);

        $list_agenda = $list_agenda->merge(Agenda::factory()->count(3)->create([
            'status' => 'sedang berjalan'
        ]));

        $list_agenda = $list_agenda->merge(Agenda::factory()->count(5)->create([
            'status' => 'belum dimulai'
        ]));

        foreach ($list_agenda as $agenda) {
            Partisipan::create([
                'id_agenda' => $agenda->id_agenda,
                'id_user' => $auth_user->id,
                'status' => 'ikut'
            ]);
        }

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $result_agenda_belum_dimulai = $agendaService->getUserAgendaFilterByStatus('belum dimulai');

        $this->assertTrue(
            $result_agenda_belum_dimulai->count() == 5 &&
            $result_agenda_belum_dimulai->contains(function ($agenda) use ($auth_user) {
                return $agenda->status == 'belum dimulai' &&
                        $agenda->participants->contains('id', $auth_user->id);
            })
        );

        $result_agenda_sedang_berjalan = $agendaService->getUserAgendaFilterByStatus('sedang berjalan');

        $this->assertTrue(
            $result_agenda_sedang_berjalan->count() == 3 &&
            $result_agenda_sedang_berjalan->contains(function ($agenda) use ($auth_user) {
                return $agenda->status == 'sedang berjalan' &&
                        $agenda->participants->contains('id', $auth_user->id);
            })
        );

        $result_agenda_selesai = $agendaService->getUserAgendaFilterByStatus('selesai');

        $this->assertTrue(
            $result_agenda_selesai->count() == 2 &&
            $result_agenda_selesai->contains(function ($agenda) use ($auth_user) {
                return $agenda->status == 'selesai' &&
                        $agenda->participants->contains('id', $auth_user->id);
            })
        );
    }

    public function testGetUserAgendaFilterByStatusFailed() {
        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result_agenda_belum_dimulai = $agendaService->getUserAgendaFilterByStatus('belum dimulai');
    }

    public function testGetUserAgendaFilterByStatusWithInvalidStatus() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $result_agenda_belum_dimulai = $agendaService->getUserAgendaFilterByStatus('invalid');

        $this->assertTrue($result_agenda_belum_dimulai == -1);
    }
}