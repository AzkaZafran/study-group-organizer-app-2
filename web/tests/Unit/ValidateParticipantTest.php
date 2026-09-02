<?php

namespace Tests\Unit;

use App\Models\Agenda;
use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\User;
use App\Services\AgendaService;
use App\Services\PartisipanService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ValidateParticipantTest extends TestCase {
    public function testValidateParticipantSuccess() {
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

        $result = $partisipanService->validateParticipant($new_agenda->id_agenda);

        $this->assertTrue($result);
    }

    public function testValidateParticipantFailed() {
        $partisipanService = new PartisipanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_NOT_AUTHENTICATED');

        $result = $partisipanService->validateParticipant(999);
    }

    public function testValidateParticipantWithRegularUser() {
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
        
        $this->actingAs($target_user);

        $partisipanService = new PartisipanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_IS_NOT_PARTICIPANT');

        $result = $partisipanService->validateParticipant($new_agenda->id_agenda);
    }

    public function testValidateParticipantWithUserAlreadyJoin() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

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

        $this->actingAs($auth_user);

        $partisipanService = new PartisipanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_ALREADY_JOIN_AGENDA');

        $result = $partisipanService->validateParticipant($new_agenda->id_agenda);
    }

    public function testValidateParticipantWithUserAlreadyRejectInvite() {
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
            'status' => 'tidak ikut'
        ]);

        $this->actingAs($target_user);

        $partisipanService = new PartisipanService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_ALREADY_REJECT_INVITE');

        $result = $partisipanService->validateParticipant($new_agenda->id_agenda);
    }
}