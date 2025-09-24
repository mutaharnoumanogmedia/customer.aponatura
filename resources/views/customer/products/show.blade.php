@extends('layouts.customer.app')

@section('title', 'View Internal Product')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Product Details: {{ $productInternal->sku ?? '—' }}</h3>
        <div>
            <a href="{{ route('customer.product.index') }}" class="btn btn-secondary me-2">
                ← Back to All
            </a>
            
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-6">
                    <strong>SKU:</strong>
                    <div>{{ $productInternal->sku }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Internal Code:</strong>
                    <div>{{ $productInternal->internal_code }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Stock Qty:</strong>
                    <div>{{ $productInternal->stock_quantity }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Min Threshold:</strong>
                    <div>{{ $productInternal->min_stock_threshold }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Max Threshold:</strong>
                    <div>{{ $productInternal->max_stock_threshold }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Cost Price (€):</strong>
                    <div>{{ number_format($productInternal->price, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Wholesale Price (€):</strong>
                    <div>{{ number_format($productInternal->wholesale_price, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-4">
                    <strong>Retail Price (€):</strong>
                    <div>{{ number_format($productInternal->retail_price, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Type:</strong>
                    <div>{{ ucfirst($productInternal->product_type) }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <div>{{ ucfirst(str_replace('_', ' ', $productInternal->status)) }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Received At:</strong>
                    <div>{{ optional($productInternal->received_at)->format('Y-m-d') ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Expires At:</strong>
                    <div>{{ optional($productInternal->expires_at)->format('Y-m-d') ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Location:</strong>
                    <div>{{ $productInternal->location }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Batch Number:</strong>
                    <div>{{ $productInternal->batch_number }}</div>
                </div>
                @if ($productInternal->image_path)
                    <div class="col-6">
                        <strong>Product Image:</strong>
                        <div class="mt-2">
                            <img src="{{ env('STORAGE_URL') . '/' . $productInternal->image_path }}" class="img-fluid rounded"
                                alt="Product Image">
                        </div>
                    </div>
                @endif
                @if ($productInternal->download_url)
                    <div class="col-6">
                        <strong>Digital File:</strong>
                        <div class="mt-2">
                            <a href="{{ $productInternal->download_url }}" target="_blank">
                                Download File
                            </a>
                        </div>
                    </div>
                @endif
                @if ($productInternal->notes)
                    <div class="col-12">
                        <strong>Notes:</strong>
                        <div class="mt-1">{{ $productInternal->notes }}</div>
                    </div>
                @endif
                @if ($productInternal->attributes)
                    <div class="col-12">
                        <strong>Attributes:</strong>
                        <div class="mt-1">{{ $productInternal->attributes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
