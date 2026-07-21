<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserUpdateAgendaTest extends TestCase {
    public function testUpdateAgendaSuccess() {
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
                        ->from("/agenda/{$agenda->id}/update")
                        ->put("/agenda/update", [
                            'id_agenda' => $agenda->id_agenda,
                            'nama_agenda' => 'agenda diubah',
                            'lokasi_agenda' => $agenda->lokasi,
                            'waktu_agenda' => $agenda->waktu_mulai->format('Y-m-d'),
                            'jam_awal' => $agenda->waktu_mulai->format('H:i'),
                            'jam_akhir' => $agenda->waktu_berakhir->format('H:i')
                        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas(Agenda::class, [
            'id_agenda' => $agenda->id_agenda,
            'nama_agenda' => 'agenda diubah'
        ]);
    }

    public function testUpdateAgendaFailed() {
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

        $response = $this->put("/agenda/update");

        $response->assertRedirect('/login');

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$agenda->id}/update")
                        ->put("/agenda/update", [
                            'id_agenda' => '',
                            'nama_agenda' => '',
                            'lokasi_agenda' => '',
                            'waktu_agenda' => '',
                            'jam_awal' => '',
                            'jam_akhir' => ''
                        ]);

        $response->assertRedirect("/agenda/{$agenda->id}/update")
                ->assertSessionHasErrors([
                    'id_agenda' => 'The id agenda field is required.',
                    'nama_agenda' => 'The nama agenda field is required.',
                    'lokasi_agenda' => 'The lokasi agenda field is required.',
                    'waktu_agenda' => 'The waktu agenda field is required.',
                    'jam_awal' => 'The jam awal field is required.',
                    'jam_akhir' => 'The jam akhir field is required.'
                ]);
    }

    public function testUpdateAgendaWithRunningOrFinishedAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->subHour(),
            'waktu_berakhir' => now()->addHour(),
            'status' => 'sedang berjalan'
        ]);

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$agenda->id}/update")
                        ->put("/agenda/update", [
                            'id_agenda' => $agenda->id_agenda,
                            'nama_agenda' => 'agenda diubah',
                            'lokasi_agenda' => $agenda->lokasi,
                            'waktu_agenda' => $agenda->waktu_mulai->addDay()->format('Y-m-d'),
                            'jam_awal' => $agenda->waktu_mulai->format('H:i'),
                            'jam_akhir' => $agenda->waktu_berakhir->format('H:i')
                        ]);

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Agenda dalam kondisi tidak bisa diedit.'
                ]);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->subHours(3),
            'waktu_berakhir' => now()->subHour(),
            'status' => 'selesai'
        ]);

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$agenda->id}/update")
                        ->put("/agenda/update", [
                            'id_agenda' => $agenda->id_agenda,
                            'nama_agenda' => 'agenda diubah',
                            'lokasi_agenda' => $agenda->lokasi,
                            'waktu_agenda' => $agenda->waktu_mulai->addDay()->format('Y-m-d'),
                            'jam_awal' => $agenda->waktu_mulai->format('H:i'),
                            'jam_akhir' => $agenda->waktu_berakhir->format('H:i')
                        ]);

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Agenda dalam kondisi tidak bisa diedit.'
                ]);
    }

    public function testUpdateAgendaWithPastDateOrTime() {
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
                        ->from("/agenda/{$agenda->id}/update")
                        ->put("/agenda/update", [
                            'id_agenda' => $agenda->id_agenda,
                            'nama_agenda' => 'agenda diubah',
                            'lokasi_agenda' => $agenda->lokasi,
                            'waktu_agenda' => now()->format('Y-m-d'),
                            'jam_awal' => now()->subHour()->format('H:i'),
                            'jam_akhir' => now()->addHour()->format('H:i')
                        ]);

        $response->assertRedirect("/agenda/{$agenda->id}/update")
                ->assertSessionHasErrors([
                    'start_time' => 'Tanggal atau waktu mulai yang diberikan harus di masa mendatang.'
                ]);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->addDay(),
            'waktu_berakhir' => now()->addDay()->addHours(3),
            'status' => 'belum dimulai'
        ]);

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$agenda->id}/update")
                        ->put("/agenda/update", [
                            'id_agenda' => $agenda->id_agenda,
                            'nama_agenda' => 'agenda diubah',
                            'lokasi_agenda' => $agenda->lokasi,
                            'waktu_agenda' => now()->subDays(2)->format('Y-m-d'),
                            'jam_awal' => $agenda->waktu_mulai->format('H:i'),
                            'jam_akhir' => $agenda->waktu_berakhir->format('H:i')
                        ]);

        $response->assertRedirect("/agenda/{$agenda->id}/update")
                ->assertSessionHasErrors([
                    'waktu_agenda' => 'The waktu agenda field must be a date after or equal to today.'
                ]);
    }

    public function testUpdateAgendaWithEndTimeGreaterThanStartTime() {
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
                        ->from("/agenda/{$agenda->id}/update")
                        ->put("/agenda/update", [
                            'id_agenda' => $agenda->id_agenda,
                            'nama_agenda' => 'agenda diubah',
                            'lokasi_agenda' => $agenda->lokasi,
                            'waktu_agenda' => $agenda->waktu_mulai->format('Y-m-d'),
                            'jam_awal' => now()->addHours(3)->format('H:i'),
                            'jam_akhir' => now()->addHour()->format('H:i')
                        ]);

        $response->assertRedirect("/agenda/{$agenda->id}/update")
                ->assertSessionHasErrors([
                    'jam_akhir' => 'The jam akhir field must be a date after jam awal.'
                ]);
    }
}