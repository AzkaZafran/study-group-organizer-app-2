<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetUserAgendaStatistikTest extends TestCase {
    public function testGetUserAgendaStatistikSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $total_agenda_selesai = 3;
        $total_agenda_sedang_berjalan = 2;
        $total_agenda_belum_dimulai = 5;

        $new_agendas = Agenda::factory()->count($total_agenda_selesai)->create([
                            'id_penyelenggara' => $auth_user->id,
                            'status' => 'selesai'
                        ]);

        foreach ($new_agendas as $agenda) {
            Partisipan::create([
                'id_agenda' => $agenda->id_agenda,
                'id_user' => $auth_user->id,
                'status' => 'ikut'
            ]);
        }

        $new_agendas = Agenda::factory()->count($total_agenda_sedang_berjalan)->create([
                            'id_penyelenggara' => $auth_user->id,
                            'status' => 'sedang berjalan'
                        ]);

        foreach ($new_agendas as $agenda) {
            Partisipan::create([
                'id_agenda' => $agenda->id_agenda,
                'id_user' => $auth_user->id,
                'status' => 'ikut'
            ]);
        }

        $new_agendas = Agenda::factory()->count($total_agenda_belum_dimulai)->create([
                            'id_penyelenggara' => $auth_user->id,
                            'status' => 'belum dimulai'
                        ]);

        foreach ($new_agendas as $agenda) {
            Partisipan::create([
                'id_agenda' => $agenda->id_agenda,
                'id_user' => $auth_user->id,
                'status' => 'ikut'
            ]);
        }

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $result = $agendaService->getUserAgendaStatistik();

        $this->assertTrue(
            $result['total_user_agenda'] == $total_agenda_belum_dimulai + $total_agenda_sedang_berjalan + $total_agenda_selesai &&
            $result['total_user_agenda_belum_dimulai'] == $total_agenda_belum_dimulai &&
            $result['total_user_agenda_sedang_berjalan'] == $total_agenda_sedang_berjalan &&
            $result['total_user_agenda_selesai'] == $total_agenda_selesai
        );
    }

    public function testGetUserAgendaStatistikFailed() {
        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $agendaService->getUserAgendaStatistik();
    }
}