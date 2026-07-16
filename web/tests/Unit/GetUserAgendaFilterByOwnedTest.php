<?php

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetUserAgendaFilterByOwnedTest extends TestCase {
    public function testGetUserAgendaFilterByOwnedSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $list_agenda = Agenda::factory()->count(3)->create([
            'id_penyelenggara' => $auth_user->id
        ]);

        $list_agenda = $list_agenda->merge(Agenda::factory()->count(7)->create());

        foreach ($list_agenda as $agenda) {
            Partisipan::create([
                'id_agenda' => $agenda->id_agenda,
                'id_user' => $auth_user->id,
                'status' => 'ikut'
            ]);
        }

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $result = $agendaService->getUserAgendaFilterByOwned();

        $this->assertTrue(
            $result->count() == 3 &&
            $result->contains(function ($agenda) use ($auth_user) {
                return $agenda->id_penyelenggara == $auth_user->id;
            })
        );
    }

    public function testGetUserAgendaFilterByOwnedFailed() {
        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $agendaService->getUserAgendaFilterByOwned();
    }
}