<?php

namespace Tests\Feature\Livewire;

use App\Livewire\EditCatatanForm;
use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class EditCatatanWireTest extends TestCase {
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

        /**
         * @var Testable
         */
        $livewire_response = Livewire::test(EditCatatanForm::class)
                                    ->dispatch(
                                        'load-edit-catatan',
                                        id_catatan:     $catatan->id_catatan,
                                        judul_catatan:  $catatan->judul_catatan,
                                        isi_catatan:    $catatan->catatan
                                    )->set('isi_catatan', 'This is a test [UPDATED]')
                                    ->call('editCatatan');

        $livewire_response->assertHasNoErrors()
                        ->assertDispatched('catatan-edited');

        $this->assertDatabaseHas(Catatan::class, [
            'id_catatan' => $catatan->id_catatan,
            'id_agenda' => $running_agenda->id_agenda,
            'id_author' => $auth_user->id,
            'judul_catatan' => $catatan->judul_catatan,
            'catatan' => 'This is a test [UPDATED]'
        ]);
    }

    public function testEditCatatanFailed() {
        $catatan = Catatan::factory()->create();

        /**
         * @var Testable
         */
        $livewire_response = Livewire::test(EditCatatanForm::class)
                                    ->dispatch(
                                        'load-edit-catatan',
                                        id_catatan:     $catatan->id_catatan,
                                        judul_catatan:  $catatan->judul_catatan,
                                        isi_catatan:    $catatan->catatan
                                    )->set('isi_catatan', 'This is a test [UPDATED]')
                                    ->call('editCatatan');

        $livewire_response->assertHasErrors([
            'edit_catatan_error' => 'Pengguna tidak terautentikasi.'
        ]);

        $livewire_response->assertNotDispatched('catatan-edited');

        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        /**
         * @var Testable
         */
        $livewire_response = Livewire::test(EditCatatanForm::class)
                                    ->dispatch(
                                        'load-edit-catatan',
                                        id_catatan:     $catatan->id_catatan,
                                        judul_catatan:  $catatan->judul_catatan,
                                        isi_catatan:    $catatan->catatan
                                    )->set('isi_catatan', '')
                                    ->call('editCatatan');

        $livewire_response->assertHasErrors([
            'isi_catatan' => 'The isi catatan field is required.'
        ]);

        $livewire_response->assertNotDispatched('catatan-edited');
    }

    public function testEditCatatanWithUnknownCatatan() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        /**
         * @var Testable
         */
        $livewire_response = Livewire::test(EditCatatanForm::class)
                                    ->dispatch(
                                        'load-edit-catatan',
                                        id_catatan:     999,
                                        judul_catatan:  'Laravel Test',
                                        isi_catatan:    'This is a test'
                                    )->call('editCatatan');

        $livewire_response->assertHasErrors([
            'edit_catatan_error' => "Catatan tidak dapat ditemukan."
        ]);

        $livewire_response->assertNotDispatched('catatan-edited');
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

        /**
         * @var Testable
         */
        $livewire_response = Livewire::test(EditCatatanForm::class)
                                    ->dispatch(
                                        'load-edit-catatan',
                                        id_catatan:     $catatan->id_catatan,
                                        judul_catatan:  $catatan->judul_catatan,
                                        isi_catatan:    $catatan->catatan
                                    )->set('isi_catatan', 'This is a test [UPDATED]')
                                    ->call('editCatatan');

        $livewire_response->assertHasErrors([
            'edit_catatan_error' => 'Pengguna bukan author dari catatan ini.'
        ]);

        $livewire_response->assertNotDispatched('catatan-edited');
    }
}