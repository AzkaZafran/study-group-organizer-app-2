<div>
    <div class="d-flex flex-row gap-2 mb-3">
        <button type="button" wire:click="selectStatusFilter('semua')" class="btn {{ $filterStatus === 'semua' ? 'btn-purple' : 'btn-outline-purple' }}">Semua</button>
        <button type="button" wire:click="selectStatusFilter('selesai')" class="btn {{ $filterStatus === 'selesai' ? 'btn-purple' : 'btn-outline-purple' }}">Selesai</button>
        <button type="button" wire:click="selectStatusFilter('sedang berjalan')" class="btn {{ $filterStatus === 'sedang berjalan' ? 'btn-purple' : 'btn-outline-purple' }}">Sedang Berjalan</button>
        <button type="button" wire:click="selectStatusFilter('belum dimulai')" class="btn {{ $filterStatus === 'belum dimulai' ? 'btn-purple' : 'btn-outline-purple' }}">Belum Dimulai</button>
        <button type="button" wire:click="selectOwnedFilter()" class="btn {{ $filterOwned ? 'btn-purple' : 'btn-outline-purple' }}">Dibuat</button>
    </div>

    <div class="container d-flex flex-row flex-wrap gap-3 mb-4">

        @foreach ($wire_data['list_agenda'] as $agenda)
            <div class="card shadow" style="width: 22rem;"
            data-bs-toggle="modal" data-bs-target={{ '#modaldetailagenda' . $loop->index }}>
                <div class="btn text-start w-100 h-100">
                    <div class="card-body d-flex flex-column w-100 h-100">
                        <div class="d-flex flex-row gap-1 mb-1">
                            @if ($agenda->status == 'selesai')
                                <span class="badge rounded-pill fs-6" style="background-color: #00cc00;">{{ 'Selesai' }}</span>
                            @elseif($agenda->status == 'sedang berjalan')
                                <span class="badge rounded-pill fs-6" style="background-color: #dfc500;">{{ 'Sedang Berjalan' }}</span>
                            @elseif($agenda->status == 'belum dimulai')
                                <span class="badge rounded-pill text-bg-primary fs-6">{{ 'Belum Dimulai' }}</span>
                            @endif

                            @if ($agenda->is_owner)
                                <span class="badge rounded-pill fs-6" style="background-color: #1E3A8A;">{{ 'Owner' }}</span>
                            @endif
                        </div>
                        <h5 class="card-title text-truncate mb-3" style="color: #1E3A8A">{{ $agenda->nama_agenda }}</h5>

                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div class="card-text text3 col">{{ $agenda->nama_penyelenggara }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="card-text text3 col text-truncate">{{ $agenda->lokasi }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="card-text text3 col">{{ $agenda->waktu_agenda }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="card-text text2 col-1">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="card-text text3 col">{{ $agenda->jam_awal . ' - ' . $agenda->jam_akhir . " WIB" }}</div>
                        </div>
                        
                        <p class="card-text text4 text-center mt-3" >Klik untuk detail</p>
                    </div>
                </div>
            </div>


            <div class="modal fade" id={{ 'modaldetailagenda' . $loop->index }} tabindex="-1" 
                aria-labelledby="modalDetailAgendaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-wrap" id="modalDetailAgendaLabel" style="color: #1E3A8A;">Agenda: {{ $agenda->nama_agenda }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex flex-row gap-1 mb-3">
                                @if ($agenda->status == 'selesai')
                                    <span class="badge rounded-pill fs-6" style="background-color: #00cc00;">{{ 'Selesai' }}</span>
                                @elseif($agenda->status == 'sedang berjalan')
                                    <span class="badge rounded-pill fs-6" style="background-color: #dfc500;">{{ 'Sedang Berjalan' }}</span>
                                @elseif($agenda->status == 'belum dimulai')
                                    <span class="badge rounded-pill text-bg-primary fs-6">{{ 'Belum Dimulai' }}</span>
                                @endif

                                @if ($agenda->is_owner)
                                    <span class="badge rounded-pill fs-6" style="background-color: #1E3A8A;">{{ 'Owner' }}</span>
                                @endif
                            </div>
                            <div class="row mb-1">
                                <div class="card-text text2 col-1">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div class="card-text text3 col">{{ $agenda->nama_penyelenggara }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="card-text text2 col-1">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="card-text text3 col text-truncate">{{ $agenda->lokasi }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="card-text text2 col-1">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>
                                <div class="card-text text3 col">{{ $agenda->waktu_agenda }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="card-text text2 col-1">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div class="card-text text3 col">{{ $agenda->jam_awal . ' - ' . $agenda->jam_akhir . " WIB" }}</div>
                            </div>

                            <div class="border-top my-3"></div>

                            <div class="d-flex flex-row gap-2 align-items-center mb-3">
                                <i class="fa-solid fa-user-group fa-lg"></i>
                                <h5 class="m-0 p-0">Partisipan :</h5>
                            </div>

                            <div class="d-flex flex-column gap-2 overflow-auto " style="height: 180px;">

                                @foreach ($agenda->participants as $partisipan)
                                    <div class="d-flex flex-row gap-1 mb-1">
                                        <span class="badge rounded-pill fs-6 fw-normal" 
                                            style="background-color: {{ $colors[$loop->index % $colors_count] }}; color: #111827; width: fit-content;">
                                            {{ $partisipan->username }}
                                        </span>

                                        @if ($partisipan->pivot->status == 'ikut')
                                            <span class="badge rounded-pill fs-6 fw-normal" 
                                                style="background-color: #E6F4EA; color: #137333; width: fit-content;">
                                                Ikut
                                            </span>
                                        @elseif($partisipan->pivot->status == 'tidak ikut')
                                            <span class="badge rounded-pill fs-6 fw-normal" 
                                                style="background-color: #FCE8E6; color: #C5221F; width: fit-content;">
                                                Tidak Ikut
                                            </span>
                                        @else
                                            <span class="badge rounded-pill fs-6 fw-normal" 
                                                style="background-color: #FEF7E0; color: #B06000; width: fit-content;">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                    
                                @endforeach
                            
                            </div>
                        </div>

                        @if ($agenda->status == 'belum dimulai')
                            <div class="modal-footer justify-content-between">
                                <div>
                                    <a class="btn" role="button" style="background-color: #ff0000; color: white;"
                                        data-bs-dismiss="modal"
                                        data-bs-toggle="modal"
                                        data-bs-target={{ "#deleteAgendaModal" . $loop->index }}>
                                        <i class="fa-solid fa-trash-can me-1"></i>
                                        Batalkan
                                    </a>
                                </div>

                                <div>
                                    <a class="btn" role="button" style="background-color: hsl(0, 0%, 30%); color: white;"
                                        href="{{ route('update agenda dialog', ['id_agenda' => $agenda->id_agenda]) }}">
                                        <i class="fa-solid fa-pen-to-square me-1"></i>
                                        Ubah
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        @else
                            <div class="modal-footer">
                                <a class="btn" role="button" style="background-color: #492201; color: white;">
                                    <i class="fa-solid fa-book-open me-1"></i>
                                    Catatan
                                </a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <div class="modal fade" id={{ "deleteAgendaModal" . $loop->index }} tabindex="-1" 
                aria-labelledby="modalDetailAgendaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahAgendaLabel"
                                style="color: #1E3A8A;">
                                Batalkan Agenda
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="d-flex flex-column align-items-center">
                                <h5 class="text text-center">Apakah Anda Yakin Ingin Membatalkan Agenda "{{ $agenda->nama_agenda }}"?</h5>
                                <h6 class="text fw-bolder" style="color: #ff0000;">*Tindakan ini akan menghapus agenda secara permanen</h6>
                                <div class="d-flex flex-row justify-content-center gap-2 mt-3">
                                    <button type="button" class="btn btn-back" 
                                            data-bs-dismiss="modal" style="width: 175px;">
                                        Tidak
                                    </button>

                                    <form action="{{ route('delete agenda', ['id_agenda' => $agenda->id_agenda]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="width: 175px;">Yakin</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($wire_data['list_agenda'] as $agenda)

        @endforeach

    </div>

    @push('styles')
        <style>
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
</div>