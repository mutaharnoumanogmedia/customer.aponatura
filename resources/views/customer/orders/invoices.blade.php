@extends('layouts.customer.app')
@section('content')
    <!-- Rechnungen Seite (Invoices Page) -->
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                {{ __('My Invoices') }}
            </h2>

        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if ($invoices && count($invoices) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 data-table">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Invoice Number') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Order Reference') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td class="fw-semibold">{{ $invoice['invoice_number_id'] }}</td>
                                        <td>{{ $invoice['datevuebertrag'] ? \Carbon\Carbon::parse($invoice['datevuebertrag'])->format('d. F Y') : '-' }}
                                        </td>
                                        <td>€
                                            {{ number_format(($invoice['payments'][0]['amount'] ?? 0) / 100, 2, ',', '.') }}
                                        </td>
                                        <td>{{ $invoice['order_reference'] ?? '-' }}</td>
                                        <td>
                                            @if ($invoice['payment_date'])
                                                <span class="badge bg-success bg-opacity-10 text-white">
                                                    <i class="bi bi-check-circle me-1"></i> {{ __('Paid') }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-white">
                                                    <i class="bi bi-hourglass-split me-1"></i> {{ __('Open') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.orders.invoice', $invoice['id']) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i> {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">{{ __('No invoices found') }}.</div>
                @endif
            </div>
        </div>


    </div>
@endsection
@section('script')
    @include('components.data-table-resources')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                // Your DataTable options here
            });
        });
    </script>
@endsection
