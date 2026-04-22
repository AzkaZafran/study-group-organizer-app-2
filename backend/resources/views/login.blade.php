@extends('layouts.app')

@section('content')
    <div class="bg-light d-flex justify-content-center align-items-center"
        style="width: 100%; height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        
            <h3 class="card-title text-center mb-4">Login</h3>

            <form action="{{ route('login account') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="inputUsername" class="form-label">Username</label>
                    <input type="text" id="inputUsername" class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan Username" name="username"/>
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" id="inputPassword" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password" name="password"/>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @error('message')
                    <div class="mb-3 p-3 bg-danger text-white rounded">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>

                <div class="text-center">
                    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                </div>
            </form>

        </div>
    </div>
@endsection