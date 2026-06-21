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
    public function createAgendaInviteCode($id_agenda) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $agenda_data = Agenda::find($id_agenda);

        if (!$agenda_data) {
            throw new Exception('AGENDA_NOT_FOUND');
        }

        $new_invite_code = '';

        do {
            $new_invite_code = Str::upper(Str::random(8));
        } while (
            UndanganAgenda::where('expired_at', '>', now())->where('invite_code', $new_invite_code)->exists()
        );

        $invite_code_data = [
            'id_agenda' => $id_agenda,
            'invite_code' => $new_invite_code,
            'expired_at' => now()->addDay()
        ];

        $new_invite = UndanganAgenda::create($invite_code_data);

        return $new_invite;
    }
}