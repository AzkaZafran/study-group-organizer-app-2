<?php

namespace App\Livewire;

use App\Services\CatatanService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CreateCatatanForm extends Component
{
    private CatatanService $catatanService;

    public string $judul_catatan = 'Untitled';
    public string $isi_catatan;

    #[Locked]
    public $id_agenda;

    public function boot(CatatanService $catatanService) {
        $this->catatanService = $catatanService;
    }

    public function mount($id_agenda) {
        $this->id_agenda = $id_agenda;
    }

    public function createCatatan() {
        $this->validate([
            'judul_catatan' => ['nullable', 'max:300'],
            'isi_catatan' => ['required', 'max:1000']
        ]);

        $this->judul_catatan = blank($this->judul_catatan) ? 'Untitled' : $this->judul_catatan;

        try {
            $this->catatanService->createCatatan(
                id_agenda:      $this->id_agenda,
                judul_catatan:  $this->judul_catatan,
                isi_catatan:    $this->isi_catatan
            );
        } catch (\Exception $e) {
            match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => throw ValidationException::withMessages([
                                                'business_error' => 'Pengguna tidak terautentikasi.'
                                            ]),
                'AGENDA_NOT_FOUND' => throw ValidationException::withMessages([
                                                'business_error' => "Agenda tidak dapat ditemukan."
                                            ]),
                'USER_NOT_PERMITTED' => throw ValidationException::withMessages([
                                                'business_error' => 'Pengguna bukan partisipan agenda ini.'
                                            ]),
                'AGENDA_NOT_STARTED_YET' => throw ValidationException::withMessages([
                                                'business_error' => 'Catatan tidak dapat dibuat dalam agenda yang belum dimulai.'
                                            ]),
                default => throw ValidationException::withMessages([
                                                'business_error' => 'Something went wrong.'
                                            ]),
            };
        }

        $this->dispatch('catatan-created');
    }

    public function render()
    {
        return view('livewire.create-catatan-form');
    }
}
