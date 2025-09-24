@extends('layouts.customer.app')

@section('content')
    <style>
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            font-weight: 500;
        }

        .text-muted {
            opacity: 0.8;
        }

        .table th {
            border-top: none;
            font-weight: 500;
        }

        .badge {
            font-weight: 400;
        }

        /* Your existing styles... */

        @media print {
            body * {
                visibility: hidden;
            }

            #invoice-container,
            #invoice-container * {
                visibility: visible;
            }

            #invoice-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }

            .table {
                page-break-inside: avoid;
            }
        }
    </style>
    <div class="container my-5" id="invoice-container">
        {{-- Invoice Header --}}
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-receipt-cutoff fs-1 text-primary me-3"></i>
                    <div>
                        <h1 class="h2 mb-0">Invoice #{{ $invoice->invoice_number_id }}</h1>
                        <small class="text-muted">Invoice ID: {{ $invoice->id }}</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-calendar-check me-2 text-secondary"></i>
                            <div>
                                <strong>Date transferred:</strong>
                                {{ \Carbon\Carbon::parse($invoice->datevuebertrag)->format('M d, Y H:i') }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-earmark-text me-2 text-secondary"></i>
                            <div>
                                <strong>Invoice Date:</strong>
                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-cart me-2 text-secondary"></i>
                            <div>
                                <strong>Order Date:</strong>
                                {{ \Carbon\Carbon::parse($invoice->order_date)->format('M d, Y H:i') }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-credit-card me-2 text-secondary"></i>
                            <div>
                                <strong>Payment Date:</strong>
                                {{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('M d, Y H:i') : '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Address Cards --}}
        <div class="row mb-4">
            {{-- Shipping Address --}}
            <div class="col-md-6 mb-4">
                <div class="card h-100 ">
                    <div class="card-header bg-secondary text-white d-flex align-items-center">
                        <i class="bi bi-truck me-2"></i>
                        <span>{{ __('Shipping Address') }}</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="bi bi-person me-2 text-muted"></i>
                            @if (is_object($invoice->shipping_address->firstname))
                                {{ $invoice->shipping_address->firstname->firstname }}
                            @else
                                {{ $invoice->shipping_address->firstname ?? '' }}
                            @endif
                            {{ $invoice->shipping_address->lastname ?? '' }}
                        </p>

                        <p class="mb-2">
                            <i class="bi bi-geo-alt me-2 text-muted"></i>
                            {{ $invoice->shipping_address->adresse->street ?? '' }}
                        </p>

                        <p class="mb-0">
                            <i class="bi bi-building me-2 text-muted"></i>
                            {{ $invoice->shipping_address->zip ?? '' }}
                            {{ $invoice->shipping_address->city->city ?? $invoice->shipping_address->city }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="col-md-6 mb-4">
                <div class="card h-100  ">
                    <div class="card-header bg-secondary text-white d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>{{ __('Customer Information') }}</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="bi bi-person me-2 text-muted"></i>
                            {{ $invoice->customer->vorname }}
                            {{ $invoice->customer->nachname }}
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-geo-alt me-2 text-muted"></i>
                            {{ $invoice->customer->adresse }}
                            {{ $invoice->customer->plz }}
                            {{ $invoice->customer->ort }}
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-globe me-2 text-muted"></i>
                            Country:
                            {{ \App\Models\Countries::getCountryById($invoice->customer->country) }}
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-envelope me-2 text-muted"></i>
                            Email: {{ $invoice->customer->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vendor & Storage --}}
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <div class="card h-100 ">
                    <div class="card-header bg-secondary text-white d-flex align-items-center">
                        <i class="bi bi-shop me-2"></i>
                        <span>{{ __('Vendor Information') }}</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="bi bi-building me-2 text-muted"></i>
                            <strong>{{ $invoice->vendor->vendor_name }}</strong>
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-briefcase me-2 text-muted"></i>
                            Company: {{ $invoice->vendor->companyname }}
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-envelope me-2 text-muted"></i>
                            Email: {{ $invoice->vendor->invoice_from_email }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100 ">
                    <div class="card-header bg-secondary text-light d-flex align-items-center">
                        <i class="bi bi-archive me-2"></i>
                        <span>{{ __('Storage Location') }}</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="bi bi-geo-alt me-2 text-muted"></i>
                            {{ $invoice->storagelocation->adresse->street ?? '' }}
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-building me-2 text-muted"></i>
                            {{ $invoice->storagelocation->zip ?? '' }}
                            {{ $invoice->storagelocation->city->city ?? $invoice->storagelocation->city }}

                            {{ isset($invoice->storagelocation->country->country)
                                ? \App\Models\Countries::getCountryById($invoice->storagelocation->country->country)
                                : $invoice->storagelocation->country->name ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payments --}}
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-secondary text-white d-flex align-items-center">
                        <i class="bi bi-credit-card me-2"></i>
                        <span>{{ __('Payments') }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <i class="bi bi-hash me-1"></i>
                                        {{ __('Transaction') }}
                                    </th>
                                    <th>
                                        <i class="bi bi-currency-exchange me-1"></i>
                                        {{ __('Amount') }}
                                    </th>
                                    <th>
                                        <i class="bi bi-wallet me-1"></i>
                                        {{ __('Gateway') }}
                                    </th>
                                    <th>
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ __('Date') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->payments as $p)
                                    <tr>
                                        <td>{{ $p->transaction_reference }}</td>
                                        <td>
                                            <span>
                                                {{ number_format($p->amount / 100, 2) }} {{ $p->currency }}
                                            </span>
                                        </td>
                                        <td>{{ $p->payment_gateway->description }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="row no-print">
            <div class="col">
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-dark btn-print">
                        <i class="bi bi-printer me-2"></i> Print Invoice
                    </button>
                    <button class="btn btn-primary btn-pdf">
                        <i class="bi bi-download me-2"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Print button functionality
            document.querySelector('.btn-print').addEventListener('click', function() {
                window.print();
            });

            // PDF button functionality (placeholder)
            document.querySelector('.btn-pdf').addEventListener('click', function() {
                // You would typically implement this with a backend route
                alert('PDF generation would be handled by the server');
                // window.location.href = "{{-- route('invoice.pdf', $invoice->id) --}}";
            });
        });

        // Auto-print if ?print=1 is in URL
        if (window.location.search.includes('print=1')) {
            window.addEventListener('afterprint', function() {
                // Close window after printing if opened in new tab
                if (window.opener) {
                    window.close();
                }
            });
            window.print();
        }
    </script>
@endsection
