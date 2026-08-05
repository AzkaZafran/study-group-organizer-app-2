<?php

namespace App\Livewire;

use App\Models\Catatan;
use App\Services\CatatanService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ListCatatan extends Component
{
    private CatatanService $catatanService;
    public $list_catatan;

    #[Locked]
    public $id_agenda;

    public function mount($id_agenda) {
        $this->id_agenda = $id_agenda;

        try {
            $this->list_catatan = $this->getAgendaCatatanWithViews()->sortByDesc('updated_at');

            $list_id_catatans = $this->list_catatan->pluck('id_catatan')->toArray();

            $this->catatanService->markCatatanAsRead($list_id_catatans);

            $this->list_catatan->loadCount('viewed');
        } catch (Exception $e) {
            match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED'    => $this->addError(
                                                'list_catatan_error', 
                                                'Pengguna tidak terautentikasi.'
                                                ),
                'AGENDA_NOT_FOUND'          => $this->addError(
                                                'list_catatan_error', 
                                                "Agenda tidak dapat ditemukan."
                                                ),
                'USER_NOT_PERMITTED'        => $this->addError(
                                                'list_catatan_error', 
                                                'Pengguna bukan partisipan agenda ini.'
                                                ),
                default                     => $this->addError(
                                                'list_catatan_error', 
                                                'Something went wrong.'
                                                )
            };
        }
    }

    public function boot(CatatanService $catatanService) {
        $this->catatanService = $catatanService;
    }

    public function getAgendaCatatanWithViews() {
        $fetched_list_catatan = $this->catatanService->getAgendaCatatanWithViews($this->id_agenda);

        return $this->formatted_list_catatan($fetched_list_catatan);
    }

    #[On('catatan-created')]
    public function refresh_list_catatan() {
        try {
            $this->list_catatan = $this->getAgendaCatatanWithViews()->sortByDesc('updated_at');

            $list_id_catatans = $this->list_catatan->pluck('id_catatan')->toArray();

            $this->catatanService->markCatatanAsRead($list_id_catatans);

            $this->list_catatan->loadCount('viewed');
        } catch (Exception $e) {
            match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED'    => $this->addError(
                                                'list_catatan_error', 
                                                'Pengguna tidak terautentikasi.'
                                                ),
                'AGENDA_NOT_FOUND'          => $this->addError(
                                                'list_catatan_error', 
                                                "Agenda tidak dapat ditemukan."
                                                ),
                'USER_NOT_PERMITTED'        => $this->addError(
                                                'list_catatan_error', 
                                                'Pengguna bukan partisipan agenda ini.'
                                                ),
                default                     => $this->addError(
                                                'list_catatan_error', 
                                                'Something went wrong.'
                                                )
            };
        }
    }

    private function formatted_list_catatan($list_catatan) {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $list_catatan->each(function (Catatan $catatan) use ($auth_user) {
            $catatan->author_name = $catatan->author->username;
            $catatan->tanggal_diubah = $catatan->updated_at->format('d/m/Y H:i');
            $catatan->is_author = $catatan->id_author == $auth_user->id;
            $catatan->is_updated = $catatan->updated_at->greaterThan($catatan->created_at);
        });

        return $list_catatan;
    }

    public function render()
    {
        $data = [
            'list_catatan' => $this->list_catatan
        ];

        return view('livewire.list-catatan', ['wire_data' => $data]);
    }
}
