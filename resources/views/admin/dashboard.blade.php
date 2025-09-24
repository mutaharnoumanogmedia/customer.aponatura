@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Dashboard Overview</h2>
            <div>
                <select class="form-select w-auto" id="dashboardDateRange">
                    <option value="allTime"> All Time</option>
                    <option value="today"><i class="fas fa-calendar-day me-2"></i>Today</option>
                    <option value="last7">Last 7 Days</option>
                    <option value="last30">Last 30 Days</option>
                    <option value="year">This Year</option>
                </select>
            </div>
        </div>
        <!-- Stats Cards -->
        <div class="row mb-4 g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card bg-primary bg-gradient text-white shadow-lg ">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-white-50 mb-2">Total Orders</h6>
                                <h2 class="mb-0" id="totalOrders">
                                    <div class="spinner-border spinner-border-sm text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </h2>
                            </div>
                            <div class="icon-shape rounded-circle bg-white-10 p-3">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                        {{-- <p class="mt-3 mb-0 text-white-75">
                                <span class="stat-card-percentage">+12.5%</span> from last month
                            </p> --}}
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card stat-card bg-success bg-gradient text-white shadow-lg ">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-white-50 mb-2">Total Products</h6>
                                <h2 class="mb-0">
                                    {{ $totalProducts }}
                                </h2>
                            </div>
                            <div class="icon-shape rounded-circle bg-white-10 p-3">
                                <i class="fas fa-box-open fa-2x"></i>
                            </div>
                        </div>
                        {{-- <p class="mt-3 mb-0 text-white-75">
                            <span
                                class=" stat-card-percentage {{ $productPercentageChange >= 0 ? 'text-white' : 'text-danger' }}">
                                {!! $productPercentageChange >= 0 ? '&#9650;' : '&#9660;' !!}
                                {{ abs($productPercentageChange) }}% from last month
                            </span>
                        </p> --}}
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card stat-card bg-info bg-gradient text-white shadow-lg ">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-white-50 mb-2">Customers</h6>
                                <h2 class="mb-0" id="totalCustomers">
                                    <div class="spinner-border spinner-border-sm text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </h2>
                            </div>
                            <div class="icon-shape rounded-circle bg-white-10 p-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                        {{-- <p class="mt-3 mb-0 text-white-75">
                            <span class="stat-card-percentage">+24 new</span> this month
                        </p> --}}
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div
                    class="card stat-card bg-purple bg-gradient text-white shadow-lg animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-white-50 mb-2">Active Banners</h6>
                                <h2 class="mb-0">
                                    {{ $allActiveBanners }}
                                </h2>
                            </div>
                            <div class="icon-shape rounded-circle bg-white-10 p-3">
                                <i class="fas fa-ad fa-2x"></i>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-white-75">
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Recent Orders -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">Recent Orders</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-table-tbody">
                                    <tr>
                                        <td colspan="6">
                                            <div class="d-flex justify-content-center align-items-center"
                                                style="height: 80px;">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <!-- Top Brands -->
                <div class="card shadow-sm border-0 mb-4 animate__animated animate__fadeIn animate__delay-1s">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">Top Brands</h5>
                    </div>
                    <div class="card-body">
                        <div class="brand-list">
                            @foreach ($brands as $brand)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <img src="{{ env('STORAGE_URL') . '/' . $brand->logo_path }}" class="rounded-circle"
                                            width="50" height="auto" alt="{{ $brand->name }}">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $brand->name }}</h6>
                                        <small class="text-muted">{{ $brand->products()->count() }} products</small>
                                    </div>
                                    <span
                                        class="badge bg-light bg-opacity-10 text-{{ $brand->is_active == 1 ? 'success' : 'danger' }} ms-auto">
                                        {{ $brand->is_active == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            @endforeach


                        </div>
                    </div>
                </div>

                <!-- Notices -->
                <div class="card shadow-sm border-0 animate__animated animate__fadeIn animate__delay-2s">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">Chat Logs</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-6 mb-3">
                                <div class="alert alert-primary bg-primary bg-gradient mb-0">
                                    <strong>Total Chats:</strong> <span id="totalChats">
                                        {{ $allWhatsappWidgetChatLogs->count() }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="alert alert-success  bg-success bg-gradient mb-0 text-white">
                                    <strong>Chats Today:</strong> <span id="todayChats">
                                        {{ $todayWhatsappWidgetChatLogs->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ url('admin/support-chat-logs') }}" class="btn btn-outline-primary btn-sm">
                                 View All Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <!-- Add these to your head section -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bs-purple: #6f42c1;
        }

        .bg-purple {
            background-color: var(--bs-purple) !important;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            min-height: 130px;
            display: flex;
            align-content: center;
            justify-content: center;
            /* transition: transform 0.3s ease; */
        }

        /* .stat-card:hover {
                                                                                                transform: translateY(-5px);
                                                                                            } */

        .icon-shape {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
            transform: translateX(2px);
        }

        .brand-list .d-flex {
            padding: 10px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .brand-list .d-flex:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }
    </style>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.js"></script>
    <script>
        // Example of fetching orders via AJAX
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ url('/api/get-orders/0/100') }}')
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    const totalOrdersElement = document.getElementById('totalOrders');
                    totalOrdersElement.innerHTML = formatNumber(data.total); // Update with the total count


                    appendOrdersRow(data.orders);
                })
                .catch(error => console.error('Error fetching orders:', error));


            fetch('{{ url('/api/get-customers/0/100') }}')
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const totalCustomersElement = document.getElementById('totalCustomers');
                    totalCustomersElement.innerHTML = formatNumber(data.total); // Update with the total count
                })
                .catch(error => console.error('Error fetching customers:', error));

        });



        function appendOrdersRow(orders) {
            const tbody = document.getElementById('orders-table-tbody'); // or 'orders-table-tbod' if that's your actual id
            tbody.innerHTML = ''; // Clear existing rows

            // Map status codes to labels and classes
            const statusMap = {
                1: {
                    label: 'Pending',
                    class: 'warning'
                },
                2: {
                    label: 'Shipped',
                    class: 'info'
                },
                3: {
                    label: 'Delivered',
                    class: 'success'
                },
                4: {
                    label: 'Cancelled',
                    class: 'danger'
                },
            };

            // Only take the first 10 orders
            orders.slice(0, 10).forEach(order => {
                const status = statusMap[order.status] || {
                    label: 'Unknown',
                    class: 'secondary'
                };
                const date = new Date(order.datum * 1000); // Assuming datum is a UNIX timestamp (seconds)
                const formattedDate = date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: '2-digit',
                    year: 'numeric'
                });

                const viewUrl = "{{ url('/admin/orders') }}/" + order.bestellNr;

                const row = `
            <tr>
                <td class="fw-bold">#${order.bestellNr}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <span>${order.vorname} ${order.nachname}</span>
                    </div>
                </td>
                <td>
                    €${(order.gesamtpreis / 100).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
                <td>
                    ${formattedDate}
                </td>
                <td>
                    <span class="badge bg-light bg-opacity-10 text-${status.class}">
                        ${status.label}
                    </span>
                </td>
                <td>
                    <a href="${viewUrl}" class="btn btn-sm btn-outline-secondary" target="_self">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                </td>
            </tr>
        `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }
    </script>
@endsection
