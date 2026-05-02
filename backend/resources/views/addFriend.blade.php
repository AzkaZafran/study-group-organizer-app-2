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

    <form class="d-flex px-5 mb-3" role="search" action="/friend/search" method="GET">
        <input class="form-control me-2" type="search" placeholder="Cari Username..." name="username" value="{{ request('username') }}" aria-label="Search"/>
        <input name="page" type="number" value="1" hidden/>
        <input name="size" type="number" value="6" hidden/>
        <button class="btn btn-outline-success" type="submit">Cari</button>
    </form>

    @isset($data)
        <div class="d-flex flex-column gap-2 px-5 mb-5" style="height: 270px;">
            @foreach ($data['users'] as $user)
                <div class="d-flex flex-row align-items-center gap-2">
                    <div class="flex-grow-1 ps-2 py-1" style="background-color: #D6D7DA; border-radius: 10px;">
                        <p class="fs-5 m-0">{{ $user['username'] }}</p>
                    </div>
                    <div class="flex-grow-0">
                        @if ($user['status'] === 'mutual')
                            <button type="button" class="btn btn-secondary" style="width: 155.5px;" disabled>Mutual</button>
                        @elseif($user['status'] === 'pending')
                            <button type="button" class="btn btn-secondary" style="width: 155.5px;" disabled>Pending</button>
                        @else
                            <form action="{{ '/friend/requests/send/'. $user['id'] }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Minta Pertemanan</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <nav class="align-self-center">
            <ul class="pagination">

                {{-- Previous --}}
                <li class="page-item {{ $data['on_first_page'] ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ route('search new friend', [
                        'username' => request('username'),
                        'page' => $data['page'] - 1,
                        'size' => $data['size']
                    ]) }}">Previous</a>
                </li>

                {{-- Page numbers --}}
                @for ($i = 1; $i <= $data['last_page']; $i++)
                    <li class="page-item {{ $i == $data['page'] ? 'active' : '' }}">
                        <a class="page-link" href="{{ route('search new friend', [
                            'username' => request('username'),
                            'page' => $i,
                            'size' => $data['size']
                        ]) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Next --}}
                <li class="page-item {{ !$data['has_more_pages'] ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ route('search new friend', [
                        'username' => request('username'),
                        'page' => $data['page'] + 1,
                        'size' => $data['size']
                    ]) }}">Next</a>
                </li>

            </ul>
        </nav>
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