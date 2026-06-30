<?php

use Livewire\Component;
use App\Models\Agenda;
use App\Services\AgendaService;

new class extends Component
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
    public $color_counter = 0;

    private function getUserAgenda() {
        $result = $this->agendaService->getUserAgenda();

        $result->each(function (Agenda $agenda) {
            $waktu_agenda = $agenda->waktu_mulai->toDateString();
            $jam_awal = $agenda->waktu_mulai->format('H:i');
            $jam_akhir = $agenda->waktu_berakhir->format('H:i');
            
            return [
                'nama_agenda' => $agenda->nama_agenda,
                'nama_penyelenggara' => $agenda->penyelenggara->username,
                'lokasi' => $agenda->lokasi,
                'waktu_agenda' => $waktu_agenda,
                'jam_awal' => $jam_awal,
                'jam_akhir' => $jam_akhir
            ];
        });

        return $result;
    }

    public function boot(AgendaService $agendaService) {
        $this->agendaService = $agendaService;

        $this->list_agenda = $this->getUserAgenda();
    }

};
?>

<div>
    <div class="d-flex flex-row gap-2 mb-3">
        <button type="button" class="btn {{ 'selected' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Semua</button>
        <button type="button" class="btn {{ '' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Selesai</button>
        <button type="button" class="btn {{ '' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Sedang Berjalan</button>
        <button type="button" class="btn {{ '' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Belum Dimulai</button>
        <button type="button" class="btn {{ '' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Dibuat</button>
    </div>

    <div class="container d-flex flex-row flex-wrap gap-3 mb-4">

        @foreach ($list_agenda as $agenda)
            <div class="card shadow" style="width: 22rem;"
            data-bs-toggle="modal" data-bs-target={{ '#modaldetailagenda' . $loop->index }}>
                <div class="btn text-start w-100 h-100">
                    <div class="card-body d-flex flex-column w-100 h-100">
                    <div class="d-flex flex-row gap-1 mb-1">
                        @if ($agenda->status == 'selesai')
                            <span class="badge rounded-pill fs-6" style="background-color: #00cc00;">{{ 'Selesai' }}</span>
                        @elseif($agenda->status == 'sedang berjalan')
                            <span class="badge rounded-pill fs-6" style="background-color: #dfc500;">{{ 'Sedang Berjalan' }}</span>
                        @elseif($agenda->status == 'belum dimulai')
                            <span class="badge rounded-pill text-bg-primary fs-6">{{ 'Belum Dimulai' }}</span>
                        @endif

                        @if ($agenda->is_owner)
                            <span class="badge rounded-pill fs-6" style="background-color: #1E3A8A;">{{ 'Owner' }}</span>
                        @endif
                    </div>
                        <h5 class="card-title text-truncate mb-3" style="color: #1E3A8A">{{ $agenda['nama_agenda'] }}</h5>

                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div class="card-text text3 col">{{ $agenda['nama_penyelenggara'] }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="card-text text3 col text-truncate">{{ $agenda['lokasi'] }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="card-text text3 col">{{ $agenda['waktu_agenda'] }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="card-text text3 col">{{ $agenda['jam_awal'] . ' - ' . $agenda['jam_akhir'] . " WIB" }}</div>
                        </div>
                        
                        <p class="card-text text4 text-center mt-3" >Klik untuk detail</p>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($list_agenda as $agenda)
            <div class="modal fade" id={{ 'modaldetailagenda' . $loop->index }} tabindex="-1" 
                aria-labelledby="modalDetailAgendaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-wrap" id="modalDetailAgendaLabel" style="color: #1E3A8A;">Agenda: {{ "Belajar Bareng" }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex flex-row gap-1 mb-3">
                                @if ($agenda->status == 'selesai')
                                    <span class="badge rounded-pill fs-6" style="background-color: #00cc00;">{{ 'Selesai' }}</span>
                                @elseif($agenda->status == 'sedang berjalan')
                                    <span class="badge rounded-pill fs-6" style="background-color: #dfc500;">{{ 'Sedang Berjalan' }}</span>
                                @elseif($agenda->status == 'belum dimulai')
                                    <span class="badge rounded-pill text-bg-primary fs-6">{{ 'Belum Dimulai' }}</span>
                                @endif

                                @if ($agenda->is_owner)
                                    <span class="badge rounded-pill fs-6" style="background-color: #1E3A8A;">{{ 'Owner' }}</span>
                                @endif
                            </div>
                            <div class="row mb-1">
                                <div class="card-text text2 col-1">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div class="card-text text3 col">{{ $agenda->is_owner }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="card-text text2 col-1">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="card-text text3 col text-truncate">{{ $agenda->lokasi }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="card-text text2 col-1">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>
                                <div class="card-text text3 col">{{ $agenda->waktu_agenda }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="card-text text2 col-1">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div class="card-text text3 col">{{ $agenda->jam_awal . ' - ' . $agenda->jam_akhir . " WIB" }}</div>
                            </div>

                            <div class="border-top my-3"></div>

                            <div class="d-flex flex-row gap-2 align-items-center mb-3">
                                <i class="fa-solid fa-user-group fa-lg"></i>
                                <h5 class="m-0 p-0">Partisipan :</h5>
                            </div>

                            <div class="d-flex flex-column gap-2 overflow-auto " style="height: 180px;">
                                <span class="badge rounded-pill fs-6 fw-normal" 
                                    style="background-color: hsl(214, 95%, 80%); color: #111827; width: fit-content;">
                                    {{ 'azkazafran78' }}
                                </span>
                                <span class="badge rounded-pill fs-6 fw-normal" 
                                    style="background-color: hsl(226, 100%, 80%); color: #111827; width: fit-content;">
                                    {{ 'azkazafran78' }}
                                </span>
                                <span class="badge rounded-pill fs-6 fw-normal" 
                                    style="background-color: hsl(269, 100%, 80%); color: #111827; width: fit-content;">
                                    {{ 'azkazafran78' }}
                                </span>
                                <span class="badge rounded-pill fs-6 fw-normal" 
                                    style="background-color: hsl(326, 78%, 80%); color: #111827; width: fit-content;">
                                    {{ 'azkazafran78' }}
                                </span>
                                <span class="badge rounded-pill fs-6 fw-normal" 
                                    style="background-color: hsl(141, 84%, 80%); color: #111827; width: fit-content;">
                                    {{ 'azkazafran78' }}
                                </span>
                                <span class="badge rounded-pill fs-6 fw-normal" 
                                    style="background-color: hsl(48, 97%, 80%); color: #111827; width: fit-content;">
                                    {{ 'azkazafran78' }}
                                </span>
                                <span class="badge rounded-pill fs-6 fw-normal" 
                                    style="background-color: hsl(356, 100%, 80%); color: #111827; width: fit-content;">
                                    {{ 'azkazafran78' }}
                                </span>
                                <span class="badge rounded-pill fs-6 fw-normal" 
                                    style="background-color: hsl(204, 94%, 80%); color: #111827; width: fit-content;">
                                    {{ 'azkazafran78' }}
                                </span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a class="btn" role="button" style="background-color: #492201; color: white;">
                                <i class="fa-solid fa-book-open me-1"></i>
                                Catatan
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>