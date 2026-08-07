<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\CatatanTerbaca;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\CatatanService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserDeleteCatatanTest extends TestCase {
    public function testDeleteCatatanSuccess() {
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

        $list_partisipan = Partisipan::factory()->count(2)->create([
            'id_agenda' => $running_agenda->id_agenda,
            'status' => 'ikut'
        ]);

        $list_partisipan->prepend($owner_participant);

        $list_partisipan = $list_partisipan->map(function (Partisipan $partisipan) {
                                return $partisipan->belongsTo(User::class,
                                                                'id_user',
                                                                'id')->first();
                            });

        $catatan_data = [
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $auth_user->id,
            'judul_catatan' => 'Laravel Test',
            'catatan' => 'This is a test'
        ];

        /**
         * @var Collection<int, Catatan>
         */
        $list_auth_user_catatan = Catatan::factory()->count(3)->create([
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $auth_user->id
        ]);

        foreach ($list_auth_user_catatan as $catatan) {
            foreach ($list_partisipan as $partisipan) {
                CatatanTerbaca::create([
                    'id_catatan' => $catatan->id_catatan,
                    'id_user' => $partisipan->id,
                    'status' => 'sudah dibaca'
                ]);
            }
        }

        $auth_user_first_catatan = $list_auth_user_catatan->pop();

        $other_auth_user_catatans = $list_auth_user_catatan;

        $this->actingAs($auth_user);

        $catatanService = new CatatanService();

        $result = $catatanService->deleteCatatan($auth_user_first_catatan->id_catatan);

        $this->assertTrue($result);

        $this->assertDatabaseMissing(CatatanTerbaca::class, [
            'id_catatan' => $auth_user_first_catatan->id_catatan
        ]);

        $this->assertDatabaseMissing(Catatan::class, [
            'id_catatan' => $auth_user_first_catatan->id_catatan
        ]);

        foreach ($other_auth_user_catatans as $catatan) {
            $this->assertDatabaseHas(CatatanTerbaca::class, [
                'id_catatan' => $catatan->id_catatan
            ]);

            $this->assertDatabaseHas(Catatan::class, [
                'id_catatan' => $catatan->id_catatan
            ]);
        }
    }

    public function testDeleteCatatanFailed() {
        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $catatanService->deleteCatatan(999);
    }

    public function testDeleteCatatanWithUnknownCatatan() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('CATATAN_NOT_FOUND');

        $result = $catatanService->deleteCatatan(999);
    }

    public function testDeleteCatatanWithUserNotAuthor() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $other_user = User::factory()->create();

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

        $result = $catatanService->deleteCatatan($catatan->id_catatan);
    }
}