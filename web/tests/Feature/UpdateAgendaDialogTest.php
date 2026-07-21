<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateAgendaDialogTest extends TestCase {
    public function testUpdateAgendaDialogSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->addDay(),
            'waktu_berakhir' => now()->addDay()->addHours(3),
            'status' => 'belum dimulai'
        ]);

        $response = $this->actingAs($auth_user)
                        ->get("/agenda/{$agenda->id_agenda}/update");

        $response->assertViewIs('updateAgendaDialog')
                ->assertViewHas('data', function ($data) use ($agenda) {
                    return $data['id_agenda'] == $agenda->id_agenda &&
                            $data['nama_agenda'] == $agenda->nama_agenda &&
                            $data['lokasi'] == $agenda->lokasi &&
                            $data['waktu_agenda'] == $agenda->waktu_mulai->format('Y-m-d') &&
                            $data['jam_mulai'] == $agenda->waktu_mulai->format('H:i') &&
                            $data['jam_akhir'] == $agenda->waktu_berakhir->format('H:i');
                });
    }

    public function testUpdateAgendaDialogFailed() {
        $response = $this->get("/agenda/999/update");

        $response->assertRedirect('/login');
    }

    public function testUpdateAgendaDialogWithUnrecognizedAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $response = $this->actingAs($auth_user)
                        ->get("/agenda/999/update");

        $response->assertViewIs('errors.error')
                ->assertViewHas('title', '404 Not Found')
                ->assertViewHas('description', 'Agenda Tidak Dapat Ditemukan.');
    }

    public function testUpdateAgendaDialogWithUnpermittedUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $user2 = User::factory()->create();

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->addDay(),
            'waktu_berakhir' => now()->addDay()->addHours(3),
            'status' => 'belum dimulai'
        ]);

        $response = $this->actingAs($user2)
                        ->get("/agenda/{$agenda->id_agenda}/update");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna tidak memiliki izin untuk mengubah agenda ini.'
                ]);
    }
}