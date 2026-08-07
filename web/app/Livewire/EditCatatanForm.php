<?php

namespace App\Livewire;

use App\Services\CatatanService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class EditCatatanForm extends Component
{
    private CatatanService $catatanService;
    public $isi_catatan;

    #[Locked]
    public $judul_catatan;

    #[Locked]
    public $id_catatan;

    public function boot(CatatanService $catatanService) {
        $this->catatanService = $catatanService;
    }

    #[On('load-edit-catatan')]
    public function loadEditModal($id_catatan, $judul_catatan, $isi_catatan) {
        $this->id_catatan = $id_catatan;
        $this->judul_catatan = $judul_catatan;
        $this->isi_catatan = $isi_catatan;

        $this->dispatch('show-edit-catatan-modal');
    }

    public function editCatatan() {
        $this->validate([
            'isi_catatan' => ['required', 'string', 'max:1000']
        ]);

        try {
            $this->catatanService->editCatatan(
                id_catatan:     $this->id_catatan,
                judul_catatan:  $this->judul_catatan,
                isi_catatan:    $this->isi_catatan
            );

            $this->dispatch('catatan-edited');
        } catch (\Exception $e) {
            match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED'    => $this->addError(
                                                'edit_catatan_error', 
                                                'Pengguna tidak terautentikasi.'
                                                ),
                'CATATAN_NOT_FOUND'          => $this->addError(
                                                'edit_catatan_error', 
                                                "Catatan tidak dapat ditemukan."
                                                ),
                'USER_NOT_PERMITTED'        => $this->addError(
                                                'edit_catatan_error', 
                                                'Pengguna bukan author dari catatan ini.'
                                                ),
                default                     => $this->addError(
                                                'edit_catatan_error', 
                                                'Something went wrong.'
                                                )
            };
        }

        
    }

    public function render()
    {
        $data = [
            'judul_catatan' => $this->judul_catatan
        ];

        return view('livewire.edit-catatan-form', ['wire_data' => $data]);
    }
}
