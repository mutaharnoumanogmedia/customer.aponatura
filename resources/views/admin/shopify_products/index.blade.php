@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2>Shopify Products</h2>
                <p class="text-muted">Manage your Shopify products. Use the search and filter options to find specific
                    products.</p>
            </div>
            <div>
                <a href="{{ route('admin.shopify-products.sync-form') }}" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Sync Products
                </a>
            </div>
        </div>
        <div class="bg-white p-3 rounded shadow-sm mb-4 w-100" style="overflow-x: auto;">
            <table id="products-table" class="table table-stripped table-borderless display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Shopify ID</th>
                        <th>Image</th>
                        <th>Online Store URL</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Brand</th>
                        <th>Visible</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th> <!-- no search on ID -->
                        <th><input placeholder="Search ID" /></th>
                        <th></th>
                        <th></th>
                        <th><input placeholder="Search title" /></th>
                        <th><input placeholder="e.g. 10-100" /></th>
                        <th><input placeholder="Search brand" /></th>
                        <th>
                            <select class="form-select" aria-label="Show to customer filter">
                                <option value="">All</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </th>
                        <th><!-- skip --></th>
                        <th><!-- skip --></th>
                    </tr>
                </tfoot>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('style')
@endsection

@section('script')
    @include('components.data-table-resources')
    <script>
        $(function() {
            var table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,

                // 1. AJAX setup
                ajax: {
                    url: '{{ route('admin.shopify-products.data') }}',
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },

                // 2. Define your columns, matching data keys from the JSON
                columns: [{
                        data: 'id',
                        name: 'id',
                        searchable: false
                    },
                    {
                        data: 'shopify_id',
                        name: 'shopify_id'
                    },
                    {
                        data: 'image_url',
                        name: 'image_url',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'online_store_url',
                        name: 'online_store_url',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'brand',
                        name: 'brand'
                    },
                    {
                        data: 'show_to_customer',
                        name: 'show_to_customer',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'shopify_created_at',
                        name: 'shopify_created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],

                // 3. Initial sort
                order: [
                    [0, 'desc']
                ],

                // 4. Page-length options
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],

                // 5. Add a small delay before triggering searches
                searchDelay: 500,

                // 6. Defer rendering until rows scroll into view
                deferRender: true,

                // 7. Add column-specific search from our tfoot inputs
                initComplete: function() {
                    this.api().columns().every(function() {
                        var col = this;
                        $('input,select', this.footer()).on('keyup change clear', function() {
                            if (col.search() !== this.value) {
                                col.search(this.value).draw();
                            }
                        });
                    });
                }
            });
        });
    </script>
@endsection
