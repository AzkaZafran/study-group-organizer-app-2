<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PartisipanService;
use App\Services\UndanganAgendaService;
use Illuminate\Http\Request;

class AgendaInviteController extends Controller
{
    protected $undanganAgendaService;
    protected $partisipanService;

    public function __construct(UndanganAgendaService $undanganAgendaService, PartisipanService $partisipanService) {
        $this->undanganAgendaService = $undanganAgendaService;
        $this->partisipanService = $partisipanService;
    }

    public function agendaInviteDialog($invite_code) {
        try {
            $agenda = $this->undanganAgendaService->searchAgendaByInviteCode($invite_code);

            $this->partisipanService->validateParticipant($agenda->id_agenda);

            $agenda_owner = $agenda->penyelenggara;

            $data = [
                'agenda_name' => $agenda->nama_agenda,
                'inviter_name' => $agenda_owner->username
            ];

            return view('agendaInviteDialog', ['data' => $data]);
            
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login'),
                'INVALID_INVITE_CODE' => redirect('/dashboard')->withErrors([
                    'message' => 'Tautan undangan yang digunakan tidak valid.'
                ]),
                'USER_IS_NOT_PARTICIPANT' => redirect('/dashboard')->withErrors([
                    'message' => 'Pengguna tidak termasuk partisipan dari agenda ini.'
                ]),
                'USER_ALREADY_REJECT_INVITE' => redirect('/dashboard')->withErrors([
                    'message' => 'Pengguna telah menolak undangan ini.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
        
    }
}
