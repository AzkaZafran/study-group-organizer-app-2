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

        Partisipan::factory()->count(5)->create([
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        Partisipan::factory()->count(3)->create([
            'id_user' => $auth_user->id
        ]);

        $agendaService = new AgendaService();

        $this->actingAs($auth_user);

        $result = $agendaService->getUserAgenda();

        $this->assertCount(5, $result);

        $this->assertTrue(
            $result->contains(function($agenda) {
                return $agenda instanceof Agenda;
            })
        );
    }

    public function testGetUserAgendaFailed() {
        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $agendaService->getUserAgenda();
    }
}