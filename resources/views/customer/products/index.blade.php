@extends('layouts.customer.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Internal Products</h3>
            <a href="{{ route('admin.product-internal.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Add Product (Internal)
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form id="filter-form" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="sku" id="filter-sku" class="form-control"
                            placeholder="Search by SKU">
                    </div>
                    <div class="col-md-3">
                        <select name="product_type" id="filter-product-type" class="form-select">
                            <option value="">All Types</option>
                            <option value="physical">Physical</option>
                            <option value="digital">Digital</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" id="filter-status" class="form-select">
                            <option value="">All Status</option>
                            <option value="in_stock">In Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover" id="product-internal-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SKU</th>
                            <th>Type</th>
                            <th> Price</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('style')
@endsection

@section('script')
    @include('components.data-table-resources')


    <script>
        $(function() {
            let table = $('#product-internal-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.product-internal.data') }}",
                    data: function(d) {
                        d.sku = $('#filter-sku').val();
                        d.product_type = $('#filter-product-type').val();
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'sku',
                        name: 'sku'
                    },
                    {
                        data: 'product_type',
                        name: 'product_type'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Reload table on filter
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });
        });
    </script>
@endsection
