<div class="modal-content">

    <form wire:submit="editCatatan">
        <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="mb-3">
                <p class="form-label secondary text2">Judul Catatan : {{ $wire_data['judul_catatan'] }}</label>
            </div>

            <div class="mb-3">
                <label for="isiCatatan" class="form-label secondary text2">Isi</label>
                <textarea class="form-control @error('isi_catatan') is-invalid @enderror" wire:model="isi_catatan" id="isiCatatan" placeholder="Catatan agenda..." rows="3"></textarea>
                @error('isi_catatan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            @if ($errors->has('edit_catatan_error'))
                <ul class="mb-3 p-3 bg-danger rounded ps-4">
                    @foreach ($errors->get('edit_catatan_error') as $message)
                        <li class="text-white">
                            {{ $message }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <div>
                <button type="submit" class="btn w-25" style="background-color: #1E3A8A; color: white;">Edit</button>
            </div>
        </div>

        
    </form>
</div>
