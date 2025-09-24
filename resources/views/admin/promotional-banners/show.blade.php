@extends('layouts.admin.app')

@section('content')

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Banner Details</h4>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label fw-bold">Banner Image</label><br>
                    @if ($banner->banner_file)
                        <img src="{{ url($banner->banner_file) }}" alt="Banner" class="img-fluid border p-2"
                            style="max-width: 100%; height: auto;">
                    @else
                        <p class="text-muted">No banner uploaded.</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">URL</label><br>
                    @if ($banner->url)
                        <a href="{{ $banner->url }}" target="_blank">{{ $banner->url }}</a>
                    @else
                        <span class="text-muted">No URL provided</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label><br>
                    <span class="badge bg-{{ $banner->active ? 'success' : 'secondary' }}">
                        {{ $banner->active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Created At</label><br>
                    <span>{{$banner->created_at ?  $banner->created_at->format('d M Y, h:i A') : '' }}</span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Last Updated</label><br>
                    <span>{{ $banner->updated_at ? $banner->updated_at->format('d M Y, h:i A') : '' }}</span>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.promotional-banners.edit', ["promotional_banner" => $banner->id]) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.promotional-banners.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
