@extends('layouts.admin.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-images text-primary me-2"></i>Promotional Banners
                </h2>
                <p class="text-muted mb-0">Manage website promotional banners and campaigns</p>
            </div>
            <a href="{{ route('admin.promotional-banners.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add New Banner
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Banner Preview</th>
                                <th>Destination URL</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banners as $banner)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ env('STORAGE_URL') . '/' . $banner->banner_file }}"
                                                    alt="Banner {{ $loop->iteration }}" class="rounded-3 shadow-sm"
                                                    style="width: 120px; height: auto; max-height: 60px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">Banner #{{ $banner->id }}</div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($banner->created_at)->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ $banner->url }}" target="_blank"
                                            class="text-decoration-none text-truncate d-inline-block"
                                            style="max-width: 200px;">
                                            <i class="bi bi-link-45deg me-1 text-primary"></i>
                                            {{ $banner->url }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($banner->active)
                                            <span class="badge bg-success px-3 py-1">
                                                <i class="bi bi-check-circle-fill me-1"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary   px-3 py-1">
                                                <i class="bi bi-dash-circle-fill me-1"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group" role="group">

                                            <a href="{{ route('admin.promotional-banners.edit', $banner->id) }}"
                                                class="btn btn-sm btn-outline-secondary me-2" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.promotional-banners.destroy', $banner->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-end-pill" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($banners->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-images display-4 text-muted mb-3"></i>
                        <h4 class="mb-3">No Banners Found</h4>
                        <p class="text-muted mb-4">You haven't created any promotional banners yet.</p>
                        <a href="{{ route('admin.promotional-banners.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Create Your First Banner
                        </a>
                    </div>
                @endif


            </div>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.03);
        }

        .badge.rounded-pill {
            padding: 0.35em 0.65em;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 50rem;
            border-bottom-left-radius: 50rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 50rem;
            border-bottom-right-radius: 50rem;
        }

        .table img {
            transition: transform 0.2s;
        }

        .table img:hover {
            transform: scale(1.05);
        }
    </style>
@endsection
