@extends('layouts.appWithNavbar')

@section('navbar-content')
    @if ($errors->any())
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
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

        <livewire:list-agenda />

    </div>

    @if (session()->has('invite_code'))
        <div class="modal fade" id="inviteCodeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #1E3A8A;">Link Undangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text2">Berikut tautan undangan agenda yang dapat dibagikan:</p>
                        <div class="input-group mt-3">
                            <input
                                type="text"
                                class="form-control"
                                id="inviteLink"
                                value="{{ route('agenda invite dialog', ['invite_code' => session('invite_code')]) }}"
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