<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\ParticipantsNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateAgendaRequest;
use App\Http\Requests\UserUpdateAgendaRequest;
use App\Models\Agenda;
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
        $this->agendaService->autoUpdateUserAgendaAndParticipantStatus();

        $agenda_statistic = $this->agendaService->getUserAgendaStatistik();

        $data = $agenda_statistic;

        return view('dashboard', ['data' => $data]);
    }

    public function createAgenda(UserCreateAgendaRequest $request) {
        $data = $request->validated();

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
        } 
    }

    public function updateAgendaDialog($id_agenda) {
        try {
            $agenda = Agenda::find($id_agenda);

            if (empty($agenda)) {
                throw new \Exception('AGENDA_NOT_FOUND');
            } elseif ($agenda->id_penyelenggara != auth()->id()) {
                throw new \Exception('USER_NOT_PERMITTED');
            }

            $data = [
                'id_agenda' => $agenda->id_agenda,
                'nama_agenda' => $agenda->nama_agenda,
                'lokasi' => $agenda->lokasi,
                'waktu_agenda' => $agenda->waktu_mulai->format('Y-m-d'),
                'jam_mulai' => $agenda->waktu_mulai->format('H:i'),
                'jam_akhir' => $agenda->waktu_berakhir->format('H:i')
            ];

            return view('updateAgendaDialog', ['data' => $data]);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'AGENDA_NOT_FOUND' => view('errors.error', [
                    'title' => '404 Not Found',
                    'description' => 'Agenda Tidak Dapat Ditemukan.'
                ]),
                'USER_NOT_PERMITTED' => redirect()->route('dashboard')->withErrors([
                    'message' => 'Pengguna tidak memiliki izin untuk mengubah agenda ini.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }

    public function updateAgenda(UserUpdateAgendaRequest $request) {
        $data = $request->validated();

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
            $this->agendaService->updateAgenda(
                $data['id_agenda'],
                $data['nama_agenda'],
                $data['lokasi_agenda'],
                $waktu_mulai,
                $waktu_selesai
            );

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'AGENDA_NOT_FOUND' => view('errors.error', [
                    'title' => '404 Not Found',
                    'description' => 'Agenda Tidak Dapat Ditemukan.'
                ]),
                'USER_NOT_PERMITTED' => redirect()->route('dashboard')->withErrors([
                    'message' => 'Pengguna tidak memiliki izin untuk mengubah agenda ini.'
                ]),
                'AGENDA_ALREADY_RUNNING_OR_FINISHED' => redirect()->route('dashboard')->withErrors([
                    'message' => 'Agenda dalam kondisi tidak bisa diedit.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }

    public function deleteAgenda($id_agenda) {
        try {
            $this->agendaService->deleteAgenda($id_agenda);

            return back();
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'AGENDA_NOT_FOUND' => view('errors.error', [
                    'title' => '404 Not Found',
                    'description' => 'Agenda Tidak Dapat Ditemukan.'
                ]),
                'USER_NOT_PERMITTED' => back()->withErrors([
                    'message' => 'Pengguna tidak memiliki izin untuk mengubah agenda ini.'
                ]),
                'AGENDA_ALREADY_RUNNING_OR_FINISHED' => back()->withErrors([
                    'message' => 'Agenda dalam kondisi tidak bisa diedit.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }
}
