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
    public $filterOwned = false;

    public function selectStatusFilter($status) {
        $this->filterOwned = false;
    
        $this->filterStatus = $status;

        $this->list_agenda = $this->getUserAgendaFilterByStatus($status);
    }

    public function selectOwnedFilter() {
        $this->filterStatus = '';

        $this->filterOwned = true;

        $this->list_agenda = $this->getUserAgendaFilterByOwned();
    }

    private function getUserAgendaFilterByStatus($status) {
        $result = collect();

        if ($status == 'semua') {
            $result = $this->agendaService->getUserAgenda();
        } else {
            $result = $this->agendaService->getUserAgendaFilterByStatus($status);
        }

        $result = $this->formatted_list_agenda($result);

        return $result;
    }

    public function getUserAgendaFilterByOwned() {
        $result = $this->agendaService->getUserAgendaFilterByOwned();

        $result = $this->formatted_list_agenda($result);

        return $result;
    }

    private function formatted_list_agenda($list_agenda) {
        $list_agenda->each(function (Agenda $agenda) {
            $agenda->waktu_agenda = $agenda->waktu_mulai->toDateString();
            $agenda->jam_awal = $agenda->waktu_mulai->format('H:i');
            $agenda->jam_akhir = $agenda->waktu_berakhir->format('H:i');
            $agenda->nama_penyelenggara = $agenda->penyelenggara->username;
            $agenda->is_owner = $agenda->id_penyelenggara == auth()->id();
        });

        return $list_agenda;
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
