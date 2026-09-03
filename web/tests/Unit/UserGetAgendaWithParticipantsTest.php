<?php

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserGetAgendaWithParticipantsTest extends TestCase {
    public function testGetAgendaWithParticipantsSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $mutual_friends = User::factory()->count(5)->create();

        $mutual_friends->add($auth_user);

        for ($i=0; $i < $mutual_friends->count() - 1; $i++) { 
            $friendi = $mutual_friends[$i];

            for ($j=$i+1; $j < $mutual_friends->count(); $j++) { 
                $friendj = $mutual_friends[$j];

                FriendRequests::create([
                    'id_pengirim' => $friendi->id,
                    'id_penerima' => $friendj->id,
                    'status' => 'mutual'
                ]);

                FriendRequests::create([
                    'id_pengirim' => $friendj->id,
                    'id_penerima' => $friendi->id,
                    'status' => 'mutual'
                ]);
            }
        }

        $unattended_agenda_count = 3;
        $attended_agenda_count = 2;

        $unattended_agenda = Agenda::factory()
                                    ->count($unattended_agenda_count)
                                    ->create([
                                        'id_penyelenggara' => $mutual_friends[2]->id
                                    ]);

        foreach ($unattended_agenda as $agenda) {
            foreach ($mutual_friends as $participant) {
                if ($participant->id == $auth_user->id) {
                    continue;
                }

                Partisipan::create([
                    'id_agenda' => $agenda->id_agenda,
                    'id_user' => $participant->id,
                    'status' => 'ikut'
                ]);
            }
        }

        $attended_agenda = Agenda::factory()
                                    ->count($attended_agenda_count)
                                    ->create([
                                        'id_penyelenggara' => $mutual_friends[2]->id
                                    ]);

        foreach ($attended_agenda as $agenda) {
            foreach ($mutual_friends as $participant) {
                Partisipan::create([
                    'id_agenda' => $agenda->id_agenda,
                    'id_user' => $participant->id,
                    'status' => 'ikut'
                ]);
            }
        }

        $agendaService = new AgendaService();

        $this->actingAs($auth_user);

        $result = $agendaService->getUserAgendaWithParticipants($auth_user);

        $this->assertCount($attended_agenda_count, $result);

        $this->assertTrue(
            $result->every(function ($agenda) use ($auth_user, $mutual_friends) {
                return $agenda->participants->contains('id', $auth_user->id) &&
                        $agenda->participants->every(function (User $participant) use ($mutual_friends) {
                            return $mutual_friends->contains('id', $participant->id);
                        });
            })
        );
    }

    public function testGetAgendaWithParticipantsAndFilterByStatusSuccess() {
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

        $result_agenda_belum_dimulai = $agendaService->getUserAgendaWithParticipants($auth_user, 'belum dimulai');

        $this->assertTrue(
            $result_agenda_belum_dimulai->count() == 5 &&
            $result_agenda_belum_dimulai->contains(function ($agenda) use ($auth_user) {
                return $agenda->status == 'belum dimulai' &&
                        $agenda->participants->contains('id', $auth_user->id);
            })
        );

        $result_agenda_sedang_berjalan = $agendaService->getUserAgendaWithParticipants($auth_user, 'sedang berjalan');

        $this->assertTrue(
            $result_agenda_sedang_berjalan->count() == 3 &&
            $result_agenda_sedang_berjalan->contains(function ($agenda) use ($auth_user) {
                return $agenda->status == 'sedang berjalan' &&
                        $agenda->participants->contains('id', $auth_user->id);
            })
        );

        $result_agenda_selesai = $agendaService->getUserAgendaWithParticipants($auth_user, 'selesai');

        $this->assertTrue(
            $result_agenda_selesai->count() == 2 &&
            $result_agenda_selesai->contains(function ($agenda) use ($auth_user) {
                return $agenda->status == 'selesai' &&
                        $agenda->participants->contains('id', $auth_user->id);
            })
        );
    }

    public function testGetAgendaWithParticipantsAndFilterByOwnedSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $mutual_friends = User::factory()->count(5)->create();

        $mutual_friends->add($auth_user);

        for ($i=0; $i < $mutual_friends->count() - 1; $i++) { 
            $friendi = $mutual_friends[$i];

            for ($j=$i+1; $j < $mutual_friends->count(); $j++) { 
                $friendj = $mutual_friends[$j];

                FriendRequests::create([
                    'id_pengirim' => $friendi->id,
                    'id_penerima' => $friendj->id,
                    'status' => 'mutual'
                ]);

                FriendRequests::create([
                    'id_pengirim' => $friendj->id,
                    'id_penerima' => $friendi->id,
                    'status' => 'mutual'
                ]);
            }
        }

        $owned_agenda_count = 3;
        $unowned_agenda_count = 7;

        $owned_agenda = Agenda::factory()->count($owned_agenda_count)
                                        ->create([
                                            'id_penyelenggara' => $auth_user->id
                                        ]);

        $unowned_agenda = Agenda::factory()->count($unowned_agenda_count)
                                        ->create([
                                            'id_penyelenggara' => $mutual_friends[2]->id
                                        ]);

        $list_agenda = $owned_agenda->merge($unowned_agenda);

        foreach ($list_agenda as $agenda) {
            foreach ($mutual_friends as $participant) {
                Partisipan::create([
                    'id_agenda' => $agenda->id_agenda,
                    'id_user' => $participant->id,
                    'status' => 'ikut'
                ]);
            }
        }

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $result = $agendaService
                    ->getUserAgendaWithParticipants(
                        auth_user: $auth_user,
                        agenda_is_owned: true
                    );

        $this->assertTrue(
            $result->count() == 3 &&
            $result->every('id_penyelenggara', $auth_user->id)
        );

        $this->assertTrue(
            $result->every(function (Agenda $agenda) use ($mutual_friends) {
                return $agenda->participants->every(function (User $participant) use ($mutual_friends) {
                    return $mutual_friends->contains('id', $participant->id);
                });
            })
        );
    }

    public function testGetAgendaWithParticipantsAndCombinedFilter() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $mutual_friends = User::factory()->count(5)->create();

        $mutual_friends->add($auth_user);

        for ($i=0; $i < $mutual_friends->count() - 1; $i++) { 
            $friendi = $mutual_friends[$i];

            for ($j=$i+1; $j < $mutual_friends->count(); $j++) { 
                $friendj = $mutual_friends[$j];

                FriendRequests::create([
                    'id_pengirim' => $friendi->id,
                    'id_penerima' => $friendj->id,
                    'status' => 'mutual'
                ]);

                FriendRequests::create([
                    'id_pengirim' => $friendj->id,
                    'id_penerima' => $friendi->id,
                    'status' => 'mutual'
                ]);
            }
        }

        $owned_pending_agenda_count = 2;
        $owned_finished_agenda_count = 2;
        $unowned_pending_agenda_count = 2;
        $unowned_finished_agenda_count = 3;

        $owned_pending_agendas = Agenda::factory()
                                        ->count($owned_pending_agenda_count)
                                        ->create([
                                            'id_penyelenggara' => $auth_user->id,
                                            'status' => 'pending'
                                        ]);

        $owned_finished_agendas = Agenda::factory()
                                        ->count($owned_finished_agenda_count)
                                        ->create([
                                            'id_penyelenggara' => $auth_user->id,
                                            'status' => 'selesai'
                                        ]);

        $unowned_pending_agendas = Agenda::factory()
                                        ->count($unowned_pending_agenda_count)
                                        ->create([
                                            'id_penyelenggara' => $mutual_friends[2]->id,
                                            'status' => 'pending'
                                        ]);

        $unowned_finished_agendas = Agenda::factory()
                                        ->count($unowned_finished_agenda_count)
                                        ->create([
                                            'id_penyelenggara' => $mutual_friends[3]->id,
                                            'status' => 'selesai'
                                        ]);

        $list_agenda = $owned_pending_agendas->merge($owned_finished_agendas)
                                            ->merge($unowned_pending_agendas)
                                            ->merge($unowned_finished_agendas);

        foreach ($list_agenda as $agenda) {
            foreach ($mutual_friends as $participant) {
                Partisipan::create([
                    'id_agenda' => $agenda->id_agenda,
                    'id_user' => $participant->id,
                    'status' => 'ikut'
                ]);
            }
        }

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $result = $agendaService->getUserAgendaWithParticipants(
            auth_user:          $auth_user,
            agenda_status:      'selesai',
            agenda_is_owned:    true
        );

        $this->assertTrue(
            $result->count() == 2 &&
            $result->every('status', 'selesai') &&
            $result->every('id_penyelenggara', $auth_user->id)
        );

        $this->assertTrue(
            $result->every(function (Agenda $agenda) use ($mutual_friends) {
                return $agenda->participants->every(function (User $participant) use ($mutual_friends) {
                    return $mutual_friends->contains('id', $participant->id);
                });
            })
        );
    }
}