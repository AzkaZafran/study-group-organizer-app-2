<?php

namespace App\Livewire;

use App\Models\Agenda;
use App\Services\AgendaService;
use Livewire\Component;

class ListAgenda extends Component
{
    private AgendaService $agendaService;

    public $list_agenda;
    public $colors = ["hsl(214, 95%, 80%)", 
                        "hsl(226, 100%, 80%)", 
                        "hsl(269, 100%, 80%)",
                        "hsl(326, 78%, 80%)",
                        "hsl(141, 84%, 80%)",
                        "hsl(48, 97%, 80%)",
                        "hsl(356, 100%, 80%)",
                        "hsl(204, 94%, 80%)"];
    public $colors_count = 8;
    public $filterStatus = 'semua';

    public function selectStatusFilter($status) {
        $this->filterStatus = $status;

        $this->list_agenda = $this->getUserAgendaFilterByStatus($status);
    }

    private function getUserAgendaFilterByStatus($status) {
        $result = collect();

        if ($status == 'semua') {
            $result = $this->agendaService->getUserAgenda();
        } else {
            $result = $this->agendaService->getUserAgendaFilterByStatus($status);
        }

        $result->each(function (Agenda $agenda) {
            $agenda->waktu_agenda = $agenda->waktu_mulai->toDateString();
            $agenda->jam_awal = $agenda->waktu_mulai->format('H:i');
            $agenda->jam_akhir = $agenda->waktu_berakhir->format('H:i');
            $agenda->nama_penyelenggara = $agenda->penyelenggara->username;
            $agenda->is_owner = $agenda->id_penyelenggara == auth()->id();
        });

        return $result;
    }

    public function mount() {
        $this->list_agenda = $this->getUserAgendaFilterByStatus($this->filterStatus);
    }

    public function boot(AgendaService $agendaService) {
        $this->agendaService = $agendaService;
    }

    public function render()
    {
        $data = [
            'list_agenda' => $this->list_agenda
        ];

        return view('livewire.list-agenda', ['wire_data' => $data]);
    }
}
