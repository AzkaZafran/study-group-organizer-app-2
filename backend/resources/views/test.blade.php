@extends('layouts.app')

@section('content')
    <form action="/friend/requests/reject/999" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit">Test Reject Friend Request</button>
    </form>

    <form action="/friend/requests/accept/999" method="POST">
        @csrf

        <button type="submit">Test Accept Friend Request</button>
    </form>

    <form action="/friend/requests/send/999" method="POST">
        @csrf

        <button type="submit">Test Send Friend Request</button>
    </form>

    <form action="{{ "/friend/requests/reject/{$data['id_mutual_request']}" }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit">Test Reject Mutual Request</button>
    </form>

    <form action="{{ "/friend/requests/accept/{$data['id_mutual_request']}" }}" method="POST">
        @csrf

        <button type="submit">Test Accept Mutual Request</button>
    </form>
@endsection