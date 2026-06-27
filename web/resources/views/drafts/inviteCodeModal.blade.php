@extends('layouts.app')

@section('content')
    <div class="modal fade" id="inviteCodeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #1E3A8A;">Link Undangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text2">Berikut tautan undangan agenda yang dapat dibagikan:</p>
                    <div class="input-group mt-3">
                        <input
                            type="text"
                            class="form-control"
                            id="inviteLink"
                            value="https://example.com/invite/ABC123"
                            readonly
                        >

                        <button
                            class="btn btn-outline-primary"
                            type="button"
                            onclick="copyInviteLink()"
                        >
                            <i class="fa-regular fa-clone"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(
                document.getElementById('inviteCodeModal')
            );

            modal.show();
        });

        function copyInviteLink() {
            const input = document.getElementById('inviteLink');

            navigator.clipboard.writeText(input.value);
        }
    </script>
@endsection