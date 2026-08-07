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
    public function testViewAndRefreshListCatatanSuccess() {
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

        $auth_user_attended_agenda = $auth_user_attended_agenda->map(function (Partisipan $partisipan) {
                                        return $partisipan->agenda;
                                    });

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

        $list_catatan = collect();

        foreach ($list_partisipan as $key => $partisipan) {
            $catatan_data = [
                'is_updated' => [
                    'id_author' => $partisipan->id,
                    'id_agenda' => $running_agenda->id_agenda,
                    'created_at' => $now->copy()->subMinutes(30)
                                        ->max($now->copy()->startOfDay()),
                    'updated_at' => $now->copy()->addMinutes(30)
                                        ->min($now->copy()->endOfDay())
                ],
                'is_not_updated' => [
                    'id_author' => $partisipan->id,
                    'id_agenda' => $running_agenda->id_agenda,
                    'created_at' => $now->copy(),
                    'updated_at' => $now->copy()
                ]
            ];

            $selected_data = ($key + 1) % 2 == 0 ? $catatan_data['is_not_updated'] : $catatan_data['is_updated'];

            $new_catatan = Catatan::factory()->create($selected_data);

            $new_catatan->is_author = $partisipan->id == $auth_user->id;

            $list_catatan->push($new_catatan);

            foreach ($list_partisipan as $partisipan_j) {
                CatatanTerbaca::create([
                    'id_user' => $partisipan_j->id,
                    'id_catatan' => $new_catatan->id_catatan,
                    'status' => 'belum dibaca'
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
                            $fetched->viewed_count == 1 &&
                            $fetched->author_name == $catatan->author->username &&
                            $fetched->tanggal_diubah == $catatan->updated_at->format('d/m/Y H:i') &&
                            $fetched->is_author == $catatan->is_author &&
                            $fetched->is_updated == $catatan->updated_at->greaterThan($catatan->created_at)
                    )
            )
        );

        expect(
            $data['list_catatan']->values()->every(function ($catatan, $index) use ($data) {

                if ($index === 0) {
                    return true;
                }

                return $data['list_catatan'][$index - 1]->updated_at
                    ->greaterThanOrEqualTo($catatan->updated_at);
            })
        )->toBeTrue();

        $new_catatan = Catatan::factory()->create([
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $auth_user->id
        ]);

        $new_catatan->is_author = true;

        $list_catatan->push($new_catatan);

        foreach ($list_partisipan as $partisipan) {
            CatatanTerbaca::create([
                'id_user' => $partisipan->id,
                'id_catatan' => $new_catatan->id_catatan,
                'status' => 'belum dibaca'
            ]);
        }

        $this->actingAs($auth_user);

        $component_response = $component_response->dispatch('catatan-created');

        $component_response->assertViewIs('livewire.list-catatan');

        $new_data = $component_response->viewData('wire_data');

        $this->assertTrue(
            $list_catatan->every(
                fn (Catatan $catatan) =>
                    $new_data['list_catatan']->contains(
                        fn (Catatan $fetched) =>
                            $fetched->id_catatan == $catatan->id_catatan &&
                            $fetched->viewed_count == 1 &&
                            $fetched->author_name == $catatan->author->username &&
                            $fetched->tanggal_diubah == $catatan->updated_at->format('d/m/Y H:i') &&
                            $fetched->is_author == $catatan->is_author &&
                            $fetched->is_updated == $catatan->updated_at->greaterThan($catatan->created_at)
                    )
            )
        );

        expect(
            $data['list_catatan']->values()->every(function ($catatan, $index) use ($data) {

                if ($index === 0) {
                    return true;
                }

                return $data['list_catatan'][$index - 1]->updated_at
                    ->greaterThanOrEqualTo($catatan->updated_at);
            })
        )->toBeTrue();
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

    public function testShowEditModalSuccess() {
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

        $component_response = Livewire::test(ListCatatan::class, [
                                            'id_agenda' => $running_agenda->id_agenda
                                        ])->call('showEditModal', id_catatan: $catatan->id_catatan);

        $component_response->assertDispatched('load-edit-catatan', 
                                                id_catatan: $catatan->id_catatan, 
                                                judul_catatan: $catatan->judul_catatan,
                                                isi_catatan: $catatan->catatan);

        $component_response->assertHasNoErrors();

        $component_response->assertDontSee('Fatal Error!');
        $component_response->assertDontSee('Pengguna bukan partisipan agenda atau bukan author dari catatan ini.');
    }

    public function testShowEditModalFailed() {
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

        $component_response = Livewire::test(ListCatatan::class, [
                                            'id_agenda' => $running_agenda->id_agenda
                                        ]);

        $this->actingAsGuest();

        $component_response = $component_response->call('showEditModal', id_catatan: $catatan->id_catatan);

        $component_response->assertHasErrors([
            'edit_catatan_error' => 'Pengguna tidak terautentikasi.'
        ]);

        $component_response->assertSee('Fatal Error!');
        $component_response->assertSee('Pengguna tidak terautentikasi.');
    }

    public function testShowEditModalWithUnknownCatatan() {
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

        $this->actingAs($auth_user);

        $component_response = Livewire::test(ListCatatan::class, [
                                            'id_agenda' => $running_agenda->id_agenda
                                        ])->call('showEditModal', id_catatan: 999);

        $component_response->assertHasErrors([
            'edit_catatan_error' => 'Catatan tidak dapat ditemukan.'
        ]);

        $component_response->assertSee('Fatal Error!');
        $component_response->assertSee('Catatan tidak dapat ditemukan.');
    }

    public function testShowEditModalWithUserNotAuthor() {
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

        $other_user_participant = Partisipan::create([
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

        $component_response = Livewire::test(ListCatatan::class, [
                                            'id_agenda' => $running_agenda->id_agenda
                                        ])->call('showEditModal', id_catatan: $catatan->id_catatan);

        $component_response->assertHasErrors([
            'edit_catatan_error' => 'Pengguna bukan partisipan agenda atau bukan author dari catatan ini.'
        ]);

        $component_response->assertSee('Fatal Error!');
        $component_response->assertSee('Pengguna bukan partisipan agenda atau bukan author dari catatan ini.');
    }
}