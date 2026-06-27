@extends('layouts.appWithNavbar')

@section('navbar-content')
    <div class="container">
        <h3 class="fw-medium mb-3" style="color: black;">Statistik</h3>

        <div class="d-flex flex-row gap-3 mb-4">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Total Agenda</h5>
                    <p class="fs-4 fw-medium">{{ 10 }}</p>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Agenda Terselesaikan</h5>
                    <p class="fs-4 fw-medium">{{ 8 }}</p>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Agenda Sedang Berjalan</h5>
                    <p class="fs-4 fw-medium">{{ 1 }}</p>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Agenda Belum Dimulai</h5>
                    <p class="fs-4 fw-medium">{{ 1 }}</p>
                </div>
            </div>
        </div>

        <h3 class="fw-medium mb-3" style="color: black;">Buat Agenda</h3>

        <div class="card shadow mb-4" style="width: 18rem;">
            <button type="button" class="btn text-start w-100 h-100" data-bs-toggle="modal" data-bs-target="#modalTambahAgenda">
                <div class="card-body d-flex flex-column w-100 h-100">
                    <h5 class="card-title text-center" style="color: #1E3A8A;">Tambah Agenda</h5>
                    <div class="d-flex justify-content-center align-items-center" style="height: 140px;">
                        <i class="fa-solid fa-plus text-center" style="font-size: 80px; color: #1E3A8A;"></i>
                    </div>
                    <p class="card-text text-center mt-auto">Klik untuk tambah</p>
                </div>
            </button>
        </div>

        <h3 class="fw-medium mb-3" style="color: black;">Daftar Agenda</h3>

        <div class="d-flex flex-row gap-2 mb-3">
            <button type="button" class="btn {{ 'selected' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Semua</button>
            <button type="button" class="btn {{ '' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Selesai</button>
            <button type="button" class="btn {{ '' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Sedang Berjalan</button>
            <button type="button" class="btn {{ '' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Belum Dimulai</button>
            <button type="button" class="btn {{ '' === 'selected' ? 'btn-purple' : 'btn-outline-purple' }}">Dibuat</button>
        </div>

        <div class="container d-flex flex-row flex-wrap gap-3 mb-4">

            <div class="card shadow" style="width: 22rem;"
            data-bs-toggle="modal" data-bs-target={{ '#modaldetailagenda' . '1' }}>
                <div class="btn text-start w-100 h-100">
                    <div class="card-body d-flex flex-column w-100 h-100">
                    <div class="d-flex flex-row gap-1 mb-1">
                        <span class="badge rounded-pill fs-6" style="background-color: #00cc00;">{{ 'Selesai' }}</span>
                        <span class="badge rounded-pill fs-6" style="background-color: #1E3A8A;">{{ 'Owner' }}</span>
                    </div>
                        <h5 class="card-title text-truncate mb-3" style="color: #1E3A8A">{{ 'Belajar Bareng' }}</h5>

                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div class="card-text text3 col">{{ 'azkazafran78' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="card-text text3 col text-truncate">{{ 'Telkom University' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="card-text text3 col">{{ '12 Februari 2026' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="card-text text3 col">{{ '12:00' . ' - ' . '14:00' . " WIB" }}</div>
                        </div>
                        
                        <p class="card-text text4 text-center mt-3" >Klik untuk detail</p>
                    </div>
                </div>
            </div>

            <div class="card shadow" style="width: 22rem;" 
                data-bs-toggle="modal" data-bs-target={{ '#modaldetailagenda' . '2' }}>
                <div class="btn text-start w-100 h-100">
                    <div class="card-body d-flex flex-column w-100 h-100">
                    <div class="d-flex flex-row gap-1 mb-1">
                        <span class="badge rounded-pill fs-6" style="background-color: #dfc500;">{{ 'Sedang Berjalan' }}</span>
                    </div>
                        <h5 class="card-title text-truncate mb-3" style="color: #1E3A8A">{{ 'Belajar Laravel' }}</h5>

                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div class="card-text text3 col">{{ 'pelajar39' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="card-text text3 col text-truncate">{{ 'Telkom University' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="card-text text3 col">{{ '9 Mei 2026' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="card-text text3 col">{{ '09:00' . ' - ' . '11:00' . " WIB" }}</div>
                        </div>
                        
                        <p class="card-text text4 text-center mt-3" >Klik untuk detail</p>
                    </div>
                </div>
            </div>

            <div class="card shadow" style="width: 22rem;">
                <div class="btn text-start w-100 h-100">
                    <div class="card-body d-flex flex-column w-100 h-100">
                    <div class="d-flex flex-row gap-1 mb-1">
                        <span class="badge rounded-pill text-bg-primary fs-6">{{ 'Belum Dimulai' }}</span>
                    </div>
                        <h5 class="card-title text-truncate mb-3" style="color: #1E3A8A">{{ 'Web Framework' }}</h5>

                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div class="card-text text3 col">{{ 'pelajar40' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="card-text text3 col text-truncate">{{ 'Telkom University' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="card-text text3 col">{{ '28 Juni 2030' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="card-text text3 col">{{ '08:00' . ' - ' . '10:00' . " WIB" }}</div>
                        </div>
                        
                        <p class="card-text text4 text-center mt-3" >Klik untuk detail</p>
                    </div>
                </div>
            </div>

            <div class="card shadow" style="width: 22rem;" 
                data-bs-toggle="modal" data-bs-target={{ '#modaldetailagenda' . '4' }}>
                <div class="btn text-start w-100 h-100">
                    <div class="card-body d-flex flex-column w-100 h-100">
                    <div class="d-flex flex-row gap-1 mb-1">
                        <span class="badge text-bg-primary rounded-pill fs-6">{{ 'Belum Dimulai' }}</span>
                        <span class="badge rounded-pill fs-6" style="background-color: #1E3A8A;">{{ 'Owner' }}</span>
                    </div>
                        <h5 class="card-title text-truncate mb-3" style="color: #1E3A8A">{{ 'Belajar Bareng' }}</h5>

                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div class="card-text text3 col">{{ 'azkazafran78' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="card-text text3 col text-truncate">{{ 'Telkom University' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="card-text text3 col">{{ '30 Agustus 2026' }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="card-text text3 col">{{ '10:00' . ' - ' . '12:00' . " WIB" }}</div>
                        </div>
                        
                        <p class="card-text text4 text-center mt-3" >Klik untuk detail</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if (session()->has('invite_code'))
        <div class="modal fade" id="inviteCodeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #1E3A8A;">Link Undangan</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text2">Berikut tautan undangan agenda yang dapat dibagikan:</p>
                        <div class="input-group mt-3">
                            <input
                                type="text"
                                class="form-control"
                                id="inviteLink"
                                value="https://example.com/invite/ABC123"
                                readonly
                            >

                            <button
                                class="btn btn-outline-primary"
                                type="button"
                                onclick="copyInviteLink()"
                            >
                                <i class="fa-regular fa-clone"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('invite_code'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(
                    document.getElementById('inviteCodeModal')
                );

                modal.show();
            });

            function copyInviteLink() {
                const input = document.getElementById('inviteLink');

                navigator.clipboard.writeText(input.value);
            }
        </script>
    @endif

    <div class="modal fade" id="modalTambahAgenda" tabindex="-1" aria-labelledby="modalTambahAgendaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <livewire:make-agenda-form />
        </div>
    </div>

    <div class="modal fade" id={{ 'modaldetailagenda' . '1' }} tabindex="-1" 
        aria-labelledby="modalDetailAgendaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-wrap" id="modalDetailAgendaLabel" style="color: #1E3A8A;">Agenda: {{ "Belajar Bareng" }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row gap-1 mb-3">
                        <span class="badge rounded-pill fs-6" style="background-color: #00cc00;">{{ 'Selesai' }}</span>
                        <span class="badge rounded-pill fs-6" style="background-color: #1E3A8A;">{{ 'Owner' }}</span>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div class="card-text text3 col">{{ 'azkazafran78' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="card-text text3 col text-truncate">{{ 'Telkom University' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div class="card-text text3 col">{{ '12 Februari 2026' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="card-text text3 col">{{ '12:00' . ' - ' . '14:00' . " WIB" }}</div>
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

    <div class="modal fade" id={{ 'modaldetailagenda' . '2' }} tabindex="-1" 
        aria-labelledby="modalDetailAgendaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-wrap" id="modalDetailAgendaLabel" style="color: #1E3A8A;">Agenda: {{ 'Belajar Laravel' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row gap-1 mb-3">
                        <span class="badge rounded-pill fs-6" style="background-color: #dfc500;">{{ 'Sedang Berjalan' }}</span>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div class="card-text text3 col">{{ 'pelajar39' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="card-text text3 col text-truncate">{{ 'Telkom University' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div class="card-text text3 col">{{ '9 Mei 2026' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="card-text text3 col">{{ '09:00' . ' - ' . '11:00' . " WIB" }}</div>
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

    <div class="modal fade" id={{ 'modaldetailagenda' . '4' }} tabindex="-1" 
        aria-labelledby="modalDetailAgendaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-wrap" id="modalDetailAgendaLabel" style="color: #1E3A8A;">Agenda: {{ 'Belajar Bareng' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row gap-1 mb-3">
                        <span class="badge rounded-pill text-bg-primary fs-6">{{ 'Belum Dimulai' }}</span>
                        <span class="badge rounded-pill fs-6" style="background-color: #1E3A8A;">{{ 'Owner' }}</span>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div class="card-text text3 col">{{ 'azkazafran78' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="card-text text3 col text-truncate">{{ 'Telkom University' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div class="card-text text3 col">{{ '30 Agustus 2026' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="card-text text2 col-1">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="card-text text3 col">{{ '10:00' . ' - ' . '12:00' . " WIB" }}</div>
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

                <div class="modal-footer justify-content-between">
                    <div>
                        <a class="btn" role="button" style="background-color: #ff0000; color: white;">
                            <i class="fa-solid fa-trash-can me-1"></i>
                            Batalkan
                        </a>
                    </div>

                    <div>
                        <a class="btn" role="button" style="background-color: hsl(0, 0%, 30%); color: white;">
                            <i class="fa-solid fa-pen-to-square me-1"></i>
                            Ubah
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .btn-purple {
                background-color: #4c0099;
                color: white;
            }

            .btn-purple:hover {
                background-color: hsl(270, 100%, 30%);
                color: white;
            }

            .btn-outline-purple {
                border-color: #4c0099;
                color: #4c0099;
            }

            .btn-outline-purple:hover {
                border-color: #4c0099;
                background-color: #4c0099;
                color: white;
            }
        </style>
    @endpush
@endsection