@extends('layouts.app')

@section('content')
    <div class="bg-light d-flex justify-content-center align-items-center"
        style="width: 100%; height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 700px;">
            <h3 class="card-title text-center mb-4">Ubah Agenda</h3>

            <form action="{{ route('update agenda') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row gx-2">

                    <div class="col-6">
                        <label for="namaAgenda" class="form-label secondary text2">Nama Agenda</label>
                        <input type="text" class="form-control @error('nama_agenda') is-invalid @enderror" name="nama_agenda" id="namaAgenda" value="{{ $data['nama_agenda'] }}" />
                        <div class="invalid-feedback d-block" style="min-height: 24px;">
                            @error('nama_agenda')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="col-5">
                        <label for="waktuAgenda" class="form-label secondary text2">Waktu</label>
                        <input type="date" class="form-control @error('waktu_agenda') is-invalid @enderror" name="waktu_agenda" id="waktuAgenda" value="{{ $data['waktu_agenda'] }}" />
                        <div class="invalid-feedback d-block" style="min-height: 24px;">
                            @error('waktu_agenda')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="row gx-2">

                    <div class="col-6">
                        <label for="lokasiAgenda" class="form-label secondary text2">Lokasi Pelaksanaan</label>
                        <textarea class="form-control @error('lokasi_agenda') is-invalid @enderror" name="lokasi_agenda" id="lokasiAgenda" rows="3">{{ $data['lokasi'] }}</textarea>
                        <div class="invalid-feedback d-block" style="min-height: 24px;">
                            @error('lokasi_agenda')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <label for="jamAgenda" class="form-label secondary text2">Jam</label>
                        <div class="input-group">
                            <input type="time" class="form-control @error('jam_awal') is-invalid @enderror" name="jam_awal" id="jamAgenda" value="{{ $data['jam_mulai'] }}">

                            <span class="input-group-text">–</span>

                            <input type="time" class="form-control @error('jam_akhir') is-invalid @enderror" name="jam_akhir" value="{{ $data['jam_akhir'] }}">
                        </div>
                        <div class="invalid-feedback d-block" style="min-height: 80px;">
                            @error('jam_awal')
                                <div>{{ $message }}</div>
                            @enderror
                            @error('start_time')
                                <div>{{ $message }}</div>
                            @enderror
                            @error('jam_akhir')
                                <div>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <input name="id_agenda" value="{{ $data['id_agenda'] }}" hidden>

                <div class="d-flex flex-row align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-center">
                            <a class="btn btn-back" role="button" href="{{ route('dashboard') }}" style="width: 300px;">Kembali</a>
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-update" style="width: 300px;">Ubah</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .btn-update {
                background-color: #1E3A8A;
                color: white;
            }

            .btn-update:hover {
                background-color: hsl(224, 64%, 23%);
                color: white;
            }

            .btn-back {
                background-color: #bdbdbd;
                color: #424242;
            }

            .btn-back:hover {
                background-color: hsl(0, 0%, 64%);
                color: #424242;
            }
        </style>
    @endpush
@endsection