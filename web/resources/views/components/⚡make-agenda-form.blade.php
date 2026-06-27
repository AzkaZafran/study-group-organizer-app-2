<?php

use Livewire\Component;
use App\Services\FriendRequestService;

new class extends Component
{
    private FriendRequestService $friendRequestService;

    public string $username_search;
    public $participants = [];
    public $search_friend_result = [];
    public $colors = ["hsl(214, 95%, 80%)", 
                        "hsl(226, 100%, 80%)", 
                        "hsl(269, 100%, 80%)",
                        "hsl(326, 78%, 80%)",
                        "hsl(141, 84%, 80%)",
                        "hsl(48, 97%, 80%)",
                        "hsl(356, 100%, 80%)",
                        "hsl(204, 94%, 80%)"];

    public function boot(FriendRequestService $friendRequestService)
    {
        $this->friendRequestService = $friendRequestService;
    }

    public function searchFriends($username) {
        $this->search_friend_result = $this->friendRequestService->searchFriends($username);
    }

    public function addParticipants($id, $username) {
        $found = false;

        for ($i=0; $i < count($this->participants); $i++) { 
            if ($this->participants[$i]['id'] == $id) {
                $found = true;
                break;
            }
        }

        if ($found) {
            return;
        }

        $random_idx = random_int(0, count($this->colors) - 1);

        $this->participants[] = [
            'id' => $id,
            'username' => $username,
            'generated_color' => $this->colors[$random_idx]
        ];
    }

    public function removeParticipants($index) {
        unset($this->participants[$index]);
    }
};
?>

<div class="modal-content">
    <form action="{{ route('create agenda') }}" method="POST" class="d-flex flex-column h-100">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="modalTambahAgendaLabel"
                style="color: #1E3A8A;">
                Form Tambah Agenda
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body overflow-auto" style="height: 430px;">
            <div class="mb-3">
                <p class="form-label secondary text2">Penyelenggara : {{ auth()->user()->username }}</label>
            </div>
            
            <div class="mb-3">
                <label for="namaAgenda" class="form-label secondary text2">Nama Agenda</label>
                <input type="text" class="form-control" name="nama_agenda" id="namaAgenda" placeholder="Agenda Belajar..." />
            </div>

            <div class="mb-3">
                <label for="lokasiAgenda" class="form-label secondary text2">Lokasi Pelaksanaan</label>
                <textarea class="form-control" name="lokasi_agenda" id="lokasiAgenda" placeholder="Warung Kopi..." rows="3"></textarea>
            </div>

            <div class="row mb-3 gx-2">
                <div class="col-md-4">
                    <label for="waktuAgenda" class="form-label secondary text2">Waktu</label>
                    <input type="date" class="form-control" name="waktu_agenda" id="waktuAgenda" placeholder="dd/mm/yyyy" />
                </div>
                <div class="col-md-6">
                    <label for="jamAgenda" class="form-label secondary text2">Jam</label>
                    <div class="input-group">
                        <input type="time" class="form-control" name="jam_awal" id="jamAgenda" placeholder="-- : --">

                        <span class="input-group-text">–</span>

                        <input type="time" class="form-control" name="jam_akhir" placeholder="-- : --">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label m-0" for="cariPeserta">Tambah Peserta</label>
                <div class="d-flex flex-row form-text mt-0 mb-1">
                    <span class="me-1" style="color: #ff0000;">*</span>
                    <span>
                        Peserta yang dapat mengikuti agenda ini hanya pengguna yang sudah berteman dengan kamu.
                    </span>
                </div>
                <div class="d-flex">
                    <input class="form-control me-2" wire:model.blur="username_search" id="cariPeserta" type="search" placeholder="Cari Username..." aria-label="Search"/>
                    <button class="btn btn-outline-success" type="button" wire:click="searchFriends('{{ $username_search }}')">Cari</button>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 px-3 py-3 mb-3 border border-secondary-subtle rounded overflow-auto" style="height: 200px;">
                @foreach ($search_friend_result as $friend)
                    <div class="d-flex flex-row align-items-center gap-1">
                        <div class="flex-grow-1 ps-2 py-1 rounded" style="background-color: #D6D7DA;">
                            <p class="fs-6 m-0">{{ $friend['username'] }}</p>
                        </div>
                        <button class="btn btn-primary btn-sm" type="button" wire:click="addParticipants({{ $friend['id'] }}, '{{ $friend['username'] }}')">
                            Tambah
                        </button>
                    </div>
                @endforeach
            </div>

            <div>
                <p class="form-label secondary text2 m-0">Daftar Partisipan</p>
                <div class="d-flex flex-row form-text mt-0 mb-1">
                    <span class="me-1" style="color: #ff0000;">*</span>
                    <span>
                        Partisipan dibawah ini dapat mengikuti agenda dengan menerima undangan berupa tautan yang kamu bagikan. Tautan akan dibuat otomatis dan diberikan oleh sistem setelah kamu selesai membuat agenda.
                    </span>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 px-3 py-3 mb-3 border border-secondary-subtle rounded overflow-auto" style="height: 200px;">
                @foreach ($participants as $participant)
                    <div class="d-flex flex-row align-items-center gap-2 badge rounded-pill fs-6 fw-normal" 
                        style="background-color: {{ $participant['generated_color'] }}; width: fit-content;">
                        <span style="color: #111827;">{{ $participant['username'] }}</span>
                        <i class="fa-solid fa-xmark btn p-0" role="button" wire:click="removeParticipants({{ $loop->index }})" style="color: #111827;"></i>
                        <input name="participant_id[]" value="{{ $participant['id'] }}" hidden>
                    </div>
                @endforeach
            </div>
            
        </div>
        <div class="modal-footer justify-content-center">
            <button type="submit" class="btn " style="background-color: #1E3A8A; color: white;">Buat Agenda</button>
        </div>
    </form>
</div>