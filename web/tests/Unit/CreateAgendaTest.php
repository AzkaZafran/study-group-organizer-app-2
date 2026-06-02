<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\User;
use App\Services\AgendaService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAgendaTest extends TestCase {
    public function testCreateAgendaSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $agenda_data = [
            'nama_agenda' => 'belajar bareng',
            'lokasi' => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            'waktu_mulai' => '2026-10-15 12:30:00',
            'waktu_berakhir' => '2026-10-15 15:30:00'
        ];

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $new_agenda = $agendaService->createAgenda(
            $agenda_data['nama_agenda'], 
            $agenda_data['lokasi'], 
            $agenda_data['waktu_mulai'], 
            $agenda_data['waktu_berakhir']
        );

        $this->assertDatabaseHas(Agenda::class, [
            'id_agenda' => $new_agenda->id_agenda
        ]);

        $this->assertTrue(
            $new_agenda->nama_agenda == $agenda_data['nama_agenda'] &&
            $new_agenda->lokasi == $agenda_data['lokasi'] &&
            $new_agenda->waktu_mulai == $agenda_data['waktu_mulai'] &&
            $new_agenda->waktu_berakhir == $agenda_data['waktu_berakhir']
        );
    }

    public function testCreateAgendaFailed() {
        $agenda_data = [
            'nama_agenda' => 'belajar bareng',
            'lokasi' => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            'waktu_mulai' => '2026-10-15 12:30:00',
            'waktu_berakhir' => '2026-10-15 15:30:00'
        ];

        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $new_agenda = $agendaService->createAgenda(
            $agenda_data['nama_agenda'], 
            $agenda_data['lokasi'], 
            $agenda_data['waktu_mulai'], 
            $agenda_data['waktu_berakhir']
        );
    }
}