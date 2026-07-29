<?php

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetUserAgendaTest extends TestCase {
    public function testGetUserAgendaSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $auth_user_attended_agenda_count = 5;

        Partisipan::factory()->count($auth_user_attended_agenda_count)->create([
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $auth_user_unattended_agenda_count = 3;

        $auth_user_unattended_agenda = Partisipan::factory()
                                        ->count($auth_user_unattended_agenda_count)
                                        ->create([
                                            'id_user' => $auth_user->id
                                        ]);

        $agendaService = new AgendaService();

        $this->actingAs($auth_user);

        $result = $agendaService->getUserAgenda();

        $this->assertCount($auth_user_attended_agenda_count, $result);

        $this->assertTrue(
            $result->every(function ($agenda) use ($auth_user) {

                if (!($agenda instanceof Agenda)) {
                    return false;
                }

                $auth_user_is_attending = $agenda->pivot->id_user == $auth_user->id && 
                                            $agenda->pivot->status == 'ikut';

                if (!$auth_user_is_attending) {
                    return false;
                }

                return true;
            })
        );

        $result_only_have_attended_agenda_from_auth_user = $auth_user_unattended_agenda->every(
            function ($partisipan) use ($result) {
                
                return !$result->contains('id_agenda', $partisipan->id_agenda);
            }
        );

        $this->assertTrue($result_only_have_attended_agenda_from_auth_user);
    }

    public function testGetUserAgendaFailed() {
        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $agendaService->getUserAgenda();
    }
}