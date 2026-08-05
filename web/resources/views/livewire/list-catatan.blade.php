<div class="list-catatan-container col-md-6 p-0 border-start border-end border-secondary-subtle scroll-y-inv">
    
    @if ($errors->has('list_catatan_error'))
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
                        </ul>

                        <div class="d-flex justify-content-center">
                            @if (!Auth::user())
                                <a role="button" href="{{ route('login') }}" class="btn w-25" style="background-color: #1E3A8A; color: white;">Kembali Ke Login Page</a>
                            @else
                                <a role="button" href="{{ route('dashboard') }}" class="btn w-25" style="background-color: #1E3A8A; color: white;">Kembali Ke Dashboard Page</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(
                    document.getElementById('modallistcatatanerror')
                );

                modal.show();
            });
        </script>
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
                        <span style="color: hsl(0, 0%, 65%);">• {{ $catatan->tanggal_dibuat }} · {{ $catatan->viewed_count }} views</span>
                    </p>

                    <div class="flex-grow-1"></div>

                    @if ($catatan->is_author)
                        <div class="d-flex flex-row gap-2 px-2 rounded" style="background-color: #F8FAFC;">
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                            <a role="button" class="btn p-0">
                                <i class="fa-solid fa-trash-can" style="font-size: 14px; color: #ff0000;"></i>
                            </a>
                        </div>
                    @endif
                    
                </div>

                <h5 class="fw-semibold">{{ $catatan->judul_catatan }}</h5>
                <p class="fs-6">{{ $catatan->catatan }}</p>
            </div>
        @endforeach
    @endif

</div>
