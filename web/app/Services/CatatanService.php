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

    public function createCatatan($id_agenda, $judul_catatan, $isi_catatan) {
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
        } elseif (now()->lessThan($agenda->waktu_mulai)) {
            throw new Exception('AGENDA_NOT_STARTED_YET');
        }

        $catatan_data = [
            'id_agenda' => $agenda->id_agenda,
            'id_author' => $auth_user->id,
            'judul_catatan' => $judul_catatan,
            'catatan' => $isi_catatan
        ];

        $catatan = Catatan::create($catatan_data);

        return $catatan;
    }
}