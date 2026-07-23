<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\User;
use App\Services\AgendaService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserDeleteAgendaTest extends TestCase {
    public function testUserDeleteAgendaSuccess() {
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

        $result = $agendaService->deleteAgenda($agenda->id_agenda);

        $this->assertTrue($result);

        $this->assertDatabaseMissing(Agenda::class, [
            'id_agenda' => $agenda->id_agenda
        ]);
    }

    public function testUserDeleteAgendaFailed() {
        $agenda = Agenda::factory()->create();

        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $updated_agenda = $agendaService->deleteAgenda($agenda->id_agenda);
    }

    public function testUserDeleteAgendaWithUnrecognizedAgenda() {
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

        $updated_agenda = $agendaService->deleteAgenda(999);
    }

    public function testUserDeleteAgendaWithUnownedAgenda() {
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

        $updated_agenda = $agendaService->deleteAgenda($agenda->id_agenda);
    }

    public function testUserDeleteAgendaWithRunningAgenda() {
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

        $updated_agenda = $agendaService->deleteAgenda($running_agenda->id_agenda);
    }

    public function testUserDeleteAgendaWithFinishedAgenda() {
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

        $updated_agenda = $agendaService->deleteAgenda($finished_agenda->id_agenda);
    }
}