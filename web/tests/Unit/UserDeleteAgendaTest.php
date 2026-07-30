<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\CatatanTerbaca;
use App\Models\Partisipan;
use App\Models\UndanganAgenda;
use App\Models\User;
use App\Services\AgendaService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Str;
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

        $owner_participant = Partisipan::create([
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $list_partisipan = Partisipan::factory()->count(3)->create([
            'id_agenda' => $agenda->id_agenda
        ]);

        $list_partisipan->prepend($owner_participant);

        $old_code = Str::upper(Str::random(8));

        $old_invite_code = UndanganAgenda::create([
            'id_agenda' => $agenda->id_agenda,
            'invite_code' => $old_code,
            'expired_at' => $agenda->waktu_mulai->subMinutes(5)
        ]);

        $list_catatan = collect();

        for ($i=0; $i < 3; $i++) { 
            $catatan = Catatan::factory()->create([
                'id_agenda' => $agenda->id_agenda,
                'id_author' => $auth_user->id
            ]);

            CatatanTerbaca::create([
                'id_catatan' => $catatan->id_catatan,
                'id_user' => $auth_user->id,
                'status' => 'sudah dibaca'
            ]);

            $list_catatan->push($catatan);
        }

        $new_code = '';

        do {
            $new_code = Str::upper(Str::random(8));
        } while (
            $new_code == $old_code
        );

        $new_invite_code = UndanganAgenda::create([
            'id_agenda' => $agenda->id_agenda,
            'invite_code' => $new_code,
            'expired_at' => $agenda->waktu_mulai->addMinutes(5)
        ]);

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $result = $agendaService->deleteAgenda($agenda->id_agenda);

        $this->assertTrue($result);

        $this->assertDatabaseMissing(Partisipan::class, [
            'id_agenda' => $agenda->id_agenda
        ]);

        $this->assertDatabaseMissing(UndanganAgenda::class, [
            'id_invite' => $old_invite_code->id_invite
        ]);
        
        $this->assertDatabaseMissing(UndanganAgenda::class, [
            'id_agenda' => $agenda->id_agenda
        ]);

        $this->assertDatabaseMissing(Catatan::class, [
            'id_agenda' => $agenda->id_agenda
        ]);

        foreach ($list_catatan as $catatan) {
            $this->assertDatabaseMissing(CatatanTerbaca::class, [
                'id_catatan' => $catatan->id_catatan
            ]);
        }
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