<?php

namespace Tests\Feature;

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

        $response = $this->actingAs($auth_user)
            ->from("/agenda/{$running_agenda->id_agenda}/catatan")
            ->delete("/catatan/{$auth_user_first_catatan->id_catatan}");

        $response->assertRedirect("/agenda/{$running_agenda->id_agenda}/catatan")
                ->assertSessionHasNoErrors();
    }

    public function testDeleteCatatanFailed() {
        $response = $this->delete("/catatan/999");

        $response->assertRedirect('/login');
    }

    public function testDeleteCatatanWithUnknownCatatan() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $response = $this->actingAs($auth_user)
                        ->delete("/catatan/999");

        $response->assertViewIs('errors.error')
                ->assertViewHas([
                    'title' => '404 Not Found',
                    'description' => 'Catatan Tidak Dapat Ditemukan.'
                ]);
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

        $response = $this->actingAs($auth_user)
                        ->delete("/catatan/{$catatan->id_catatan}");

        $response->assertRedirect('/dashboard')
                ->assertSessionHasErrors([
                    'message' => 'Pengguna bukan author dari catatan ini.'
                ]);
    }
}