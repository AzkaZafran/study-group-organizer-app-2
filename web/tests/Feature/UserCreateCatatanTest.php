<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCreateCatatanTest extends TestCase {
    public function testCreateCatatanSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda_data = [
            'nama_agenda' => 'belajar bareng',
            'lokasi' => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            'waktu_mulai' => $now->copy()->subHour()
                                ->max($now->copy()->startOfDay()),
            'waktu_berakhir' => $now->copy()->addHour()
                                    ->min($now->copy()->endOfDay()),
            'status' => 'sedang berjalan'
        ];

        $running_agenda = Agenda::factory()->create($running_agenda_data);

        $owner_agenda_participant = Partisipan::create([
                                        'id_agenda' => $running_agenda->id_agenda,
                                        'id_user' => $running_agenda->id_penyelenggara,
                                        'status' => 'ikut'
                                    ]);

        $auth_user_participant = Partisipan::create([
                                    'id_agenda' => $running_agenda->id_agenda,
                                    'id_user' => $auth_user->id,
                                    'status' => 'ikut'
                                ]);

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$running_agenda->id_agenda}/catatan")
                        ->post("/agenda/{$running_agenda->id_agenda}/catatan/create",
                                [
                                    'judul_catatan' => 'Laravel Livewire',
                                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                                ]
                            );

        $response->assertRedirect("/agenda/{$running_agenda->id_agenda}/catatan");

        $this->assertDatabaseHas(Catatan::class, [
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $auth_user->id,
            'judul_catatan' => 'Laravel Livewire',
            'catatan' => 'Membuat komponen interaktif menggunakan Livewire'
        ]);

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$running_agenda->id_agenda}/catatan")
                        ->post("/agenda/{$running_agenda->id_agenda}/catatan/create",
                                [
                                    'judul_catatan' => '',
                                    'isi_catatan' => 'Untitled Catatan'
                                ]
                            );

        $response->assertRedirect("/agenda/{$running_agenda->id_agenda}/catatan");

        $this->assertDatabaseHas(Catatan::class, [
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $auth_user->id,
            'judul_catatan' => 'Untitled',
            'catatan' => 'Untitled Catatan'
        ]);
    }

    public function testCreateCatatanFailed() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda_data = [
            'nama_agenda' => 'belajar bareng',
            'lokasi' => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            'waktu_mulai' => $now->copy()->subHour()
                                ->max($now->copy()->startOfDay()),
            'waktu_berakhir' => $now->copy()->addHour()
                                    ->min($now->copy()->endOfDay()),
            'status' => 'sedang berjalan'
        ];

        $running_agenda = Agenda::factory()->create($running_agenda_data);

        $owner_agenda_participant = Partisipan::create([
                                        'id_agenda' => $running_agenda->id_agenda,
                                        'id_user' => $running_agenda->id_penyelenggara,
                                        'status' => 'ikut'
                                    ]);

        $auth_user_participant = Partisipan::create([
                                    'id_agenda' => $running_agenda->id_agenda,
                                    'id_user' => $auth_user->id,
                                    'status' => 'ikut'
                                ]);

        $response = $this->post("/agenda/{$running_agenda->id_agenda}/catatan/create",
                                [
                                    'judul_catatan' => '',
                                    'isi_catatan' => ''
                                ]
                            );

        $response->assertRedirect('/login');

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$running_agenda->id_agenda}/catatan")
                        ->post("/agenda/{$running_agenda->id_agenda}/catatan/create",
                                [
                                    'judul_catatan' => '',
                                    'isi_catatan' => ''
                                ]
                            );

        $response->assertRedirect("/agenda/{$running_agenda->id_agenda}/catatan")
                ->assertSessionHasErrors([
                    'isi_catatan' => 'The isi catatan field is required.'
                ]);
    }

    public function testCreateCatatanWithUnknownAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $response = $this->actingAs($auth_user)
                        ->post("/agenda/999/catatan/create",
                                [
                                    'judul_catatan' => 'Laravel Livewire',
                                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                                ]
                            );

        $response->assertViewIs('errors.error')
                ->assertViewHas([
                    'title' => '404 Not Found',
                    'description' => 'Agenda Tidak Dapat Ditemukan.'
                ]);
    }

    public function testCreateCatatanWithUnparticipatingUserInAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda_data = [
            'nama_agenda' => 'belajar bareng',
            'lokasi' => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            'waktu_mulai' => $now->copy()->subHour()
                                ->max($now->copy()->startOfDay()),
            'waktu_berakhir' => $now->copy()->addHour()
                                    ->min($now->copy()->endOfDay()),
            'status' => 'sedang berjalan'
        ];

        $running_agenda = Agenda::factory()->create($running_agenda_data);

        $owner_agenda_participant = Partisipan::create([
                                        'id_agenda' => $running_agenda->id_agenda,
                                        'id_user' => $running_agenda->id_penyelenggara,
                                        'status' => 'ikut'
                                    ]);

        $auth_user_participant = Partisipan::create([
                                    'id_agenda' => $running_agenda->id_agenda,
                                    'id_user' => $auth_user->id,
                                    'status' => 'tidak ikut'
                                ]);

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$running_agenda->id_agenda}/catatan")
                        ->post("/agenda/{$running_agenda->id_agenda}/catatan/create",
                                [
                                    'judul_catatan' => 'Laravel Livewire',
                                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                                ]
                            );

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna bukan partisipan agenda ini.'
                ]);
    }

    public function testCreateCatatanWithAgendaNotStarted() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda_data = [
            'nama_agenda' => 'belajar bareng',
            'lokasi' => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            'waktu_mulai' => $now->copy()->addDays(2),
            'waktu_berakhir' => $now->copy()->addDays(2)->addHours(3)
                                    ->min($now->copy()->addDays(2)->endOfDay()),
            'status' => 'belum dimulai'
        ];

        $running_agenda = Agenda::factory()->create($running_agenda_data);

        $owner_agenda_participant = Partisipan::create([
                                        'id_agenda' => $running_agenda->id_agenda,
                                        'id_user' => $running_agenda->id_penyelenggara,
                                        'status' => 'ikut'
                                    ]);

        $auth_user_participant = Partisipan::create([
                                    'id_agenda' => $running_agenda->id_agenda,
                                    'id_user' => $auth_user->id,
                                    'status' => 'ikut'
                                ]);

        $response = $this->actingAs($auth_user)
                        ->from("/agenda/{$running_agenda->id_agenda}/catatan")
                        ->post("/agenda/{$running_agenda->id_agenda}/catatan/create",
                                [
                                    'judul_catatan' => 'Laravel Livewire',
                                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                                ]
                            );

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Catatan tidak dapat dibuat dalam agenda yang belum dimulai.'
                ]);
    }
}