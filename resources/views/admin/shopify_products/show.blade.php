@extends('layouts.admin.app')
@section('content')
    <div class="container py-4">
        <a href="{{ route('admin.shopify-products.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            ← Back to Products
        </a>

        <div class="card shadow-sm">
            <div class="row g-0">
                {{-- Image --}}
                <div class="col-md-4">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" class="img-fluid rounded-start" alt="{{ $product->title }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:100%;">
                            <span class="text-muted">No Image</span>
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div class="col-md-8">
                    <div class="card-body">
                        <h2 class="card-title">{{ $product->title }}</h2>
                        <p class="card-text text-muted">
                            <small>Handle: <code>{{ $product->handle }}</code></small><br>
                            <small>Vendor: {{ $product->vendor ?? '—' }}</small>
                        </p>

                        <p class="card-text">{{ $product->description }}</p>

                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">
                                <strong>Shopify ID:</strong> {{ $product->shopify_id }}
                            </li>
                            <li class="list-group-item">
                                <strong>Type:</strong> {{ $product->product_type ?? '—' }}
                            </li>
                            <li class="list-group-item">
                                <strong>Brand:</strong> {{ $product->brand ?? '—' }}
                            </li>
                            <li class="list-group-item">
                                <strong>Tags:</strong>
                                @if ($product->tags)
                                    @foreach (explode(',', $product->tags) as $tag)
                                        <span class="badge bg-secondary">{{ trim($tag) }}</span>
                                    @endforeach
                                @else
                                    &mdash;
                                @endif
                            </li>
                            <li class="list-group-item">
                                <strong>Visible to Customer:</strong>
                                @if ($product->show_to_customer)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </li>
                            <li class="list-group-item">
                                <strong>Price:</strong> {{ number_format($product->price, 2) }} {{ $product->currency }}
                            </li>
                            <li class="list-group-item">
                                <strong>Shopify Created:</strong>
                                {{ $product->shopify_created_at  ?? '—' }}
                            </li>
                            <li class="list-group-item">
                                <strong>Shopify Updated:</strong>
                                {{ $product->shopify_updated_at  ?? '—' }}
                            </li>
                        </ul>

                        @if ($product->online_store_url)
                            <a href="{{ $product->online_store_url }}" target="_blank" class="btn btn-primary">
                                View on Store
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
