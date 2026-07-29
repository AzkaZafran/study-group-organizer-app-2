<?php

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetUserAgendaWithParticipantTest extends TestCase {
    public function testGetUserAgendaWithParticipantSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        Partisipan::factory()->count(5)->create([
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        Partisipan::factory()->count(3)->create([
            'id_user' => $auth_user->id
        ]);

        $agendaService = new AgendaService();

        $this->actingAs($auth_user);

        $result = $agendaService->getUserAgendaWithParticipant();

        $this->assertCount(5, $result);

        $this->assertTrue(
            $result->contains(function ($agenda) use ($auth_user) {
                return $agenda instanceof Agenda &&
                        $agenda->pivot->status == 'ikut' &&
                        $agenda->participants->contains('id', $auth_user->id) &&
                        $agenda->participants->contains(function ($participant) {
                            return (!empty($participant->pivot->status));
                        });
            })
        );
    }

    public function testGetUserAgendaWithParticipantFailed() {
        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $agendaService->getUserAgendaWithParticipant();
    }
}