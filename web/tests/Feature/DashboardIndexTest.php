<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardIndexTest extends TestCase {
    public function testDashboardIndexSuccess() {
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

        $response = $this->actingAs($auth_user)->get('/dashboard');

        $response->assertViewIs('dashboard')
                ->assertViewHas('data', function ($data) use ($total_agenda_belum_dimulai, $total_agenda_sedang_berjalan, $total_agenda_selesai) {
                    return $data['total_user_agenda'] == $total_agenda_belum_dimulai + $total_agenda_sedang_berjalan + $total_agenda_selesai &&
                            $data['total_user_agenda_belum_dimulai'] == $total_agenda_belum_dimulai &&
                            $data['total_user_agenda_sedang_berjalan'] == $total_agenda_sedang_berjalan &&
                            $data['total_user_agenda_selesai'] == $total_agenda_selesai;
                });
    }

    public function testDashboardIndexFailed() {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}