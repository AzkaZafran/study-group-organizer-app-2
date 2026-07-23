<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
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

    public function getUserAgendaFilterByStatus($status) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        if ($status == 'belum dimulai' || $status == 'sedang berjalan' || $status == 'selesai') {
            return $auth_user->agendas()
                            ->with(['participants' => function ($query) {
                                $query->withPivot('status');
                            }])->withPivot('status')
                            ->wherePivot('status', 'ikut')
                            ->where('agenda.status', $status)
                            ->get();
        }

        return -1;
    }

    public function getUserAgendaFilterByOwned() {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        return $auth_user->agendas()
                        ->with(['participants' => function ($query) {
                            $query->withPivot('status');
                        }])->withPivot('status')
                        ->where('id_penyelenggara', $auth_user->id)
                        ->get();
    }

    public function autoUpdateUserAgendaAndParticipantStatus() {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $user_agenda = $auth_user->agendas()
                                ->withPivot('status')
                                ->wherePivot('status', 'ikut')
                                ->get();
        
        $user_agenda->each(function (Agenda $agenda) {
            $waktu_mulai = $agenda->waktu_mulai;
            $waktu_berakhir = $agenda->waktu_berakhir;

            $agenda_status = 'belum dimulai';

            if (now()->lessThan($waktu_mulai)) {
                $agenda_status = 'belum dimulai';
            } elseif (now()->isBetween($waktu_mulai, $waktu_berakhir)) {
                $agenda_status = 'sedang berjalan';
            } else {
                $agenda_status = 'selesai';
            }

            

            if ($agenda->status != $agenda_status) {
                $agenda->status = $agenda_status;
                $agenda->save();
            }

            if ($agenda->status == 'sedang berjalan' || $agenda->status == 'selesai') {
                Partisipan::where('id_agenda', $agenda->id_agenda)
                            ->whereIn('status', ['pending', 'tidak ikut'])
                            ->delete();
            }
        });
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

    public function updateAgenda($id_agenda, $nama_agenda, $lokasi, $waktu_mulai, $waktu_berakhir) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $agenda = Agenda::find($id_agenda);

        if (empty($agenda)) {
            throw new Exception('AGENDA_NOT_FOUND');
        } elseif ($agenda->id_penyelenggara != $auth_user->id) {
            throw new Exception('USER_NOT_PERMITTED');
        } elseif (now()->greaterThanOrEqualTo($agenda->waktu_mulai)) {
            throw new Exception('AGENDA_ALREADY_RUNNING_OR_FINISHED');
        }

        $agenda->nama_agenda = $nama_agenda;
        $agenda->lokasi = $lokasi;
        $agenda->waktu_mulai = $waktu_mulai;
        $agenda->waktu_berakhir = $waktu_berakhir;

        $update_success = $agenda->save();

        if ($update_success) {
            return $agenda;
        }

        return false;
    }

    public function deleteAgenda($id_agenda) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $agenda = Agenda::find($id_agenda);

        if (empty($agenda)) {
            throw new Exception('AGENDA_NOT_FOUND');
        } elseif ($agenda->id_penyelenggara != $auth_user->id) {
            throw new Exception('USER_NOT_PERMITTED');
        } elseif (now()->greaterThanOrEqualTo($agenda->waktu_mulai)) {
            throw new Exception('AGENDA_ALREADY_RUNNING_OR_FINISHED');
        }

        $agenda->participants()->detach();

        $agenda->undangan()->delete();

        $success = $agenda->delete();

        return $success;
    }
}