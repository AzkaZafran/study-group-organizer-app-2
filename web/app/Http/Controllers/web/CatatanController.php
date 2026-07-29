<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\User;
use App\Services\AgendaService;
use App\Services\CatatanService;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    protected $catatanService;
    protected $agendaService;

    public function __construct(CatatanService $catatanService, AgendaService $agendaService) {
        $this->catatanService = $catatanService;
        $this->agendaService = $agendaService;
    }

    public function index($id_agenda) {
        try {
            $list_agenda = $this->agendaService->getUserAgenda();

            $selected_agenda = $this->agendaService->findUserAgenda($id_agenda);

            $list_catatan_agenda = $this->catatanService->getAgendaCatatanWithViews($id_agenda);

            $list_catatan_agenda->each(function (Catatan $catatan) {
                $catatan->view_count = $catatan->viewed->count();
            });

            $formatted_list_agenda = $list_agenda->map(function (Agenda $agenda) {
                                            return [
                                                'id_agenda' => $agenda->id_agenda,
                                                'nama_agenda' => $agenda->nama_agenda
                                            ];
                                        });

            $nama_penyelenggara = $selected_agenda->penyelenggara->username;
            $participants = $selected_agenda->participants()
                                            ->withPivot('status')
                                            ->wherePivot('status', 'ikut')
                                            ->get();
            $formatted_participants = $participants->map(function (User $partisipan) {
                return [
                    'nama_partisipan' => $partisipan->username
                ];
            });
            $agenda_status = $selected_agenda->status;
            
            $data = [
                'list_agenda' => $formatted_list_agenda,
                'list_catatan' => $list_catatan_agenda,
                'nama_penyelenggara' => $nama_penyelenggara,
                'list_partisipan' => $formatted_participants,
                'agenda_status' => $agenda_status
            ];

            return view('catatan', ['data' => $data]);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login'),
                'AGENDA_NOT_FOUND' => view('errors.error', [
                    'title' => '404 Not Found',
                    'description' => 'Agenda Tidak Dapat Ditemukan.'
                ]),
                'USER_NOT_PERMITTED' => redirect()->route('dashboard')->withErrors([
                    'message' => 'Pengguna bukan partisipan agenda ini.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }
}
