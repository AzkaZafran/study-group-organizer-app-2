@extends('layouts.app')

@section('content')
    <div class="bg-light d-flex justify-content-center align-items-center"
        style="width: 100%; height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 500px;">
            <h3 class="card-title text-center mb-4">Ubah Agenda</h3>

            <form action="#" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="namaAgenda" class="form-label secondary text2">Nama Agenda</label>
                    <input type="text" class="form-control" name="nama_agenda" id="namaAgenda" value="{{ $data['nama_agenda'] }}" />
                </div>

                <div class="mb-3">
                    <label for="lokasiAgenda" class="form-label secondary text2">Lokasi Pelaksanaan</label>
                    <textarea class="form-control" name="lokasi_agenda" id="lokasiAgenda" rows="3">{{ $data['lokasi'] }}</textarea>
                </div>

                <div class="row mb-3 gx-2">
                    <div class="col-md-4">
                        <label for="waktuAgenda" class="form-label secondary text2">Waktu</label>
                        <input type="date" class="form-control" name="waktu_agenda" id="waktuAgenda" value="{{ $data['waktu_agenda'] }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="jamAgenda" class="form-label secondary text2">Jam</label>
                        <div class="input-group">
                            <input type="time" class="form-control" name="jam_awal" id="jamAgenda" value="{{ $data['jam_mulai'] }}">

                            <span class="input-group-text">–</span>

                            <input type="time" class="form-control" name="jam_akhir" value="{{ $data['jam_akhir'] }}">
                        </div>
                    </div>
                </div>

                <input name="id_agenda" value="{{ $data['id_agenda'] }}" hidden>

                <div class="d-flex flex-row align-items-center mt-5">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-center">
                            <a class="btn btn-back" role="button" href="{{ route('dashboard') }}" style="width: 210px;">Kembali</a>
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-update" style="width: 210px;">Ubah</button>
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