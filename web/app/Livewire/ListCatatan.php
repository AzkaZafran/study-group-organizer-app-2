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
            $this->load_list_catatan_with_views_and_mark_as_read();
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

    private function load_list_catatan_with_views_and_mark_as_read() {
        $fetched_list_catatan = $this->catatanService
                                    ->getAgendaCatatanWithViews($this->id_agenda)
                                    ->sortByDesc('updated_at');

        $this->list_catatan = $this->formatted_list_catatan($fetched_list_catatan);

        $list_id_catatans = $this->list_catatan->pluck('id_catatan')->toArray();

        $this->catatanService->markCatatanAsRead($list_id_catatans);

        $this->list_catatan->loadCount('viewed');
    }

    #[On('catatan-edited')]
    #[On('catatan-created')]
    public function refresh_list_catatan() {
        try {
            $this->load_list_catatan_with_views_and_mark_as_read();
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

    public function showEditModal($id_catatan) {
        try {
            $this->load_list_catatan_with_views_and_mark_as_read();

            $auth_user = Auth::user();

            if(!$auth_user) {
                throw new Exception('USER_NOT_AUTHENTICATED');
            }

            $catatan = $this->list_catatan->firstWhere('id_catatan', $id_catatan);

            if (empty($catatan)) {
                throw new Exception('CATATAN_NOT_FOUND');
            } elseif ($catatan->id_author != $auth_user->id) {
                throw new Exception('USER_NOT_PERMITTED');
            }

            $this->dispatch('load-edit-catatan', id_catatan: $id_catatan, 
                                                judul_catatan: $catatan->judul_catatan,
                                                isi_catatan: $catatan->catatan);
        } catch (Exception $e) {
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
                                                'Pengguna bukan partisipan agenda atau bukan author dari catatan ini.'
                                                ),
                default                     => $this->addError(
                                                'edit_catatan_error', 
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
