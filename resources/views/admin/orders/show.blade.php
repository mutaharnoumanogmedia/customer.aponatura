@extends('layouts.admin.app')

@section('content')
    <div class="container py-4" id="printSection">
        <div>

            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left me-2"></i>{{ __('Back to Orders') }}
            </a>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1 text-primary">
                    <i class="bi bi-receipt me-2"></i>{{ __('Order Details') }}
                </h2>
                <p class="text-muted mb-0"><i class="bi bi-hash me-1"></i> {{ $order_number }}</p>
                <p class="text-muted"><i class="bi bi-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($orders[0]['datum'])->format('F j, Y \a\t g:i A') }}</p>
            </div>
            <button class="btn btn-primary d-flex align-items-center" onclick="printPage()">
                <i class="bi bi-printer me-2"></i>{{ __('Print') }}
            </button>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>{{ __('Customer Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><i class="bi bi-person me-2"></i><strong>{{ __('Name:') }}</strong>
                            {{ $orders[0]['vorname'] }} {{ $orders[0]['nachname'] }}</p>
                        <p class="mb-2"><i class="bi bi-envelope me-2"></i><strong>{{ __('Email:') }}</strong>
                            {{ $orders[0]['email'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><i class="bi bi-house-door me-2"></i><strong>{{ __('Address:') }}</strong>
                            {{ $orders[0]['adresse'] }}</p>
                        <p class="mb-2"><i class="bi bi-geo-alt me-2"></i><strong>{{ __('City:') }}</strong>
                            {{ $orders[0]['ort'] }}, {{ $orders[0]['plz'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-cart-check me-2"></i>{{ __('Order Items') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4"><i class="bi bi-hash"></i></th>
                                <th>{{ __('Product') }}</th>
                                <th class="text-end"><i class="bi bi-box-seam me-1"></i>{{ __('Qty') }}</th>
                                <th class="text-end"><i class="bi bi-currency-euro me-1"></i>{{ __('Price') }}</th>
                                <th class="text-end pe-4"><i class="bi bi-cash-stack me-1"></i>{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $index => $order)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="bi bi-box-seam fs-4 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $order['product_name'] }}</h6>
                                                <small class="text-muted"><i
                                                        class="bi bi-truck me-1"></i>{{ strtoupper($order['transport']) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ $order['menge'] }}</td>
                                    <td class="text-end">
                                        €{{ number_format($order['gesamtpreis'] / 100 / $order['menge'], 2, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        €{{ number_format($order['gesamtpreis'] / 100, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>{{ __('Order Summary') }}</h5>
                    </div>
                    <div class="card-body">
                        @php $details = $orders[0]['shop_system_details'][0] ?? null; @endphp
                        @php
                            $status = \App\Enums\OrderStatus::tryFrom($order['status']);
                        @endphp

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted"><i class="bi bi-info-circle me-2"></i>{{ __('Status') }}</span>
                            <span>
                                <span class="badge {{ strtolower($status->bootstrapClass()) }} rounded-pill px-3 py-1">
                                    <i class="bi {{ $status->icon() }} me-1"></i>{{ __($status->label()) }}
                                </span>
                            </span>
                        </div>
                        @if ($details != null)
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="bi bi-credit-card me-2"></i>{{ __('Payment') }}</span>
                                <span>{{ ucfirst($details['payment_provider'] ?? '') }}</span>
                            </div>
                            
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted"><i class="bi bi-cart me-2"></i>{{ __('Subtotal') }}</span>
                                <span>€{{ number_format(($details['total_price'] - $details['platform_fee']) / 100, 2, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted"><i class="bi bi-coin me-2"></i>{{ __('Platform Fee') }}</span>
                                <span>€{{ number_format($details['platform_fee'] / 100, 2, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span><i class="bi bi-cash-stack me-2"></i>{{ __('Total') }}</span>
                                <span class="text-primary">€
                                    {{ number_format($details['total_price'] / 100, 2, ',', '.') }}</span>
                            </div>
                        @else
                            <div class="text-muted">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ __('No payment details available') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-truck me-2"></i>{{ __('Shipping Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-4">
                            <div class="flex-shrink-0 me-3">
                                <i class="bi bi-truck fs-3 text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ strtoupper($orders[0]['transport']) }} Shipping</h6>
                                <p class="text-muted mb-0"><i class="bi bi-calendar-check me-1"></i>Estimated delivery:
                                    {{ \Carbon\Carbon::parse($orders[0]['datum'])->addDays(3)->format('F j, Y') }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i class="bi bi-geo-alt fs-3 text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ __('Shipping Address') }}</h6>
                                <p class="text-muted mb-0">
                                    {{ $orders[0]['vorname'] }} {{ $orders[0]['nachname'] }}<br>
                                    {{ $orders[0]['adresse'] }}<br>
                                    {{ $orders[0]['plz'] }} {{ $orders[0]['ort'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #printSection,
                #printSection * {
                    visibility: visible;
                }

                #printSection {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .card {
                    border: 1px solid #dee2e6 !important;
                }

                .table {
                    page-break-inside: avoid;
                }
            }
        </style>

        <script>
            function printPage() {
                window.print();
            }
        </script>
    @endsection
