@extends('layouts.app')

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
    <script>
        // clipboard
        var clipboard = new ClipboardJS('.btn-copy');
        clipboard.on('success', function(e) {
            var option = {delay: 1500}
            var clipToastEl = document.querySelector('.toast');
            var clipToast = new bootstrap.Toast(clipToastEl, option);
            clipToast.show();
        });

        // delete confirmation
        function confirmDelete(e) {
            e.preventDefault();

            var confirmationModal = new bootstrap.Modal(document.getElementById('delete-confirmation'));
            confirmationModal.show();

            var btnSure = document.querySelector('.btn-sure');
            btnSure.setAttribute('data-url', e.target.dataset.deleteUrl);
        }

        function executeDelete(e) {
            var deleteFormEl = document.getElementById('delete-form');
            deleteFormEl.setAttribute('action', e.target.dataset.url);
            deleteFormEl.submit();
        }
    </script>
@endpush

@section('content')
<div class="container">
    <div class="justify-content-center">
        <div class="mb-3">
            <a href="{{ route('videos.create') }}" class="btn btn-primary">Add New Video</a>
        </div>

        @if (session('status'))
            <div class="alert alert-info" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">{{ __('Videos') }}</div>
            <div class="card-body">
                @if($videos->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Size</th>
                                <th>Uploaded at</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($videos as $video)
                                <tr>
                                    <td>{{ $video->title }}</td>
                                    <td>{{ number_format($video->size / 1000000) }} MB</td>
                                    <td>{{ $video->created_at->format('Y/m/d') }}</td>
                                    <td class="text-end">
                                        <button data-clipboard-text="{{ route('show-video-url', $video->hashname) }}" class="btn btn-primary btn-copy">
                                            <span>
                                                <i class="bi-clipboard2-check-fill"></i>
                                            </span>
                                            <span>Video URL</span>
                                        </button>
                                        <button data-clipboard-text="{{ route('download-video-url', $video->hashname) }}" class="btn btn-success mx-2 btn-copy">
                                            <span>
                                                <i class="bi-clipboard2-check-fill"></i>
                                            </span>
                                            <span>Download URL</span>
                                        </button>
                                        <button data-delete-url="{{ route('videos.destroy', $video->id) }}" class="btn btn-danger" onclick="confirmDelete(event)">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning m-0">No video found.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-4">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Info</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                URL berhasil disalin.
            </div>
        </div>
    </div>

    {{-- confirmation modal --}}
    <div id="delete-confirmation" class="modal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure to delete this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sure" onclick="executeDelete(event)">Yes, Delete it</button>
                </div>
            </div>
        </div>
    </div>

    {{-- hidden form --}}
    <form id="delete-form" action="" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
