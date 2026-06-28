<?php

namespace Tests\Unit;

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

        $agendaService = new AgendaService();
        $partisipanService = new PartisipanService();

        $this->actingAs($auth_user);

        $friend_request_data = [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $target_user->id,
            'status' => 'mutual'
        ];

        FriendRequests::create($friend_request_data);

        $new_agenda = $agendaService->createAgenda(
                            'test agenda',
                            'Jl. Jaya Sukses No. 2',
                            '2026-12-20 09:00:00',
                            '2026-12-20 12:00:00'
                        );

        $partisipanService->addParticipants($new_agenda->id_agenda, [$target_user->id]);

        $this->actingAs($target_user);

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

        $agendaService = new AgendaService();
        $partisipanService = new PartisipanService();

        $this->actingAs($auth_user);

        $new_agenda = $agendaService->createAgenda(
                            'test agenda',
                            'Jl. Jaya Sukses No. 2',
                            '2026-12-20 09:00:00',
                            '2026-12-20 12:00:00'
                        );
        
        $this->actingAs($target_user);

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

        $agendaService = new AgendaService();
        $partisipanService = new PartisipanService();

        $this->actingAs($auth_user);

        $new_agenda = $agendaService->createAgenda(
                            'test agenda',
                            'Jl. Jaya Sukses No. 2',
                            '2026-12-20 09:00:00',
                            '2026-12-20 12:00:00'
                        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('USER_ALREADY_JOIN_AGENDA');

        $result = $partisipanService->validateParticipant($new_agenda->id_agenda);
    }
}