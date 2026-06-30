<?php

namespace App\Services;

use App\Exceptions\ParticipantsNotFoundException;
use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PartisipanService {
    public function addParticipants($id_agenda, array $id_users) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $agenda_data = Agenda::find($id_agenda);

        if (!$agenda_data) {
            throw new Exception('AGENDA_NOT_FOUND');
        }

        $existingIds = User::whereIn('id', $id_users)
            ->pluck('id')
            ->toArray();

        $missingIds = array_diff($id_users, $existingIds);

        if (!empty($missingIds)) {
            throw new ParticipantsNotFoundException($missingIds);
        }

        $new_participants = collect();

        foreach ($id_users as $id_user) {
            $new_participants->add(Partisipan::create([
                'id_agenda' => $id_agenda,
                'id_user' => $id_user
            ]));
        }

        return $new_participants;
    }

    public function validateParticipant($id_agenda) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $partisipan_data = Partisipan::where('id_agenda', $id_agenda)->where('id_user', $auth_user->id)->first();

        if (empty($partisipan_data)) {
            throw new Exception('USER_IS_NOT_PARTICIPANT');
        } else if ($partisipan_data->status == 'ikut') {
            throw new Exception('USER_ALREADY_JOIN_AGENDA');
        } else if ($partisipan_data->status == 'tidak ikut') {
            throw new Exception('USER_ALREADY_REJECT_INVITE');
        }

        return true;
    }

    public function acceptAgendaInvite($id_agenda) {
        $this->validateParticipant($id_agenda);

        $auth_user = Auth::user();

        $partisipan_data = Partisipan::where('id_agenda', $id_agenda)->where('id_user', $auth_user->id)->first();

        $partisipan_data->status = 'ikut';

        $partisipan_data->save();

        return true;
    }
}