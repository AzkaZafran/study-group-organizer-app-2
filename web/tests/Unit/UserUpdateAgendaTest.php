<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\User;
use App\Services\AgendaService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserUpdateAgendaTest extends TestCase {
    public function testUserUpdateAgendaSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id
        ]);

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $updated_agenda = $agendaService->updateAgenda($agenda->id_agenda,
                                                        'Agenda diubah',
                                                        $agenda->lokasi,
                                                        $agenda->waktu_mulai,
                                                        $agenda->waktu_berakhir);

        $this->assertTrue(
            $updated_agenda->id_agenda == $agenda->id_agenda &&
            $updated_agenda->nama_agenda == 'Agenda diubah',
            $updated_agenda->lokasi == $agenda->lokasi &&
            $updated_agenda->waktu_mulai == $agenda->waktu_mulai &&
            $updated_agenda->waktu_berakhir == $agenda->waktu_berakhir
        );

        $this->assertDatabaseHas(Agenda::class, [
            'id_agenda' => $agenda->id_agenda,
            'nama_agenda' => 'Agenda diubah'
        ]);
    }

    public function testUserUpdateAgendaFailed() {
        $agenda = Agenda::factory()->create();

        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $updated_agenda = $agendaService->updateAgenda($agenda->id_agenda,
                                                        'Agenda diubah',
                                                        $agenda->lokasi,
                                                        $agenda->waktu_mulai,
                                                        $agenda->waktu_berakhir);
    }

    public function testUserUpdateAgendaWithUnrecognizedAgenda() {
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

        $updated_agenda = $agendaService->updateAgenda(999,
                                                        '',
                                                        '',
                                                        '',
                                                        '');
    }

    public function testUserUpdateAgendaWithUnownedAgenda() {
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

        $user2 = User::create($data);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $user2->id
        ]);

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_PERMITTED');

        $updated_agenda = $agendaService->updateAgenda($agenda->id_agenda,
                                                        'Agenda diubah',
                                                        $agenda->lokasi,
                                                        $agenda->waktu_mulai,
                                                        $agenda->waktu_berakhir);
    }

    public function testUserUpdateAgendaWithRunningAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $running_agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->subHour(),
            'waktu_berakhir' => now()->addHour()
        ]);

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('AGENDA_ALREADY_RUNNING_OR_FINISHED');

        $updated_agenda = $agendaService->updateAgenda($running_agenda->id_agenda,
                                                        'Agenda diubah',
                                                        $running_agenda->lokasi,
                                                        $running_agenda->waktu_mulai,
                                                        $running_agenda->waktu_berakhir);
    }

    public function testUserUpdateAgendaWithFinishedAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $finished_agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->subHours(3),
            'waktu_berakhir' => now()->subHours(1)
        ]);

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('AGENDA_ALREADY_RUNNING_OR_FINISHED');

        $updated_agenda = $agendaService->updateAgenda($finished_agenda->id_agenda,
                                                        'Agenda diubah',
                                                        $finished_agenda->lokasi,
                                                        $finished_agenda->waktu_mulai,
                                                        $finished_agenda->waktu_berakhir);
    }
}