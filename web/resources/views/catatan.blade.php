@extends('layouts.appWithNavbar')

@section('navbar-content')
    <div class="container-fluid flex-grow-1 d-flex flex-column">
        <div class="row flex-grow-1">
            <div class="col-md-3 p-0 d-flex flex-column">

                <div class="px-2 d-flex flex-column">
                    <p class="fs-3 fw-semibold">Catatan</p>
                </div>

                <li class="list-agenda-container p-2 border-top border-bottom border-secondary-subtle list-unstyled">
                    @foreach ($data['list_agenda'] as $agenda)
                        <ul class="m-0 p-0"><a role="button" class="btn {{ $agenda['id_agenda'] == request()->route('id_agenda') ? 'btn-agenda-active fw-medium' : 'btn-agenda' }} d-block text-start text-truncate" 
                        href="{{ route('agenda catatan', ['id_agenda' => $agenda['id_agenda']]) }}">
                            {{ $agenda['nama_agenda'] }}
                        </a></ul>
                    @endforeach
                </li>

                <div class="px-2 d-flex flex-column">
                    <a role="button" class="btn btn-post my-3 {{ $data['agenda_status'] === 'belum dimulai' ? 'disabled' : '' }}">
                        Post Catatan
                    </a>
                </div>
            </div>

            <div class="list-catatan-container col-md-6 p-0 border-start border-end border-secondary-subtle scroll-y-inv">
                
                @if ($data['list_catatan']->isEmpty())
                    <div class="list-catatan-container d-flex justify-content-center align-items-center">
                        <p class="fs-5 fw-semibold text-secondary">Agenda ini belum memiliki catatan apapun.</p>
                    </div>
                @else
                    @foreach ($data['list_catatan'] as $catatan)
                    
                        <div class="catatan-container border-bottom border-secondary-subtle p-3">
                            <div class="d-flex flex-row mb-2" style="min-height: 26px;">

                                <p class="m-0" style="font-size: 14px;">
                                    <span class="fw-medium">{{ $catatan->author_name }}</span>
                                    <span style="color: hsl(0, 0%, 65%);">• {{ $catatan->tanggal_dibuat }} · {{ $catatan->viewed_count }} views</span>
                                </p>

                                <div class="flex-grow-1"></div>

                                @if ($catatan->is_author)
                                    <div class="d-flex flex-row gap-2 px-2 rounded" style="background-color: #F8FAFC;">
                                        <a role="button" class="btn p-0">
                                            <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                                        </a>
                                        <a role="button" class="btn p-0">
                                            <i class="fa-solid fa-trash-can" style="font-size: 14px; color: #ff0000;"></i>
                                        </a>
                                    </div>
                                @endif
                                
                            </div>

                            <h5 class="fw-semibold">{{ $catatan->judul_catatan }}</h5>
                            <p class="fs-6">{{ $catatan->catatan }}</p>
                        </div>
                    @endforeach
                @endif

            </div>

            <div class="col-md-3 p-4">
                <div class="mb-3">
                    <h5 class="panel-sub-title">Penyelenggara</h5>
                    <p class="fw-medium">{{ $data['nama_penyelenggara'] }}</p>
                </div>

                <hr>

                <div class="mb-3">
                    <h5 class="panel-sub-title" style="cursor: pointer;" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#pesertaList" 
                        aria-expanded="false">
                        Peserta ({{ $data['list_partisipan']->count() }}) 
                        <small class="text-muted ms-1" style="font-size: 0.7em;">▼</small>
                    </h5>

                    <div class="collapse show" id="pesertaList">
                        <ul class="list-unstyled ms-2 overflow-auto" style="max-height: 175px;">

                            @foreach ($data['list_partisipan'] as $partisipan)
                                <li class="mb-2">{{ $partisipan['nama_partisipan'] }}</li>
                            @endforeach
                            
                        </ul>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h5 class="panel-sub-title">Status</h5>

                    @if ($data['agenda_status'] == 'belum dimulai')
                        <p class="text-primary fw-bold">Belum Dimulai</p>
                    @elseif($data['agenda_status'] == 'sedang berjalan')
                        <p class="text-warning fw-bold">Sedang Berjalan</p>
                    @elseif($data['agenda_status'] == 'selesai')
                        <p class="text-success fw-bold">Selesai</p>
                    @endif
                    
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                .list-agenda-container{
                    min-height: calc(100vh - 58px - 60px - 70px);
                    max-height: calc(100vh - 58px - 60px - 70px);
                    overflow-y: auto;
                }

                .scroll-y-inv{
                    /* Firefox */
                    scrollbar-width: none;

                    /* Internet Explorer and old Edge */
                    -ms-overflow-style: none;
                }

                .scroll-y-inv::-webkit-scrollbar{
                    display: none;
                }

                .btn-post {
                    background-color: #1E3A8A;
                    color: white;
                }

                .btn-post:hover {
                    background-color: hsl(224, 64%, 23%);
                    color: hsl(0, 0%, 90%);
                }

                .catatan-container {
                    background-color: #F8FAFC;
                }

                .catatan-container:hover{
                    background-color: hsl(0, 0%, 80%);
                }

                .btn-agenda {
                    background-color: #F8FAFC;
                }

                .btn-agenda:hover{
                    background-color: hsl(0, 0%, 80%);
                }

                .btn-agenda-active{
                    color: white;
                    background-color: #1E3A8A;
                }

                .btn-agenda-active:hover{
                    color: white;
                    background-color: #1E3A8A;
                }

                .list-catatan-container{
                    min-height: calc(100vh - 58px);
                    max-height: calc(100vh - 58px);
                    overflow-y: auto;
                }
            </style>
        @endpush
    </div>
@endsection