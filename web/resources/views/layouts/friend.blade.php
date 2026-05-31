@extends('layouts.appWithNavbar')

@section('navbar-content')
    <div class="container h-75">
        <div class="row gx-3 gy-3">
            <div class="col d-flex">
                <div class="card d-flex flex-column w-100 shadow p-3" style="height: 80vh;">
                    @yield('friend-content')
                </div>
            </div>
        </div>
    </div>
@endsection