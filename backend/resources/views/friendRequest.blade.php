@extends('layouts.friend')

@section('friend-content')
    <ul class="friend-navbar nav nav-pills nav-fill mb-3">
        <li class="nav-item">
            <a class="nav-link cnav-text" href="{{ route('friend list') }}">Daftar Teman</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cnav-text active" aria-current="page" href="{{ route('friend requests') }}">Permintaan Teman</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cnav-text" href="{{ route('search new friend') }}">Tambah Teman</a>
        </li>
    </ul>
    @isset($data)
        <div class="d-flex flex-column gap-2 px-5 overflow-auto">
            @foreach ($data['friend_requests'] as $friend_request)
                <div class="d-flex flex-row align-items-center gap-1">
                    <div class="flex-grow-1 ps-2 py-1" style="background-color: #D6D7DA; border-radius: 10px;">
                        <p class="fs-5 m-0">{{ $friend_request['username'] }}</p>
                    </div>
                    <div class="flex-grow-0">
                        <form action="{{ '/friend/requests/reject/'.$friend_request['id_request'] }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none;">
                                <i class="fa-solid fa-square-xmark" style="color: rgb(242, 0, 0); font-size: 38px"></i>
                            </button>
                        </form>
                    </div>
                    <div class="flex-grow-0">
                        <form action="{{ '/friend/requests/accept/'.$friend_request['id_request'] }}" method="POST">
                            @csrf
                            <button type="submit" style="background: none; border: none;">
                                <i class="fa-solid fa-square-check" style="color: rgb(0, 242, 0); font-size: 38px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endisset
    @push('styles')
        <style>
            .friend-navbar > .nav-item > .nav-link.active {
                background-color: black !important;
                color: white !important;
            }

            .friend-navbar > .nav-item > .nav-link {
                color: black !important;
            }
        </style>
    @endpush
@endsection