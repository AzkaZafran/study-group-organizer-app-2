@extends('layouts.app')

@section('content')
    <div class="bg-light d-flex justify-content-center align-items-center"
        style="width: 100%; height: 100vh;">
        <div class="card shadow p-3" style="width: 100%; max-width: 550px;">
            <p class="text-center mb-4">Anda telah diundang oleh <span class="fw-bolder">{{ $data['inviter_name'] }}</span> untuk mengikuti agenda</p>
            <div class="d-flex justify-content-center mb-3">
                <i class="fa-solid fa-clipboard-list fs-2"></i>
            </div>
            <h4 class="card-title text-center mb-4" style="color: #1E3A8A">{{ $data['agenda_name'] }}</h4>

            <div class="d-flex flex-row align-items-center">
                <div class="flex-grow-1">
                    <form action="#" method="POST" class="d-flex justify-content-center">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-reject" style="width: 230px;">Tolak Ajakan</button>
                    </form>
                </div>
                <div class="flex-grow-1">
                    <form action="#" method="POST" class="d-flex justify-content-center">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-join" style="width: 230px;">Ikut Agenda</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .btn-join {
                background-color: #1E3A8A;
                color: white;
            }

            .btn-join:hover {
                background-color: hsl(224, 64%, 23%);
                color: white;
            }

            .btn-reject {
                background-color: #bdbdbd;
                color: #424242;
            }

            .btn-reject:hover {
                background-color: hsl(0, 0%, 64%);
                color: #424242;
            }
        </style>
    @endpush
@endsection