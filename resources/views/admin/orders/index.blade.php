@extends('layouts.admin.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-0">
                    <i class="bi bi-receipt text-primary me-2"></i>Orders Management
                </h1>
                <p class="text-muted mb-0">View and manage all customer orders</p>
            </div>

        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover data-table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (isset($allOrders['orders']))
                                @foreach ($allOrders['orders'] as $order)
                                    <tr>
                                        <td class="ps-4 fw-bold">
                                            <a href="{{ route('admin.orders.show', $order['bestellNr']) }}"
                                                class="text-decoration-none">#{{ $order['bestellNr'] }}</a>
                                            <div class="text-muted small">
                                                {{ strtoupper($order['transport']) }} Shipping
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 36px; height: 36px;">
                                                        <i class="bi bi-person text-muted"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ $order['vorname'] }} {{ $order['nachname'] }}</h6>
                                                    <small class="text-muted">{{ $order['email'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold">
                                            € {{ number_format($order['gesamtpreis'] / 100, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ \Carbon\Carbon::createFromTimestamp($order['datum'])->format('M d, Y') }}</span>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::createFromTimestamp($order['datum'])->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $status = \App\Enums\OrderStatus::tryFrom($order['status']);
                                            @endphp
                                            <span
                                                class="badge {{ strtolower($status->bootstrapClass()) }} bg-opacity-10 px-3 py-1">
                                                <i class="bi {{ $status->icon() }} me-1"></i>{{ __($status->label()) }}
                                            </span>
                                        </td>
                                        <td class="">
                                            <a href="{{ route('admin.orders.show', $order['bestellNr']) }}"
                                                class="btn btn-sm btn-outline-primary rounded-start-pill me-3"
                                                title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="{{ $order['tracking_url'] }}" class="btn btn-sm btn-warning"
                                                target="_blank" title="Tracking">
                                                <i class="bi bi-truck"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-exclamation-circle me-2"></i> No orders found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
        @include('components.page-iteration', [
            'total' => $allOrders['total'] ?? 0,
            'perPage' => 500,
        ])
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
    </style>
@endsection
@section('script')
    @include('components.data-table-resources')
    <script>
        $(document).ready(function() {
            const table = $('.data-table').DataTable({
                // DataTable options
            });

            table.on('draw', () => {
                const info = table.page.info();
                // info.start is zero-based
                const start = info.start + 1;
                const end = info.end;
                const total = info.recordsTotal;

                $('.data-table-info').text(`Showing ${start} to ${end} of ${total} entries`);
            });
        });
    </script>
@endsection
