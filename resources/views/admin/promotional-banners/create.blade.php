@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <form action="{{ route('admin.promotional-banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Banner File</label>
                <input type="file" name="banner_file" class="form-control" id="bannerInput" accept="image/*" required>

                <div class="mt-3">
                    <img id="bannerPreview" src="#" alt="Banner Preview"
                        style="max-width: 100%; height: auto; display: none; border: 1px solid #ccc; padding: 5px;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">URL</label>
                <input type="url" name="url" class="form-control">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="active" value="1" class="form-check-input" checked>
                <label class="form-check-label">Active</label>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('admin.promotional-banners.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>


    <script>
        document.getElementById('bannerInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('bannerPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        });
    </script>
@endsection
