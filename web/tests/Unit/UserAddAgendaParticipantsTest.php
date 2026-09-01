<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\User;
use App\Services\AgendaService;
use App\Services\PartisipanService;
use Exception;
use App\Exceptions\ParticipantsNotFoundException;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAddAgendaParticipantsTest extends TestCase {
    public function testAddAgendaParticipantsSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data_pelajars = collect();

        for ($i=1; $i <= 5; $i++) { 
            $data_pelajars->add([
                'username' => "pelajar{$i}",
                'email' => "pelajar{$i}@gmail.com",
                'password' => Hash::make("passwordpelajar{$i}"),
                'is_verified' => true
            ]);
        }

        $id_pelajars = collect();

        foreach ($data_pelajars as $data_pelajar) {
            $pelajar = User::create($data_pelajar);
            $id_pelajars->add($pelajar->id);
            FriendRequests::create([
                'id_pengirim' => $auth_user->id,
                'id_penerima' => $pelajar->id,
                'status' => 'mutual'
            ]);
        }

        $partisipanService = new PartisipanService();
        $agendaService = new AgendaService();

        $this->actingAs($auth_user);
        
        $new_agenda = $agendaService->createAgenda(
                            'test agenda',
                            'Jl. Jaya Sukses No. 2',
                            '2026-12-20 09:00:00',
                            '2026-12-20 12:00:00'
                        );

        $results = $partisipanService->addParticipants(
            auth_user: $auth_user,
            agenda: $new_agenda,
            id_users: $id_pelajars->toArray()
        );

        $this->assertCount(5, $results);

        $this->assertTrue($results->contains(function ($participant, $idx) use ($id_pelajars, $new_agenda) {
            return $participant->id_agenda == $new_agenda->id_agenda &&
                    $participant->id_user == $id_pelajars[$idx];
        }));
    }

    public function testCreateParticipantsWithUnknownUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data_pelajars = collect();

        for ($i=1; $i <= 5; $i++) { 
            $data_pelajars->add([
                'username' => "pelajar{$i}",
                'email' => "pelajar{$i}@gmail.com",
                'password' => Hash::make("passwordpelajar{$i}"),
                'is_verified' => true
            ]);
        }

        $id_pelajars = collect();

        foreach ($data_pelajars as $data_pelajar) {
            $pelajar = User::create($data_pelajar);
            $id_pelajars->add($pelajar->id);
            FriendRequests::create([
                'id_pengirim' => $auth_user->id,
                'id_penerima' => $pelajar->id,
                'status' => 'mutual'
            ]);
        }

        $id_pelajars[2] = 9999;
        $id_pelajars[3] = 8888;

        $partisipanService = new PartisipanService();
        $agendaService = new AgendaService();

        $this->actingAs($auth_user);

        $new_agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'nama_agenda' => 'test agenda',
            'lokasi' => 'Jl. Jaya Sukses No. 2',
            'waktu_mulai' => '2026-12-20 09:00:00',
            'waktu_berakhir' => '2026-12-20 12:00:00'
        ]);

        $this->expectException(ParticipantsNotFoundException::class);
        $this->expectExceptionMessage('Ada partisipan yang tidak terdaftar.');

        $results = $partisipanService->addParticipants(
            auth_user: $auth_user,
            agenda: $new_agenda,
            id_users: $id_pelajars->toArray()
        );
    }
}