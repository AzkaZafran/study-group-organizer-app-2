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

    public function getUserAgenda() {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        return $auth_user->agendas()
                        ->with(['participants' => function ($query) {
                            $query->withPivot('status');
                        }])->withPivot('status')
                        ->wherePivot('status', 'ikut')
                        ->get();
    }

    /**
     * Returns user's agenda statistic.
     *
     * @return array{
     *     total_user_agenda: int,
     *     total_user_agenda_selesai: int,
     *     total_user_agenda_sedang_berjalan: int,
     *     total_user_agenda_belum_dimulai: int
     * }
     */
    public function getUserAgendaStatistik() {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $total_user_agenda = $auth_user->agendas()
                                    ->withPivot('status')
                                    ->wherePivot('status','ikut')
                                    ->count();

        $total_user_agenda_selesai = $auth_user->agendas()
                                    ->withPivot('status')
                                    ->wherePivot('status','ikut')
                                    ->where('agenda.status', 'selesai')
                                    ->count();

        $total_user_agenda_sedang_berjalan = $auth_user->agendas()
                                    ->withPivot('status')
                                    ->wherePivot('status','ikut')
                                    ->where('agenda.status', 'sedang berjalan')
                                    ->count();

        $total_user_agenda_belum_dimulai = $auth_user->agendas()
                                    ->withPivot('status')
                                    ->wherePivot('status','ikut')
                                    ->where('agenda.status', 'belum dimulai')
                                    ->count();

        return [
            'total_user_agenda' => $total_user_agenda,
            'total_user_agenda_selesai' => $total_user_agenda_selesai,
            'total_user_agenda_sedang_berjalan' => $total_user_agenda_sedang_berjalan,
            'total_user_agenda_belum_dimulai' => $total_user_agenda_belum_dimulai
        ];
    }
}