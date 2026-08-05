<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\CatatanTerbaca;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\CatatanService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserMarkCatatanAsReadTest extends TestCase {
    public function testMarkCatatanAsReadSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda_data = [
            'id_penyelenggara' => $auth_user->id,
            'nama_agenda' => 'belajar bareng',
            'lokasi' => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            'waktu_mulai' => $now->copy()->subHour()
                                ->max($now->copy()->startOfDay()),
            'waktu_berakhir' => $now->copy()->addHour()
                                    ->min($now->copy()->endOfDay()),
            'status' => 'sedang berjalan'
        ];

        $running_agenda = Agenda::factory()->create($running_agenda_data);

        $auth_user_participant = Partisipan::create([
                                    'id_agenda' => $running_agenda->id_agenda,
                                    'id_user' => $auth_user->id,
                                    'status' => 'ikut'
                                ]);

        $catatanService = new CatatanService();

        $list_catatan = collect();

        $numbers_of_catatan = 3;

        $this->actingAs($auth_user);

        for ($i=1; $i <= $numbers_of_catatan; $i++) { 
            $catatan = $catatanService->createCatatan(
                            id_agenda:      $running_agenda->id_agenda,
                            judul_catatan:  "Laravel Livewire {$i}",
                            isi_catatan:    'Membuat komponen interaktif menggunakan Livewire'
                        );

            $list_catatan->push($catatan);
        }

        foreach ($list_catatan as $catatan) {
            $this->assertDatabaseHas(CatatanTerbaca::class, [
                'id_catatan' => $catatan->id_catatan,
                'id_user' => $auth_user->id,
                'status' => 'belum dibaca'
            ]);
        }

        $this->actingAs($auth_user);

        $catatanService->markCatatanAsRead($list_catatan->pluck('id_catatan')->toArray());

        foreach ($list_catatan as $catatan) {
            $this->assertDatabaseHas(CatatanTerbaca::class, [
                'id_catatan' => $catatan->id_catatan,
                'id_user' => $auth_user->id,
                'status' => 'sudah dibaca'
            ]);
        }
    }

    public function testMarkCatatanAsReadFailed() {
        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $catatanService->markCatatanAsRead([999]);
    }

    public function testMarkCatatanAsReadWithEmptyArray() {
        $catatanService = new CatatanService();

        $result = $catatanService->markCatatanAsRead([]);

        $this->assertFalse($result);
    }

    public function testMarkCatatanAsReadWithUnknownCatatan() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda_data = [
            'id_penyelenggara' => $auth_user->id,
            'nama_agenda' => 'belajar bareng',
            'lokasi' => 'Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
            'waktu_mulai' => $now->copy()->subHour()
                                ->max($now->copy()->startOfDay()),
            'waktu_berakhir' => $now->copy()->addHour()
                                    ->min($now->copy()->endOfDay()),
            'status' => 'sedang berjalan'
        ];

        $running_agenda = Agenda::factory()->create($running_agenda_data);

        $auth_user_participant = Partisipan::create([
                                    'id_agenda' => $running_agenda->id_agenda,
                                    'id_user' => $auth_user->id,
                                    'status' => 'ikut'
                                ]);

        $catatanService = new CatatanService();

        $list_catatan = collect();

        $numbers_of_catatan = 3;

        $this->actingAs($auth_user);

        for ($i=1; $i <= $numbers_of_catatan; $i++) { 
            $catatan = $catatanService->createCatatan(
                            id_agenda:      $running_agenda->id_agenda,
                            judul_catatan:  "Laravel Livewire {$i}",
                            isi_catatan:    'Membuat komponen interaktif menggunakan Livewire'
                        );

            $list_catatan->push($catatan);
        }

        $list_id_catatans = $list_catatan->pluck('id_catatan')->toArray();

        $list_id_catatans[] = 999;

        $this->actingAs($auth_user);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('ONE_OR_MORE_CATATAN_NOT_FOUND');

        $catatanService->markCatatanAsRead($list_id_catatans);
    }
}