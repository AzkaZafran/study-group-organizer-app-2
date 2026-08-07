<div class="list-catatan-container col-md-6 p-0 border-start border-end border-secondary-subtle scroll-y-inv">
    
    @if ($errors->has('list_catatan_error') || $errors->has('edit_catatan_error'))
        <div class="modal fade" id="modallistcatatanerror" tabindex="-1" 
            aria-labelledby="modalListCatatanError" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-3">
                        <p class="text-danger fs-3 fs-semibold text-center mb-4">Fatal Error!</p>

                        <ul class="mb-3 ps-4">
                            @foreach ($errors->get('list_catatan_error') as $message)
                                <li class="fs-medium">
                                    {{ $message }}
                                </li>
                            @endforeach
                            @foreach ($errors->get('edit_catatan_error') as $message)
                                <li class="fs-medium">
                                    {{ $message }}
                                </li>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-center">
                            @if (!Auth::user())
                                <a role="button" href="{{ route('login') }}" class="btn w-50" style="background-color: #1E3A8A; color: white;">Kembali Ke Login Page</a>
                            @else
                                <a role="button" href="{{ route('dashboard') }}" class="btn w-50" style="background-color: #1E3A8A; color: white;">Kembali Ke Dashboard Page</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($wire_data['list_catatan']->isEmpty())
        <div class="list-catatan-container d-flex justify-content-center align-items-center">
            <p class="fs-5 fw-semibold text-secondary">Agenda ini belum memiliki catatan apapun.</p>
        </div>
    @else
        @foreach ($wire_data['list_catatan'] as $catatan)
        
            <div class="catatan-container border-bottom border-secondary-subtle p-3">
                <div class="d-flex flex-row mb-2" style="min-height: 26px;">

                    <p class="m-0" style="font-size: 14px;">
                        <span class="fw-medium">{{ $catatan->author_name }}</span>
                        <span style="color: hsl(0, 0%, 65%);">• {{ $catatan->tanggal_diubah }} · {{ $catatan->viewed_count }} views</span>
                        @if ($catatan->is_updated)
                            <span class="fw-medium"> • Edited</span>
                        @endif
                    </p>

                    <div class="flex-grow-1"></div>

                    @if ($catatan->is_author)
                        <div class="d-flex flex-row gap-2 px-2 rounded" style="background-color: #F8FAFC;">
                            <a role="button" wire:click="showEditModal({{ $catatan->id_catatan }})" class="btn p-0">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                            <a role="button" class="btn p-0"
                                data-bs-dismiss="modal"
                                data-bs-toggle="modal"
                                data-bs-target={{ "#deleteCatatanModal" . $loop->index }}>
                                <i class="fa-solid fa-trash-can" style="font-size: 14px; color: #ff0000;"></i>
                            </a>
                        </div>

                        <div class="modal fade" id={{ "deleteCatatanModal" . $loop->index }} tabindex="-1" 
                            aria-labelledby="modalDetailCatatanLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDeleteCatatanLabel"
                                            style="color: #1E3A8A;">
                                            Hapus Catatan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="d-flex flex-column align-items-center">
                                            <h5 class="text text-center">Apakah Anda Yakin Ingin Menghapus Catatan "{{ $catatan->catatan }}"?</h5>
                                            <h6 class="text fw-bolder" style="color: #ff0000;">*Tindakan ini akan menghapus catatan secara permanen</h6>
                                            <div class="d-flex flex-row justify-content-center gap-2 mt-3">
                                                <button type="button" class="btn btn-back" 
                                                        data-bs-dismiss="modal" style="width: 175px;">
                                                    Tidak
                                                </button>

                                                <form action="{{ route('delete catatan', ['id_catatan' => $catatan->id_catatan]) }}" method="POST">
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
                    @endif
                    
                </div>

                <h5 class="fw-semibold">{{ $catatan->judul_catatan }}</h5>
                <p class="fs-6">{{ $catatan->catatan }}</p>
            </div>
        @endforeach
    @endif

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
