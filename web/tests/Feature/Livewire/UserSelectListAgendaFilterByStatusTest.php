<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ListAgenda;
use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserSelectListAgendaFilterByStatusTest extends TestCase {
    public function testSelectListAgendaFilterByStatusSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $mutual_friends = User::factory()->count(5)->create();

        $mutual_friends->add($auth_user);

        for ($i = 0; $i < $mutual_friends->count() - 1; $i++) { 
            $friendi = $mutual_friends[$i];

            for ($j = $i + 1; $j < $mutual_friends->count(); $j++) { 
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

        $finished_agenda_count = 2;
        $running_agenda_count = 3;
        $pending_agenda_count = 5;
        $total_agenda_count = $finished_agenda_count + $running_agenda_count + $pending_agenda_count;

        $finished_agenda = Agenda::factory()
                                ->count($finished_agenda_count)
                                ->create([
                                    'id_penyelenggara' => $mutual_friends[2]->id,
                                    'status' => 'selesai'
                                ]);

        $running_agenda = Agenda::factory()
                                ->count($running_agenda_count)
                                ->create([
                                    'id_penyelenggara' => $mutual_friends[2]->id,
                                    'status' => 'sedang berjalan'
                                ]);

        $pending_agenda = Agenda::factory()
                                ->count($pending_agenda_count)
                                ->create([
                                    'id_penyelenggara' => $mutual_friends[2]->id,
                                    'status' => 'belum dimulai'
                                ]);

        $list_agenda = $finished_agenda->merge($running_agenda)
                                    ->merge($pending_agenda);

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

        $livewire_response = Livewire::test(ListAgenda::class)
                                    ->call('selectStatusFilter', 'belum dimulai');
        
        $livewire_response->assertSet('filterOwned', false)
                        ->assertSet('filterStatus', 'belum dimulai');

        /**
         * @var Collection<int, Agenda>
         */
        $list_pending_agenda_result = $livewire_response->get('list_agenda');

        $this->assertTrue(
            $list_pending_agenda_result->count() == $pending_agenda_count &&
            $list_pending_agenda_result->every('status', 'belum dimulai')
        );

        $livewire_response = Livewire::test(ListAgenda::class)
                                    ->call('selectStatusFilter', 'sedang berjalan');
        
        $livewire_response->assertSet('filterOwned', false)
                        ->assertSet('filterStatus', 'sedang berjalan');

        /**
         * @var Collection<int, Agenda>
         */
        $list_running_agenda_result = $livewire_response->get('list_agenda');

        $this->assertTrue(
            $list_running_agenda_result->count() == $running_agenda_count &&
            $list_running_agenda_result->every('status', 'sedang berjalan')
        );

        $livewire_response = Livewire::test(ListAgenda::class)
                                    ->call('selectStatusFilter', 'selesai');
        
        $livewire_response->assertSet('filterOwned', false)
                        ->assertSet('filterStatus', 'selesai');

        /**
         * @var Collection<int, Agenda>
         */
        $list_finished_agenda_result = $livewire_response->get('list_agenda');

        $this->assertTrue(
            $list_finished_agenda_result->count() == $finished_agenda_count &&
            $list_finished_agenda_result->every('status', 'selesai')
        );

        $all_result = $list_pending_agenda_result->merge($list_running_agenda_result)
                                                ->merge($list_finished_agenda_result);

        $this->assertTrue(
            $all_result->every(function (Agenda $agenda) use ($auth_user, $mutual_friends) {
                return $agenda->participants->contains(function (User $participant) use ($auth_user) {
                    return $participant->id == $auth_user->id &&
                            $participant->pivot->status == 'ikut';
                }) &&
                $agenda->participants->every(function (User $participant) use ($mutual_friends) {
                    return $mutual_friends->contains('id', $participant->id);
                });
            })
        );

        $livewire_response = Livewire::test(ListAgenda::class)
                                    ->call('selectStatusFilter', 'semua');
        
        $livewire_response->assertSet('filterOwned', false)
                        ->assertSet('filterStatus', 'semua');

        /**
         * @var Collection<int, Agenda>
         */
        $all_agenda_result = $livewire_response->get('list_agenda');

        $this->assertTrue(
            $all_agenda_result->count() == $total_agenda_count
        );

        $this->assertTrue(
            $all_agenda_result->every(function (Agenda $agenda) use ($auth_user, $mutual_friends) {
                return $agenda->participants->contains(function (User $participant) use ($auth_user) {
                    return $participant->id == $auth_user->id &&
                            $participant->pivot->status == 'ikut';
                }) &&
                $agenda->participants->every(function (User $participant) use ($mutual_friends) {
                    return $mutual_friends->contains('id', $participant->id);
                });
            })
        );
    }
}