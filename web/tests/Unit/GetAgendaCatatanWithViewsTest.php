<?php

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\CatatanTerbaca;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\CatatanService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetAgendaCatatanWithViewsTest extends TestCase {
    public function testGetAgendaCatatanWithViewsSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->subHour(),
            'waktu_berakhir' => now()->addHour(),
            'status' => 'sedang berjalan'
        ]);

        $owner_participant = Partisipan::create([
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $list_partisipan = Partisipan::factory()->count(2)->create([
            'id_agenda' => $agenda->id_agenda,
            'status' => 'ikut'
        ]);

        $list_partisipan->prepend($owner_participant);

        Partisipan::factory()->count(3)->create([
            'id_agenda' => $agenda->id_agenda,
            'status' => 'tidak ikut'
        ]);

        $list_catatan = collect();

        $list_jumlah_catatan_terbaca = [3, 2, 0];

        for ($i=0; $i < $list_partisipan->count(); $i++) {
            $list_catatan->push(
                Catatan::factory()->create([
                    'id_author' => $list_partisipan[$i]->id_user,
                    'id_agenda' => $agenda->id_agenda
                ])
            );

            $list_catatan->last()->jmlh_catatan = $list_jumlah_catatan_terbaca[$i];
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

        $catatanService = new CatatanService();

        $result = $catatanService->getAgendaCatatanWithViews($agenda->id_agenda);

        $this->assertTrue(
            $result->every(function ($catatan) use ($agenda, $list_partisipan, $list_catatan) {

                $catatan_from_same_agenda = $catatan->id_agenda == $agenda->id_agenda;

                if (!$catatan_from_same_agenda) {
                    return false;
                }

                $author_is_agenda_participant = $list_partisipan->contains('id_user', $catatan->id_author);

                if (!$author_is_agenda_participant) {
                    return false;
                }

                $matched_with_catatan_in_generated_list_and_same_view_count = $list_catatan
                                    ->contains(function ($generated_catatan) use ($catatan) {
                
                    $same_catatan = $generated_catatan->id_catatan == $catatan->id_catatan;
                    $same_view_count = $generated_catatan->jmlh_catatan == $catatan->viewed->count();

                    return $same_catatan &&
                            $same_view_count;
                });

                if (!$matched_with_catatan_in_generated_list_and_same_view_count) {
                    return false;
                }

                return true;

            })
        );
    }

    public function testGetAgendaCatatanWithViewsFailed() {
        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $catatanService->getAgendaCatatanWithViews(999);
    }

    public function testGetAgendaCatatanWithUnknownAgenda() {
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
        $this->expectExceptionMessage('AGENDA_NOT_FOUND');

        $result = $catatanService->getAgendaCatatanWithViews(999);
    }

    public function testGetAgendaCatatanWithUnpermittedUser() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user2 = User::create($data);

        $agenda = Agenda::factory()->create([
            'id_penyelenggara' => $auth_user->id,
            'waktu_mulai' => now()->subHour(),
            'waktu_berakhir' => now()->addHour(),
            'status' => 'sedang berjalan'
        ]);

        $owner_participant = Partisipan::create([
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ]);

        $list_partisipan = Partisipan::factory()->count(2)->create([
            'id_agenda' => $agenda->id_agenda,
            'status' => 'ikut'
        ]);

        $list_partisipan->prepend($owner_participant);

        $unattended_participants = Partisipan::factory()->count(3)->create([
            'id_agenda' => $agenda->id_agenda,
            'status' => 'tidak ikut'
        ]);

        foreach ($list_partisipan as $partisipan) {
            Catatan::factory()->create([
                'id_author' => $partisipan->id_user,
                'id_agenda' => $agenda->id_agenda
            ]);
        }

        $this->actingAs($user2);

        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_PERMITTED');

        $result = $catatanService->getAgendaCatatanWithViews($agenda->id_agenda);

        $unattended_participant = User::find($unattended_participants[0]->id_user);

        $this->actingAs($unattended_participant);

        $catatanService = new CatatanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_PERMITTED');

        $result = $catatanService->getAgendaCatatanWithViews($agenda->id_agenda);
    }
}