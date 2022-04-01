@extends('layouts.app')

@section('content')
<div class="container">
    <div class="justify-content-center">
        <div class="mb-3">
            <a href="{{ route('videos.index') }}" class="btn btn-dark">Videos List</a>
        </div>

        @if (session('status'))
            <div class="alert alert-info" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">{{ __('Videos') }}</div>
            <div class="card-body">
                <div>
                    <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" placeholder="Judul Video" value="{{ old('title') }}" @class([
                                'form-control',
                                'is-invalid' => $errors->has('title')
                            ])>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="3" @class([
                                'form-control',
                                'is-invalid' => $errors->has('description')
                            ])>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Video File</label>
                            <input type="file" id="title" name="video_file" placeholder="File Video" @class([
                                'form-control',
                                'is-invalid' => $errors->has('video_file')
                            ])>
                            @error('video_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-dark">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
