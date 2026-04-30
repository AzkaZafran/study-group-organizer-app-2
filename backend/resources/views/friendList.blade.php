@extends('layouts.friend')

@section('friend-content')
    <ul class="friend-navbar nav nav-pills nav-fill mb-3">
        <li class="nav-item">
            <a class="nav-link cnav-text active" aria-current="page" href="{{ route('friend list') }}">Daftar Teman</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cnav-text" href="{{ route('friend requests') }}">Permintaan Teman</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cnav-text" href="{{ route('search new friend') }}">Tambah Teman</a>
        </li>
    </ul>
    <div class="d-flex flex-column gap-2 px-5 overflow-auto">
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend1' }}</p>
            <p class="fs-6 m-0">{{ 'Friend1@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend2' }}</p>
            <p class="fs-6 m-0">{{ 'Friend2@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend1' }}</p>
            <p class="fs-6 m-0">{{ 'Friend1@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend2' }}</p>
            <p class="fs-6 m-0">{{ 'Friend2@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend1' }}</p>
            <p class="fs-6 m-0">{{ 'Friend1@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend2' }}</p>
            <p class="fs-6 m-0">{{ 'Friend2@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend1' }}</p>
            <p class="fs-6 m-0">{{ 'Friend1@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend2' }}</p>
            <p class="fs-6 m-0">{{ 'Friend2@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend1' }}</p>
            <p class="fs-6 m-0">{{ 'Friend1@gmail.com' }}</p>
        </div>
        <div class="d-flex flex-row justify-content-between p-2" style="background-color: #D6D7DA; border-radius: 10px;">
            <p class="fs-5 m-0">{{ 'Friend2' }}</p>
            <p class="fs-6 m-0">{{ 'Friend2@gmail.com' }}</p>
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