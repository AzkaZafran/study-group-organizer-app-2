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

class UserSelectListAgendaFilterByOwnedTest extends TestCase {
    public function testSelectListAgendaFilterByOwnedSuccess() {
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
        
        $livewire_response = Livewire::test(ListAgenda::class)
                                    ->call('selectOwnedFilter');

        $livewire_response->assertSet('filterStatus', '')
                            ->assertSet('filterOwned', true);

        /**
         * @var Collection<int, Agenda>
         */
        $list_agenda_result = $livewire_response->get('list_agenda');

        $this->assertTrue(
            $list_agenda_result->count() == $owned_agenda_count &&
            $list_agenda_result->every('id_penyelenggara', $auth_user->id)
        );

        $this->assertTrue(
            $list_agenda_result->every(function (Agenda $agenda) use ($mutual_friends) {
                return $agenda->participants->every(function (User $participant) use ($mutual_friends) {
                    return $mutual_friends->contains('id', $participant->id);
                });
            })
        );
    }
}