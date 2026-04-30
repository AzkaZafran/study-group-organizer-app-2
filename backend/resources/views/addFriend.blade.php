@extends('layouts.friend')

@section('friend-content')
    <ul class="friend-navbar nav nav-pills nav-fill mb-3">
        <li class="nav-item">
            <a class="nav-link cnav-text" href="{{ route('friend list') }}">Daftar Teman</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cnav-text" href="{{ route('friend requests') }}">Permintaan Teman</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cnav-text active" aria-current="page" href="{{ route('search new friend') }}">Tambah Teman</a>
        </li>
    </ul>
    <form class="d-flex px-5 mb-3" role="search">
        <input class="form-control me-2" type="search" placeholder="Cari Username..." aria-label="Search"/>
        <button class="btn btn-outline-success" type="submit">Cari</button>
    </form>
    <div class="d-flex flex-column gap-2 px-5 overflow-auto">
        <div class="d-flex flex-row align-items-center gap-2">
            <div class="flex-grow-1 ps-2 py-1" style="background-color: #D6D7DA; border-radius: 10px;">
                <p class="fs-5 m-0">{{ 'Friend1' }}</p>
            </div>
            <div class="flex-grow-0">
                <form action="{{ '/friend/requests/send/' . '2' }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Minta Pertemanan</button>
                </form>
            </div>
        </div>
        <div class="d-flex flex-row align-items-center gap-2">
            <div class="flex-grow-1 ps-2 py-1" style="background-color: #D6D7DA; border-radius: 10px;">
                <p class="fs-5 m-0">{{ 'Friend2' }}</p>
            </div>
            <div class="flex-grow-0">
                <button type="button" class="btn btn-secondary" style="width: 155.5px;" disabled>Mutual</button>
            </div>
        </div>
        <div class="d-flex flex-row align-items-center gap-2">
            <div class="flex-grow-1 ps-2 py-1" style="background-color: #D6D7DA; border-radius: 10px;">
                <p class="fs-5 m-0">{{ 'Friend1' }}</p>
            </div>
            <div class="flex-grow-0">
                <form action="{{ '/friend/requests/send/' . '2' }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Minta Pertemanan</button>
                </form>
            </div>
        </div>
        <div class="d-flex flex-row align-items-center gap-2">
            <div class="flex-grow-1 ps-2 py-1" style="background-color: #D6D7DA; border-radius: 10px;">
                <p class="fs-5 m-0">{{ 'Friend2' }}</p>
            </div>
            <div class="flex-grow-0">
                <button type="button" class="btn btn-secondary" style="width: 155.5px;" disabled>Mutual</button>
            </div>
        </div>
        <div class="d-flex flex-row align-items-center gap-2">
            <div class="flex-grow-1 ps-2 py-1" style="background-color: #D6D7DA; border-radius: 10px;">
                <p class="fs-5 m-0">{{ 'Friend1' }}</p>
            </div>
            <div class="flex-grow-0">
                <form action="{{ '/friend/requests/send/' . '2' }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Minta Pertemanan</button>
                </form>
            </div>
        </div>
        <div class="d-flex flex-row align-items-center gap-2">
            <div class="flex-grow-1 ps-2 py-1" style="background-color: #D6D7DA; border-radius: 10px;">
                <p class="fs-5 m-0">{{ 'Friend2' }}</p>
            </div>
            <div class="flex-grow-0">
                <button type="button" class="btn btn-secondary" style="width: 155.5px;" disabled>Mutual</button>
            </div>
        </div>
    </div>
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