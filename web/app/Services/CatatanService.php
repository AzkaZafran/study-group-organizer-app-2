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

        $agenda_participants = $agenda->participants;

        $catatan->view()->attach(
            $agenda_participants->pluck('id'),
            [
                'status' => 'belum dibaca'
            ]
        );

        return $catatan;
    }

    public function markCatatanAsRead(array $id_catatans) {
        if (empty($id_catatans)) {
            return false;
        }
    
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $id_catatans = array_unique($id_catatans);

        $allCatatanExist = Catatan::whereIn('id_catatan', $id_catatans)
                                    ->count() === count($id_catatans);

        if (!$allCatatanExist) {
            throw new Exception('ONE_OR_MORE_CATATAN_NOT_FOUND');
        }

        $pivotData = [];

        foreach ($id_catatans as $id_catatan) {
            $pivotData[$id_catatan] = [
                'status' => 'sudah dibaca',
            ];
        }

        $auth_user->catatans()->syncWithoutDetachingOrFail($pivotData);

        return true;
    }

    public function editCatatan($id_catatan, $judul_catatan, $isi_catatan) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $catatan = Catatan::find($id_catatan);

        if (empty($catatan)) {
            throw new Exception('CATATAN_NOT_FOUND');
        } elseif ($catatan->id_author != $auth_user->id) {
            throw new Exception('USER_NOT_PERMITTED');
        }

        $catatan->judul_catatan = $judul_catatan;
        $catatan->catatan = $isi_catatan;

        $edit_success = $catatan->save();

        if ($edit_success) {
            return $catatan;
        }

        return false;
    }
}