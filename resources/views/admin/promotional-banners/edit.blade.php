@extends('layouts.admin.app')

@section('content')
    <div class="container mt-4">
        <h2>Edit Banner</h2>
        <form action="{{ route('admin.promotional-banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Banner File</label>
                <input type="file" name="banner_file" class="form-control" id="bannerInput" accept="image/*">

                <div class="mt-3">
                    <img id="bannerPreview" src="{{ env("STORAGE_URL").'/'.($banner->banner_file) }}" alt="Banner Preview"
                        style="max-width: 100%; height: auto; border: 1px solid #ccc; padding: 5px;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">URL</label>
                <input type="url" name="url" class="form-control" value="{{ old('url', $banner->url) }}">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="active" value="1" class="form-check-input"
                    {{ old('active', $banner->active) ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.promotional-banners.index') }}" class="btn btn-secondary">Cancel</a>
        </form>

        <script>
            document.getElementById('bannerInput').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const preview = document.getElementById('bannerPreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        </script>

    </div>
@endsection
