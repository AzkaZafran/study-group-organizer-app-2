<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\UndanganAgenda;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Str;

class UndanganAgendaService {
    public function createAgendaInviteCode(User $auth_user, Agenda $agenda) {
        $new_invite_code = '';

        do {
            $new_invite_code = Str::upper(Str::random(8));
        } while (
            UndanganAgenda::where('expired_at', '>', now())->where('invite_code', $new_invite_code)->exists()
        );

        $invite_code_data = [
            'id_agenda' => $agenda->id_agenda,
            'invite_code' => $new_invite_code,
            'expired_at' => $agenda->waktu_mulai
        ];

        $new_invite = UndanganAgenda::create($invite_code_data);

        return $new_invite;
    }

    public function searchAgendaByInviteCode($invite_code) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $invite_data = UndanganAgenda::where('expired_at', '>', now())->where('invite_code', $invite_code)->first();

        if (empty($invite_data)) {
            throw new Exception('INVALID_INVITE_CODE');
        }

        $agenda_data = $invite_data->agenda;

        return $agenda_data;
    }
}