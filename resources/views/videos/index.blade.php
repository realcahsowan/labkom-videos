@extends('layouts.app')

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
    <script>
        // clipboard
        var clipboard = new ClipboardJS('.btn-copy');
        clipboard.on('success', function(e) {
            console.log('URL berhasil disalin.');
        });
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
                                    <td>{{ $video->size }}</td>
                                    <td>{{ $video->created_at->format('Y/m/d') }}</td>
                                    <td class="text-end">
                                        <button data-clipboard-text="{{ route('show-video-url', $video->hashname) }}" class="btn btn-primary btn-copy">
                                            <span>
                                                <i class="bi-clipboard-fill"></i>
                                            </span>
                                            <span>Video URL</span>
                                        </button>
                                        <button data-clipboard-text="{{ route('download-video-url', $video->hashname) }}" class="btn btn-success mx-2 btn-copy">
                                            <span>
                                                <i class="bi-clipboard-fill"></i>
                                            </span>
                                            <span>Download URL</span>
                                        </button>
                                        <button class="btn btn-danger"
                                            onclick="event.preventDefault();
                                            if (window.confirm('Are you sure to delete this item?')) {
                                                 document.getElementById('delete-form').submit();
                                            }"
                                        >
                                            <span>
                                                <i class="bi-trash-fill"></i>
                                            </span>
                                            <span>Delete</span>
                                        </button>
                                        <form id="delete-form" action="{{ route('videos.destroy', $video->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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
</div>
@endsection
