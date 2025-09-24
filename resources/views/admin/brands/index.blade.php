@extends('layouts.admin.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-tags-fill text-primary me-2"></i>Brand Management
                </h1>
                <p class="text-muted mb-0">Manage product brands and logos</p>
            </div>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add New Brand
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Brand Details</th>
                                <th>Logo</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <h6 class="mb-0">{{ $brand->name }}</h6>
                                        <small class="text-muted">Created: {{ $brand->created_at }}</small>
                                    </td>
                                    <td>
                                        @if ($brand->logo_path)
                                            <div class="bg-light p-2 rounded-3 d-inline-block">
                                                <img src="{{ env('STORAGE_URL') . '/' . $brand->logo_path }}"
                                                    alt="{{ $brand->name }}" class="img-fluid"
                                                    style="max-height: 50px; max-width: 100px; object-fit: contain;">
                                            </div>
                                        @else
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-image me-1"></i> No Logo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-start-pill me-2"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this brand?')">
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

                @if ($brands->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-tags display-4 text-muted mb-3"></i>
                        <h4 class="mb-3">No Brands Found</h4>
                        <p class="text-muted mb-4">You haven't created any brands yet.</p>
                        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Create Your First Brand
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
            transform: scale(1.1);
        }
    </style>
@endsection
