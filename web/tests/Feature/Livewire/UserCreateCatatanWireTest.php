<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CreateCatatanForm;
use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserCreateCatatanWireTest extends TestCase {
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

        $this->actingAs($auth_user);

        Livewire::test(CreateCatatanForm::class, [
                    'id_agenda' => $running_agenda->id_agenda
                ])->set([
                    'judul_catatan' => 'Laravel Livewire',
                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                ])->call('createCatatan')
                ->assertDispatched('catatan-created')
                ->assertHasNoErrors();

        $this->assertDatabaseHas(Catatan::class, [
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $auth_user->id,
            'judul_catatan' => 'Laravel Livewire',
            'catatan' => 'Membuat komponen interaktif menggunakan Livewire'
        ]);

        $this->actingAs($auth_user);

        Livewire::test(CreateCatatanForm::class, [
                    'id_agenda' => $running_agenda->id_agenda
                ])->set([
                    'judul_catatan' => '',
                    'isi_catatan' => 'Untitled Catatan'
                ])->call('createCatatan')
                ->assertDispatched('catatan-created')
                ->assertHasNoErrors();

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

        Livewire::test(CreateCatatanForm::class, [
                    'id_agenda' => $running_agenda->id_agenda
                ])->set([
                    'judul_catatan' => 'Laravel Livewire',
                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                ])->call('createCatatan')
                ->assertHasErrors([
                    'business_error' => 'Pengguna tidak terautentikasi.'
                ]);

        Livewire::test(CreateCatatanForm::class, [
                    'id_agenda' => $running_agenda->id_agenda
                ])->set([
                    'judul_catatan' => '',
                    'isi_catatan' => ''
                ])->call('createCatatan')
                ->assertHasErrors([
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

        $this->actingAs($auth_user);

        Livewire::test(CreateCatatanForm::class, [
                    'id_agenda' => 999
                ])->set([
                    'judul_catatan' => 'Laravel Livewire',
                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                ])->call('createCatatan')
                ->assertHasErrors([
                    'business_error' => "Agenda tidak dapat ditemukan."
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

        $this->actingAs($auth_user);

        Livewire::test(CreateCatatanForm::class, [
                    'id_agenda' => $running_agenda->id_agenda
                ])->set([
                    'judul_catatan' => 'Laravel Livewire',
                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                ])->call('createCatatan')
                ->assertHasErrors([
                    'business_error' => 'Pengguna bukan partisipan agenda ini.'
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

        $this->actingAs($auth_user);

        Livewire::test(CreateCatatanForm::class, [
                    'id_agenda' => $running_agenda->id_agenda
                ])->set([
                    'judul_catatan' => 'Laravel Livewire',
                    'isi_catatan' => 'Membuat komponen interaktif menggunakan Livewire'
                ])->call('createCatatan')
                ->assertHasErrors([
                    'business_error' => 'Catatan tidak dapat dibuat dalam agenda yang belum dimulai.'
                ]);
    }
}