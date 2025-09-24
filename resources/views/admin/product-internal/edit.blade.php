@extends('layouts.admin.app')

@section('title', 'Edit Internal Product')

@section('content')
    <div class="card">
        <div class="card-header">Edit: {{ $productInternal->sku ?? 'Unnamed' }}</div>
        <div class="card-body">
            <form action="{{ route('admin.product-internal.update', $productInternal) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.product-internal._form', ['buttonText' => 'Update Product'])
            </form>
        </div>
    </div>
@endsection
