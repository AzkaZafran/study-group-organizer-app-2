<?php

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FindUserAgendaTest extends TestCase {
    public function testFindUserAgendaSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $target_agenda_name = 'agenda 1';

        $target_agenda = Agenda::factory()->create([
            'nama_agenda' => $target_agenda_name
        ]);

        Partisipan::create([
            'id_agenda' => $target_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $auth_user_other_attended_agenda_count = 3;

        Partisipan::factory()->count($auth_user_other_attended_agenda_count)->create([
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $agendaService = new AgendaService();

        $this->actingAs($auth_user);

        $result = $agendaService->findUserAgenda($target_agenda->id_agenda);

        $this->assertTrue(
            $result->id_agenda == $target_agenda->id_agenda
        );

        $this->assertTrue(
            $result->nama_agenda == $target_agenda_name
        );
    }

    public function testFindUserAgendaFailed() {
        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $agendaService->findUserAgenda(999);
    }

    public function testFindUserAgendaWithUnknownAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('AGENDA_NOT_FOUND');

        $result = $agendaService->findUserAgenda(999);
    }

    public function testFindUserAgendaWithUnparticipatedAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $target_agenda_name = 'agenda 1';

        $target_agenda = Agenda::factory()->create([
            'nama_agenda' => $target_agenda_name
        ]);

        Partisipan::create([
            'id_agenda' => $target_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'tidak ikut'
        ]);

        $auth_user_attended_agenda_count = 3;

        Partisipan::factory()->count($auth_user_attended_agenda_count)->create([
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $agendaService = new AgendaService();

        $this->actingAs($auth_user);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_PERMITTED');

        $result = $agendaService->findUserAgenda($target_agenda->id_agenda);
    }
}