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

    <div class="modal fade" id="modalTambahAgenda" tabindex="-1" aria-labelledby="modalTambahAgendaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="POST" class="d-flex flex-column h-100">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahAgendaLabel"
                            style="color: #1E3A8A;">
                            Form Tambah Agenda
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body overflow-auto" style="height: 430px;">
                        <div class="mb-3">
                            <p class="form-label secondary text2">Penyelenggara : {{ 'azkazafran78' }}</label>
                        </div>
                        
                        <div class="mb-3">
                            <label for="namaAgenda" class="form-label secondary text2">Nama Agenda</label>
                            <input type="text" class="form-control" id="namaAgenda" placeholder="Agenda Belajar..." />
                        </div>

                        <div class="mb-3">
                            <label for="lokasiAgenda" class="form-label secondary text2">Lokasi Pelaksanaan</label>
                            <textarea class="form-control" id="lokasiAgenda" placeholder="Warung Kopi..." rows="3"></textarea>
                        </div>

                        <div class="row mb-3 gx-2">
                            <div class="col-md-4">
                                <label for="waktuAgenda" class="form-label secondary text2">Waktu</label>
                                <input type="date" class="form-control" id="waktuAgenda" placeholder="dd/mm/yyyy" />
                            </div>
                            <div class="col-md-6">
                                <label for="jamAgenda" class="form-label secondary text2">Jam</label>
                                <div class="input-group">
                                    <input type="time" class="form-control" id="jamAgenda" placeholder="-- : --">

                                    <span class="input-group-text">–</span>

                                    <input type="time" class="form-control" placeholder="-- : --">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label m-0" for="cariPeserta">Tambah Peserta</label>
                            <div class="d-flex flex-row form-text mt-0 mb-1">
                                <span class="me-1" style="color: #ff0000;">*</span>
                                <span>
                                    Peserta yang dapat mengikuti agenda ini hanya pengguna yang sudah berteman dengan kamu.
                                </span>
                            </div>
                            <div class="d-flex">
                                <input class="form-control me-2" id="cariPeserta" type="search" placeholder="Cari Username..." aria-label="Search"/>
                                <button class="btn btn-outline-success" type="button">Cari</button>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2 px-3 py-3 mb-3 border border-secondary-subtle rounded overflow-auto" style="height: 200px;">
                            <div class="d-flex flex-row align-items-center gap-1">
                                <div class="flex-grow-1 ps-2 py-1 rounded" style="background-color: #D6D7DA;">
                                    <p class="fs-6 m-0">{{ 'pelajar39' }}</p>
                                </div>
                                <button class="btn btn-primary btn-sm">
                                    Tambah
                                </button>
                            </div>
                            <div class="d-flex flex-row align-items-center gap-1">
                                <div class="flex-grow-1 ps-2 py-1 rounded" style="background-color: #D6D7DA;">
                                    <p class="fs-6 m-0">{{ 'pelajar39' }}</p>
                                </div>
                                <button class="btn btn-primary btn-sm">
                                    Tambah
                                </button>
                            </div>
                            <div class="d-flex flex-row align-items-center gap-1">
                                <div class="flex-grow-1 ps-2 py-1 rounded" style="background-color: #D6D7DA;">
                                    <p class="fs-6 m-0">{{ 'pelajar39' }}</p>
                                </div>
                                <button class="btn btn-primary btn-sm">
                                    Tambah
                                </button>
                            </div>
                            <div class="d-flex flex-row align-items-center gap-1">
                                <div class="flex-grow-1 ps-2 py-1 rounded" style="background-color: #D6D7DA;">
                                    <p class="fs-6 m-0">{{ 'pelajar39' }}</p>
                                </div>
                                <button class="btn btn-primary btn-sm">
                                    Tambah
                                </button>
                            </div>
                            <div class="d-flex flex-row align-items-center gap-1">
                                <div class="flex-grow-1 ps-2 py-1 rounded" style="background-color: #D6D7DA;">
                                    <p class="fs-6 m-0">{{ 'pelajar39' }}</p>
                                </div>
                                <button class="btn btn-primary btn-sm">
                                    Tambah
                                </button>
                            </div>
                        </div>

                        <div>
                            <p class="form-label secondary text2 m-0">Daftar Partisipan</p>
                            <div class="d-flex flex-row form-text mt-0 mb-1">
                                <span class="me-1" style="color: #ff0000;">*</span>
                                <span>
                                    Partisipan dibawah ini dapat mengikuti agenda dengan menerima undangan berupa tautan yang kamu bagikan. Tautan akan dibuat otomatis dan diberikan oleh sistem setelah kamu selesai membuat agenda.
                                </span>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2 px-3 py-3 mb-3 border border-secondary-subtle rounded overflow-auto" style="height: 200px;">
                            <div class="d-flex flex-row align-items-center gap-2 badge rounded-pill fs-6 fw-normal" 
                                style="background-color: hsl(214, 95%, 80%); width: fit-content;">
                                <span style="color: #111827;">{{ 'azkazafran78' }}</span>
                                <i class="fa-solid fa-xmark btn p-0" role="button" style="color: #111827;"></i>
                            </div>
                            <div class="d-flex flex-row align-items-center gap-2 badge rounded-pill fs-6 fw-normal" 
                                style="background-color: hsl(326, 78%, 80%); width: fit-content;">
                                <span style="color: #111827;">{{ 'azkazafran78' }}</span>
                                <i class="fa-solid fa-xmark btn p-0" role="button" style="color: #111827;"></i>
                            </div>
                            <div class="d-flex flex-row align-items-center gap-2 badge rounded-pill fs-6 fw-normal" 
                                style="background-color: hsl(141, 84%, 80%); width: fit-content;">
                                <span style="color: #111827;">{{ 'azkazafran78' }}</span>
                                <i class="fa-solid fa-xmark btn p-0" role="button" style="color: #111827;"></i>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn " style="background-color: #1E3A8A; color: white;">Buat Agenda</button>
                    </div>
                </form>
            </div>
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