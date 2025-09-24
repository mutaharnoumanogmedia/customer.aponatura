@extends('layouts.admin.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-people-fill text-primary me-2"></i>Customer Management
                </h1>
                <p class="text-muted mb-0">Manage customer information and orders</p>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover data-table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#ID</th>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($customers['customers']))
                                @foreach ($customers['customers'] as $customer)
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted">#{{ $customer['id'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person text-muted"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ $customer['vorname'] }}
                                                        {{ $customer['nachname'] }}
                                                    </h6>
                                                    {{-- <small class="text-muted">Joined {{ \Carbon\Carbon::parse($customer['created_at'])->diffForHumans() }}</small> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $customer['email'] }}" class="text-decoration-none">
                                                <i class="bi bi-envelope me-2 text-primary"></i>{{ $customer['email'] }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt me-2 text-muted"></i>
                                                <div>
                                                    <div>{{ $customer['adresse'] }}</div>
                                                    <small class="text-muted">{{ $customer['country'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($customer['status'] == 1)
                                                <span class="badge bg-success bg-opacity-10    px-3 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10     px-3 py-1">
                                                    <i class="bi bi-dash-circle-fill me-1"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.customer.show', $customer['id']) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-start-pill">
                                                    <i class="bi bi-eye"></i>
                                                </a>


                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-exclamation-circle me-2"></i> No customers found
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        @include('components.page-iteration', [
            'total' => $customers['total'] ?? 0,
            'perPage' => 500,
        ])
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.03);
        }

        .avatar-placeholder {
            width: 40px;
            height: 40px;
            background-color: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
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
    </style>
@endsection


@section('script')
    @include('components.data-table-resources')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                responsive: true
            });
        });
    </script>
@endsection
