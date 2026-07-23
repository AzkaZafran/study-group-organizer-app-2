<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserDeleteAgendaTest extends TestCase {
    public function testUserDeleteAgendaSuccess() {
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
                        ->from("/dashboard")
                        ->delete("/agenda/{$agenda->id_agenda}/delete");

        $response->assertRedirectBack();

        $this->assertDatabaseMissing(Agenda::class, [
            'id_agenda' => $agenda->id_agenda
        ]);
    }

    public function testUserDeleteAgendaFailed() {
        $response = $this->delete("/agenda/999/delete");

        $response->assertRedirect('/login');
    }

    public function testUserDeleteAgendaWithNoPermission() {
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
                        ->delete("/agenda/{$agenda->id_agenda}/delete");

        $response->assertRedirectBack()
                ->assertRedirectBackWithErrors([
                    'message' => 'Pengguna tidak memiliki izin untuk mengubah agenda ini.'
                ]);
    }

        public function testUserDeleteRunningOrFinishedAgenda() {
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
                        ->from("/dashboard")
                        ->delete("/agenda/{$agenda->id_agenda}/delete");

        $response->assertRedirectBack()
                ->assertRedirectBackWithErrors([
                    'message' => 'Agenda dalam kondisi tidak bisa diedit.'
                ]);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->subHours(3),
            'waktu_berakhir' => now()->subHour(),
            'status' => 'selesai'
        ]);

        $response = $this->actingAs($auth_user)
                        ->from("/dashboard")
                        ->delete("/agenda/{$agenda->id_agenda}/delete");

        $response->assertRedirectBack()
                ->assertRedirectBackWithErrors([
                    'message' => 'Agenda dalam kondisi tidak bisa diedit.'
                ]);
    }
}