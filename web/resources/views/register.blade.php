@extends('layouts.app')

@section('content')
    <div class="bg-light d-flex justify-content-center align-items-center" style="width: 100%; height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            
            <h3 class="card-title text-center mb-4">Register</h3>

            <form action="{{ route('register account') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="inputName" class="form-label">Nama Pengguna</label>
                    <input type="text" id="inputName" class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan Nama" name="username"/>
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email</label>
                    <input type="email" id="inputEmail" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Alamat Email" name="email"/>
                    @error('email')
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

                <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>

                <div class="text-center">
                    <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                </div>
            </form>

        </div>
    </div>
@endsection