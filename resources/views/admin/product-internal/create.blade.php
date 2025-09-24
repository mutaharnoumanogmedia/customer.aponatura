@extends('layouts.admin.app')

@section('title', 'Add Internal Product')

@section('content')
    <div class="card">
        <div class="card-header">Add Internal Product</div>
        <div class="card-body">
            <form action="{{ route('admin.product-internal.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.product-internal._form', ['buttonText' => 'Create Product'])
            </form>
        </div>
    </div>
@endsection
