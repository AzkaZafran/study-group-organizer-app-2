<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ListCatatan;
use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\CatatanTerbaca;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ListCatatanWireTest extends TestCase {
    public function testViewListCatatanSuccess() {
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

        $auth_user_attended_agenda = Partisipan::factory()->count(3)->create([
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $owner_participant = Partisipan::create([
            'id_agenda' => $running_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $auth_user_attended_agenda->prepend($owner_participant);

        $list_partisipan = Partisipan::factory()->count(2)->create([
            'id_agenda' => $running_agenda->id_agenda,
            'status' => 'ikut'
        ]);

        $list_partisipan->prepend($owner_participant);

        $list_catatan = collect();

        $list_jumlah_catatan_terbaca = [3, 2, 0];

        for ($i=0; $i < $list_partisipan->count(); $i++) {
            $list_catatan->push(
                Catatan::factory()->create([
                    'id_author' => $list_partisipan[$i]->id_user,
                    'id_agenda' => $running_agenda->id_agenda
                ])
            );

            $list_catatan->last()->jmlh_catatan = $list_jumlah_catatan_terbaca[$i];
            $list_catatan->last()->is_author = $list_partisipan[$i]->id_user == $auth_user->id;
        }

        foreach ($list_catatan as $catatan) { 
            for ($i=0; $i < $list_partisipan->count(); $i++) {
                $status = 'belum dibaca';

                if ($i < $catatan->jmlh_catatan) {
                    $status = 'sudah dibaca';
                }

                CatatanTerbaca::create([
                    'id_user' => $list_partisipan[$i]->id_user,
                    'id_catatan' => $catatan->id_catatan,
                    'status' => $status
                ]);
            }
        }

        $this->actingAs($auth_user);

        $component_response = Livewire::test(ListCatatan::class, [
                                            'id_agenda' => $running_agenda->id_agenda
                                        ]);

        $component_response->assertViewIs('livewire.list-catatan');

        $data = $component_response->viewData('wire_data');

        $this->assertTrue(
            $list_catatan->every(
                fn (Catatan $catatan) =>
                    $data['list_catatan']->contains(
                        fn (Catatan $fetched) =>
                            $fetched->id_catatan == $catatan->id_catatan &&
                            $fetched->viewed_count == $catatan->jmlh_catatan &&
                            $fetched->author_name == $catatan->author->username &&
                            $fetched->tanggal_dibuat == $catatan->created_at->format('d/m/Y H:i') &&
                            $fetched->is_author == $catatan->is_author
                    )
            )
        );
    }

    public function testViewListCatatanFailed() {
        $component_response = Livewire::test(ListCatatan::class, [
                                            'id_agenda' => 999
                                        ]);

        $component_response->assertHasErrors([
            'list_catatan_error' => 'Pengguna tidak terautentikasi.'
        ]);

        $fetched_list_catatan = $component_response->get('list_catatan');

        $this->assertEmpty($fetched_list_catatan);
    }

    public function testViewListCatatanWithUnknownAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        $component_response = Livewire::test(ListCatatan::class, [
                                            'id_agenda' => 999
                                        ]);

        $component_response->assertHasErrors([
            'list_catatan_error' => "Agenda tidak dapat ditemukan."
        ]);

        $fetched_list_catatan = $component_response->get('list_catatan');

        $this->assertEmpty($fetched_list_catatan);
    }

    public function testViewListCatatanWithUnparticipatingUserInAgenda() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $now = now();

        $running_agenda = Agenda::factory()->create([
            'waktu_mulai' => $now->copy()->subHour()
                                ->max($now->copy()->startOfDay()),
            'waktu_berakhir' => $now->copy()->addHour()
                                    ->min($now->copy()->endOfDay()),
            'status' => 'sedang berjalan'
        ]);

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

        $component_response = Livewire::test(ListCatatan::class, [
                                            'id_agenda' => $running_agenda->id_agenda
                                        ]);

        $component_response->assertHasErrors([
            'list_catatan_error' => 'Pengguna bukan partisipan agenda ini.'
        ]);

        $fetched_list_catatan = $component_response->get('list_catatan');

        $this->assertEmpty($fetched_list_catatan);
    }
}