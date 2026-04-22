@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" 
        style="width: 100%; height: 100vh; background-color: #4AFFF6;">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h3 class="card-title mb-2">OTP Verification</h3>
            <h6 class="text-dark-emphasis mb-2">
                Successfully sent to <span class="text-info-emphasis">{{ old('email', session('email')) }}</span>.<br>
                Please check your email inbox.
            </h6>
            <form action="{{ route('verify email') }}" method="POST">
                @csrf
                <div class="my-3">
                    <label for="inputOtp" class="form-label">Masukkan 6-digit kode otp</label>
                    <input type="text" id="inputOtp" class="form-control @error('otp_code') is-invalid @enderror" placeholder="123456" name="otp_code"/>
                    @error('otp_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <input type="email" value="{{ old('email', session('email')) }}" name="email" hidden>

                @error('message')
                    <div class="mb-3 p-3 bg-danger text-white rounded">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" class="btn btn-primary w-100 mb-3">Verifikasi</button>
            </form>
            <form action="{{ route('resend otp') }}" method="POST">
                <div class="text-center">
                    <p>Kode OTP belum terkirim?</p>
                </div>
                <input type="email" value="{{ old('email', session('email')) }}" name="email" hidden>
                <button type="submit" class="btn btn-secondary w-100 mb-3">Kirim Ulang</button>
            </form>
        </div>
    </div>
@endsection