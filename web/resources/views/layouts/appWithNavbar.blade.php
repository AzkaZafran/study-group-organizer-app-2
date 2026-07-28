@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    <div class="flex-grow-1 d-flex flex-column overflow-auto">
        @yield('navbar-content')
    </div>
@endsection