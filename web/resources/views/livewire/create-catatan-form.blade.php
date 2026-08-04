<div class="modal-content">

    <form wire:submit="createCatatan">
        <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="mb-3">
                <label for="namaAgenda" class="form-label secondary text2">Judul Catatan</label>
                <input type="text" class="form-control @error('judul_catatan') is-invalid @enderror" wire:model="judul_catatan" id="namaAgenda" />
                @error('judul_catatan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
        </div>

            <div class="mb-3">
                <label for="lokasiAgenda" class="form-label secondary text2">Isi</label>
                <textarea class="form-control @error('isi_catatan') is-invalid @enderror" wire:model="isi_catatan" id="lokasiAgenda" placeholder="Catatan agenda..." rows="3"></textarea>
                @error('isi_catatan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            @if ($errors->has('business_error'))
                <ul class="mb-3 p-3 bg-danger rounded ps-4">
                    @foreach ($errors->get('business_error') as $message)
                        <li class="text-white">
                            {{ $message }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <div>
                <button type="submit" class="btn w-25" style="background-color: #1E3A8A; color: white;">Kirim</button>
            </div>
        </div>

        
    </form>
</div>
