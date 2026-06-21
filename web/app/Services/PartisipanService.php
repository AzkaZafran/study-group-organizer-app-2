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
}