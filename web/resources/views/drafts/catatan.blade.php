@extends('layouts.appWithNavbar')

@section('navbar-content')
    <div class="container-fluid flex-grow-1 d-flex flex-column">
        <div class="row flex-grow-1">
            <div class="col-md-3 d-flex flex-column">
                <p class="fs-3 fw-semibold">Catatan</p>

                <li class="list-agenda-container scroll-y-inv">
                    <ul class="m-0"><a role="button" class="btn d-block text-truncate" href="#">Weekly Programming Meetup (NEW)</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                    <ul class="m-0"><a role="button" class="btn text-wrap" href="#">Agenda 1</a></ul>
                </li>

                <a role="button" class="btn btn-post my-3">
                    Post Catatan
                </a>
            </div>

            <div class="list-catatan-container col-md-6 p-0 border-start border-end border-secondary-subtle scroll-y-inv">
                
                <div class="catatan-container border-bottom border-secondary-subtle p-3">
                    <div class="d-flex flex-row mb-2" style="min-height: 26px;">
                        <p class="m-0" style="font-size: 14px;">
                            <span class="fw-medium">Azka Zafran</span>
                            <span style="color: hsl(0, 0%, 65%);">• 21:52 · 100 views</span>
                        </p>
                        <div class="flex-grow-1"></div>
                        <div class="d-flex flex-row gap-2 px-2 rounded" style="background-color: #F8FAFC;">
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-trash-can" style="font-size: 14px; color: #ff0000;"></i>
                            </a>
                        </div>
                    </div>

                    <h5 class="fw-semibold">Pengantar Basis Data</h5>
                    <p class="fs-6">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est eos deserunt beatae cumque commodi omnis eligendi consequatur voluptatibus alias deleniti assumenda minima aperiam vitae in, rem quidem fuga! Consequuntur, unde.</p>
                </div>

                <div class="catatan-container border-bottom border-secondary-subtle p-3">
                    <div class="d-flex flex-row mb-2" style="min-height: 26px;">
                        <p class="m-0" style="font-size: 14px;">
                            <span class="fw-medium">Azka Zafran</span>
                            <span style="color: hsl(0, 0%, 65%);">• 21:52 · 100 views</span>
                        </p>
                        <div class="flex-grow-1"></div>
                        <div class="d-flex flex-row gap-2 px-2 rounded" style="background-color: #F8FAFC;">
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-trash-can" style="font-size: 14px; color: #ff0000;"></i>
                            </a>
                        </div>
                    </div>

                    <h5 class="fw-semibold">Pengantar Basis Data</h5>
                    <p class="fs-6">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est eos deserunt beatae cumque commodi omnis eligendi consequatur voluptatibus alias deleniti assumenda minima aperiam vitae in, rem quidem fuga! Consequuntur, unde.</p>
                </div>

                <div class="catatan-container border-bottom border-secondary-subtle p-3">
                    <div class="d-flex flex-row mb-2" style="min-height: 26px;">
                        <p class="m-0" style="font-size: 14px;">
                            <span class="fw-medium">Azka Zafran</span>
                            <span style="color: hsl(0, 0%, 65%);">• 21:52 · 100 views</span>
                        </p>
                        <div class="flex-grow-1"></div>
                        <div class="d-flex flex-row gap-2 px-2 rounded" style="background-color: #F8FAFC;">
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-trash-can" style="font-size: 14px; color: #ff0000;"></i>
                            </a>
                        </div>
                    </div>

                    <h5 class="fw-semibold">Pengantar Basis Data</h5>
                    <p class="fs-6">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est eos deserunt beatae cumque commodi omnis eligendi consequatur voluptatibus alias deleniti assumenda minima aperiam vitae in, rem quidem fuga! Consequuntur, unde.</p>
                </div>

                <div class="catatan-container border-bottom border-secondary-subtle p-3">
                    <div class="d-flex flex-row mb-2" style="min-height: 26px;">
                        <p class="m-0" style="font-size: 14px;">
                            <span class="fw-medium">Azka Zafran</span>
                            <span style="color: hsl(0, 0%, 65%);">• 21:52 · 100 views</span>
                        </p>
                        <div class="flex-grow-1"></div>
                        <div class="d-flex flex-row gap-2 px-2 rounded" style="background-color: #F8FAFC;">
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-trash-can" style="font-size: 14px; color: #ff0000;"></i>
                            </a>
                        </div>
                    </div>

                    <h5 class="fw-semibold">Pengantar Basis Data</h5>
                    <p class="fs-6">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est eos deserunt beatae cumque commodi omnis eligendi consequatur voluptatibus alias deleniti assumenda minima aperiam vitae in, rem quidem fuga! Consequuntur, unde.</p>
                </div>

            </div>

            <div class="col-md-3 p-4">
                <div class="mb-3">
                    <h5 class="panel-sub-title">Penyelenggara</h5>
                    <p class="fw-medium">Azka Zafran</p>
                </div>

                <hr>

                <div class="mb-3">
                    <h5 class="panel-sub-title" style="cursor: pointer;" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#pesertaList" 
                        aria-expanded="false">
                        Peserta (5) 
                        <small class="text-muted ms-1" style="font-size: 0.7em;">▼</small>
                    </h5>

                    <div class="collapse show" id="pesertaList">
                        <ul class="list-unstyled ms-2 overflow-auto" style="max-height: 175px;">
                            <li class="mb-2">Azka Zafran</li>
                            <li class="mb-2">Ahmad Pasha</li>
                            <li class="mb-2">Teguh Ryan</li>
                            <li class="mb-2">Ficha</li>
                            <li class="mb-2">Nabila</li>
                            <li class="mb-2">Ficha</li>
                            <li class="mb-2">Nabila</li>
                        </ul>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h5 class="panel-sub-title">Status</h5>
                    <p class="text-success fw-bold">Aktif</p>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                .list-agenda-container{
                    min-height: calc(100vh - 58px - 58px - 70px);
                    max-height: calc(100vh - 58px - 58px - 70px);
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

                .list-catatan-container{
                    min-height: calc(100vh - 58px);
                    max-height: calc(100vh - 58px);
                    overflow-y: auto;
                }
            </style>
        @endpush
    </div>
@endsection