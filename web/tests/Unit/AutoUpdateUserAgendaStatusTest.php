<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AutoUpdateUserAgendaStatusTest extends TestCase {
    public function testAutoUpdateUserAgendaStatusSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $agenda_belum_dimulai1 = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => $now->copy()->addDays(3),
            'waktu_berakhir' => $now->copy()
                                    ->addDays(3)
                                    ->addHours(3)
                                    ->min($now->copy()->addDays(3)->endOfDay()),
            'status' => 'belum dimulai'
        ]);

        Partisipan::create([
            'id_agenda' => $agenda_belum_dimulai1->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $agenda_belum_dimulai2 = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => $now->copy(),
            'waktu_berakhir' => $now->copy()
                                    ->addHours(2)
                                    ->min($now->copy()->endOfDay()),
            'status' => 'belum dimulai'
        ]);

        Partisipan::create([
            'id_agenda' => $agenda_belum_dimulai2->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $agenda_sedang_berjalan = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => $now->copy()->subDays(3),
            'waktu_berakhir' => $now->copy()
                                    ->subDays(3)
                                    ->addHours(3)
                                    ->min($now->copy()->subDays(3)->endOfDay()),
            'status' => 'sedang berjalan'
        ]);

        Partisipan::create([
            'id_agenda' => $agenda_sedang_berjalan->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $this->actingAs($auth_user);

        $agendaService = new AgendaService();

        $agendaService->autoUpdateUserAgendaStatus();

        $this->assertDatabaseHas(Agenda::class, [
            'id_agenda' => $agenda_belum_dimulai1->id_agenda,
            'status' => 'belum dimulai'
        ]);

        $this->assertDatabaseHas(Agenda::class, [
            'id_agenda' => $agenda_belum_dimulai2->id_agenda,
            'status' => 'sedang berjalan'
        ]);

        $this->assertDatabaseHas(Agenda::class, [
            'id_agenda' => $agenda_sedang_berjalan->id_agenda,
            'status' => 'selesai'
        ]);
    }

    public function testAutoUpdateUserAgendaStatusFailed() {
        $agendaService = new AgendaService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $agendaService->autoUpdateUserAgendaStatus();
    }
}