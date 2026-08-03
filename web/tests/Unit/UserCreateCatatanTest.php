<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\CatatanService;
use Exception;
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

        $catatanService = new CatatanService();

        $this->actingAs($auth_user);

        $result = $catatanService->createCatatan(
                        id_agenda:      $running_agenda->id_agenda,
                        judul_catatan:  'Laravel Livewire',
                        isi_catatan:    'Membuat komponen interaktif menggunakan Livewire'
                    );

        $this->assertTrue(
            $result->id_author == $auth_user->id &&
            $result->id_agenda == $running_agenda->id_agenda &&
            $result->judul_catatan == 'Laravel Livewire' &&
            $result->catatan == 'Membuat komponen interaktif menggunakan Livewire'
        );
    }

    public function testCreateCatatanFailed() {
        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $catatanService->createCatatan(
                        id_agenda:      999,
                        judul_catatan:  'Laravel Livewire',
                        isi_catatan:    'Membuat komponen interaktif menggunakan Livewire'
                    );
    }

    public function testCreateCatatanWithUnknownAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $catatanService = new CatatanService();

        $this->actingAs($auth_user);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('AGENDA_NOT_FOUND');

        $result = $catatanService->createCatatan(
                        id_agenda:      999,
                        judul_catatan:  'Laravel Livewire',
                        isi_catatan:    'Membuat komponen interaktif menggunakan Livewire'
                    );
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

        $catatanService = new CatatanService();

        $this->actingAs($auth_user);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_PERMITTED');

        $result = $catatanService->createCatatan(
                        id_agenda:      $running_agenda->id_agenda,
                        judul_catatan:  'Laravel Livewire',
                        isi_catatan:    'Membuat komponen interaktif menggunakan Livewire'
                    );
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

        $catatanService = new CatatanService();

        $this->actingAs($auth_user);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('AGENDA_NOT_STARTED_YET');

        $result = $catatanService->createCatatan(
                        id_agenda:      $running_agenda->id_agenda,
                        judul_catatan:  'Laravel Livewire',
                        isi_catatan:    'Membuat komponen interaktif menggunakan Livewire'
                    );
    }
}