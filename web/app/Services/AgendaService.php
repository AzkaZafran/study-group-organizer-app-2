<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Partisipan;
use Exception;
use Illuminate\Support\Facades\Auth;

class AgendaService {
    public function createAgenda($nama_agenda, $lokasi, $waktu_mulai, $waktu_berakhir) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $agenda_data = [
            'id_penyelenggara' => $auth_user->id,
            'nama_agenda' => $nama_agenda,
            'lokasi' => $lokasi,
            'waktu_mulai' => $waktu_mulai,
            'waktu_berakhir' => $waktu_berakhir
        ];

        $agenda = Agenda::create($agenda_data);

        $partisipan_data = [
            'id_agenda' => $agenda->id_agenda,
            'id_user' => $auth_user->id,
            'status' => 'ikut'
        ];

        Partisipan::create($partisipan_data);

        return $agenda;
    }
}