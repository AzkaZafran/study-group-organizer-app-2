<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\CatatanTerbaca;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CatatanIndexTest extends TestCase {
    public function testCatatanIndexSuccess() {
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

        $auth_user_attended_agenda = Partisipan::factory()->count(3)->create([
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $owner_participant = Partisipan::create([
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $auth_user_attended_agenda->prepend($owner_participant);

        $list_partisipan = Partisipan::factory()->count(2)->create([
            'id_agenda' => $agenda->id_agenda,
            'status' => 'ikut'
        ]);

        $list_partisipan->prepend($owner_participant);

        $response = $this->actingAs($auth_user)->get("/agenda/{$agenda->id_agenda}/catatan");

        $response->assertViewIs('catatan');

        $data = $response->viewData('data');

        $this->assertSame($auth_user->username, $data['nama_penyelenggara']);
        $this->assertSame($agenda->status, $data['agenda_status']);

        $this->assertTrue(
            $auth_user_attended_agenda->every(
                fn (Partisipan $partisipan) =>
                    $data['list_agenda']->contains('id_agenda', $partisipan->id_agenda)
            )
        );

        $this->assertTrue(
            $list_partisipan->every(
                fn (Partisipan $partisipan) =>
                    $data['list_partisipan']->contains('nama_partisipan', User::find($partisipan->id_user)->username)
            )
        );
    }

    public function testCatatanIndexFailed() {
        $response = $this->get('/agenda/999/catatan');

        $response->assertRedirect('/login');
    }

    public function testCatatanIndexWithUnknownAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $response = $this->actingAs($auth_user)
                        ->get('/agenda/999/catatan');

        $response->assertViewIs('errors.error')
                ->assertViewHas([
                    'title' => '404 Not Found',
                    'description' => 'Agenda Tidak Dapat Ditemukan.'
                ]);
    }

    public function testCatatanIndexWithUnparticipatedAgenda() {
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

        $owner_participant = Partisipan::create([
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'tidak ikut'
        ]);

        $response = $this->actingAs($auth_user)
                        ->get("/agenda/{$agenda->id_agenda}/catatan");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna bukan partisipan agenda ini.'
                ]);
    }
}