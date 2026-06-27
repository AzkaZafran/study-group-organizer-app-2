<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\ParticipantsNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateAgendaRequest;
use App\Services\AgendaService;
use App\Services\PartisipanService;
use App\Services\UndanganAgendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    protected $agendaService;
    protected $partisipanService;
    protected $undanganAgendaService;
    public function __construct(AgendaService $agendaService, PartisipanService $partisipanService, UndanganAgendaService $undanganAgendaService) {
        $this->agendaService = $agendaService;
        $this->partisipanService = $partisipanService;
        $this->undanganAgendaService = $undanganAgendaService;
    }

    public function index() {
        return view('dashboard');
    }

    public function createAgenda(UserCreateAgendaRequest $request) {
        $data = $request->validated();

        $data['participant_id'][] = auth()->id();

        $waktu_mulai = Carbon::parse(
            $data['waktu_agenda'] . ' ' . $data['jam_awal']
        );
        $waktu_selesai = Carbon::parse(
            $data['waktu_agenda'] . ' ' . $data['jam_akhir']
        );

        if ($waktu_mulai->isPast()) {
            return back()->withErrors([
                'start_time' => 'Tanggal atau waktu mulai yang diberikan harus di masa mendatang.'
            ]);
        }

        try {
            $new_agenda = $this->agendaService->createAgenda(
                $data['nama_agenda'],
                $data['lokasi_agenda'],
                $waktu_mulai,
                $waktu_selesai
            );

            $agenda_participant = $this->partisipanService->addParticipants($new_agenda->id_agenda, $data['participant_id']);

            $new_invite_code = $this->undanganAgendaService->createAgendaInviteCode($new_agenda->id_agenda);

            return back()->with('invite_code', $new_invite_code->invite_code);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login'),
                'AGENDA_NOT_FOUND' => view('errors.error', [
                    'title' => '404 Not Found',
                    'description' => 'Agenda Tidak Dapat Ditemukan.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        } catch (ParticipantsNotFoundException $e) {
            return match ($e->getMessage()) {
                'SOME_PARTICIPANTS_NOT_FOUND' => back()->withErrors([
                    'message' => 'Id user berikut tidak dapat ditemukan: ' . implode(", ", $e->getMissingIds())
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }
}
