<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\UndanganAgenda;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Str;
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

        $author_partisipan = Partisipan::create([
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $partisipan_agenda = Partisipan::factory()->count(3)->create([
            'id_agenda' => $agenda->id_agenda
        ]);

        $partisipan_agenda->prepend($author_partisipan);

        $old_code = Str::upper(Str::random(8));

        $old_invite_code = UndanganAgenda::create([
            'id_agenda' => $agenda->id_agenda,
            'invite_code' => $old_code,
            'expired_at' => $agenda->waktu_mulai->subMinutes(5)
        ]);

        $new_code = '';

        do {
            $new_code = Str::upper(Str::random(8));
        } while (
            $new_code == $old_code
        );

        $new_invite_code = UndanganAgenda::create([
            'id_agenda' => $agenda->id_agenda,
            'invite_code' => $new_code,
            'expired_at' => $agenda->waktu_mulai->addMinutes(5)
        ]);

        $response = $this->actingAs($auth_user)
                        ->from("/dashboard")
                        ->delete("/agenda/{$agenda->id_agenda}/delete");

        $response->assertRedirectBack();

        $this->assertDatabaseMissing(Agenda::class, [
            'id_agenda' => $agenda->id_agenda
        ]);

        foreach ($partisipan_agenda as $partisipan) {
            $this->assertDatabaseMissing(Partisipan::class, [
                'id_partisipan' => $partisipan->id_partisipan
            ]);
        }

        $this->assertDatabaseMissing(UndanganAgenda::class, [
            'id_invite' => $old_invite_code->id_invite
        ]);
        
        $this->assertDatabaseMissing(UndanganAgenda::class, [
            'id_invite' => $new_invite_code->id_invite
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