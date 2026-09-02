<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use App\Services\PartisipanService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AcceptAgendaInviteTest extends TestCase {
    public function testAcceptAgendaInviteSuccess() {
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

        $target_user = User::create($data);

        $friend_request_data = [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $target_user->id,
            'status' => 'mutual'
        ];

        FriendRequests::create($friend_request_data);

        $new_agenda = Agenda::create([
            'id_penyelenggara' => $auth_user->id,
            'nama_agenda' => 'test agenda',
            'lokasi' => 'Jl. Jaya Sukses No. 2',
            'waktu_mulai' => '2026-12-20 09:00:00',
            'waktu_berakhir' => '2026-12-20 12:00:00'
        ]);

        Partisipan::create([
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' =>'ikut'
        ]);

        Partisipan::create([
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $target_user->id,
            'status' =>'pending'
        ]);

        $this->actingAs($target_user);

        $partisipanService = new PartisipanService();

        $partisipanService->acceptAgendaInvite($new_agenda->id_agenda);

        $this->assertDatabaseHas(Partisipan::class, [
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $target_user->id,
            'status' => 'ikut'
        ]);
    }
}