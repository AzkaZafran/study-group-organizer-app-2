<?php

namespace Tests\Feature\Livewire;

use App\Livewire\EditCatatanForm;
use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class LoadEditModalWireTest extends TestCase {
    public function testLoadEditModalSuccess() {
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

        $livewire_response = Livewire::test(EditCatatanForm::class)
                                    ->dispatch(
                                        'load-edit-catatan',
                                        id_catatan:     $catatan->id_catatan,
                                        judul_catatan:  $catatan->judul_catatan,
                                        isi_catatan:    $catatan->catatan
                                    );

        $livewire_response->assertViewIs('livewire.edit-catatan-form');

        $livewire_response->assertDispatched('show-edit-catatan-modal');

        $view_data = $livewire_response->viewData('wire_data');

        $this->assertTrue(
            $view_data['judul_catatan'] == $catatan->judul_catatan
        );

        $data = $livewire_response->getData();

        $this->assertTrue(
            $data['id_catatan'] == $catatan->id_catatan &&
            $data['judul_catatan'] == $catatan->judul_catatan &&
            $data['isi_catatan'] == $catatan->catatan
        );
    }
}