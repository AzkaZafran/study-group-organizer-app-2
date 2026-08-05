<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use App\Services\CatatanService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EditCatatanTest extends TestCase {
    public function testEditCatatanSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => $now->copy()->subHour()
                                ->max($now->copy()->startOfDay()),
            'waktu_berakhir' => $now->copy()->addHour()
                                    ->min($now->copy()->endOfDay()),
            'status' => 'sedang berjalan'
        ]);

        $owner_participant = Partisipan::create([
            'id_agenda' => $running_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $catatan_data = [
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $auth_user->id,
            'judul_catatan' => 'Laravel Test',
            'catatan' => 'This is a test'
        ];

        $catatan = Catatan::create($catatan_data);

        $this->actingAs($auth_user);

        $catatanService = new CatatanService();

        $result = $catatanService->editCatatan(
                            id_catatan:     $catatan->id_catatan,
                            judul_catatan:  'Laravel Test [UPDATED]',
                            isi_catatan:    'This is a test [UPDATED]'
                        );

        $this->assertTrue(
            $result->id_catatan == $catatan->id_catatan &&
            $result->id_author == $auth_user->id &&
            $result->id_agenda == $running_agenda->id_agenda &&
            $result->judul_catatan == 'Laravel Test [UPDATED]' &&
            $result->catatan == 'This is a test [UPDATED]'
        );

        $this->assertDatabaseHas(Catatan::class, [
            'id_catatan' => $catatan->id_catatan,
            'id_author' => $auth_user->id,
            'id_agenda' => $running_agenda->id_agenda,
            'judul_catatan' => 'Laravel Test [UPDATED]',
            'catatan' => 'This is a test [UPDATED]'
        ]);
    }

    public function testEditCatatanFailed() {
        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $catatanService->editCatatan(
                            id_catatan:     999,
                            judul_catatan:  'Laravel Test [UPDATED]',
                            isi_catatan:    'This is a test [UPDATED]'
                        );
    }

    public function testEditCatatanWithUnknownCatatan() {
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
        $this->expectExceptionMessage('CATATAN_NOT_FOUND');

        $catatanService->editCatatan(
                            id_catatan:     999,
                            judul_catatan:  'Laravel Test [UPDATED]',
                            isi_catatan:    'This is a test [UPDATED]'
                        );
    }

    public function testEditCatatanWithUserNotAuthor() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => $now->copy()->subHour()
                                ->max($now->copy()->startOfDay()),
            'waktu_berakhir' => $now->copy()->addHour()
                                    ->min($now->copy()->endOfDay()),
            'status' => 'sedang berjalan'
        ]);

        $owner_participant = Partisipan::create([
            'id_agenda' => $running_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $other_user = User::factory()->create();

        $other_participant = Partisipan::create([
            'id_agenda' => $running_agenda->id_agenda,
            'id_user' => $other_user->id,
            'status' => 'ikut'
        ]);

        $catatan_data = [
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $other_user->id,
            'judul_catatan' => 'Laravel Test',
            'catatan' => 'This is a test'
        ];

        $catatan = Catatan::create($catatan_data);

        $this->actingAs($auth_user);

        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_PERMITTED');

        $result = $catatanService->editCatatan(
                            id_catatan:     $catatan->id_catatan,
                            judul_catatan:  'Laravel Test [UPDATED]',
                            isi_catatan:    'This is a test [UPDATED]'
                        );
    }
}