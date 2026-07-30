<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\CatatanTerbaca;
use Exception;
use Illuminate\Support\Facades\Auth;

class CatatanService {
    public function getAgendaCatatanWithViews($id_agenda) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $agenda = Agenda::find($id_agenda);

        if (empty($agenda)) {
            throw new Exception('AGENDA_NOT_FOUND');
        }

        $is_participant = $agenda->participants()
                                ->withPivot('status')
                                ->wherePivot('status', 'ikut')
                                ->get()
                                ->contains('id', $auth_user->id);

        if (!$is_participant) {
            throw new Exception('USER_NOT_PERMITTED');
        }

        $list_catatan = $agenda->catatan()
                                ->with('author')
                                ->withCount('viewed')
                                ->get();

        return $list_catatan;
    }
}