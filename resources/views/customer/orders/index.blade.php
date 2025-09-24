@extends('layouts.customer.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="">
                        <i class="bi bi-bag-check me-2"></i>
                        {{ __('My Orders') }}

                        @isset($_GET['status'])
                            <span style="font-size: 1rem" class="">({{ __(ucfirst($_GET['status'])) }})</span>
                        @endisset
                    </h2>
                </div>

                {{-- <div>
                <button class="btn btn-sm btn-outline-secondary me-2">
                    <i class="bi bi-download me-1"></i> {{ __('Export') }}
                </button>
                <button class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('New Order') }}
                </button>
            </div> --}}
            </div>
            <div class="row ">
                <div class="text-muted mb-0 col">
                    {{ __('View and manage your order history') }}
                </div>

                @isset($_GET['status'])
                    <div class="col">
                        <a href="{{ route('customer.orders') }}">{{ __('View All') }}</a>
                    </div>
                @endisset


            </div>
        </div>

        <div class="card shadow-sm border-0 w-100">
            <div class="card-body">
                @if ($customer)
                    <div class="card mb-4 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-person-badge me-2"></i>{{ __('Customer Information') }}
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="bi bi-person me-2 text-primary"></i>
                                        <strong>{{ __('Name:') }}</strong>
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="bi bi-envelope me-2 text-primary"></i>
                                        <strong>{{ __('Email:') }}</strong>
                                        {{ $customer->email }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="bi bi-house-door me-2 text-primary"></i>
                                        <strong>{{ __('Address:') }}</strong>
                                        {{ $customer->address }} , {{ $customer->city }}
                                        {{ $customer->postal_code }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="bi bi-globe me-2 text-primary"></i>
                                        <strong>{{ __('Country:') }}</strong>
                                        {{ \App\Models\Countries::getCountryById($customer->country) }}

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>{{ __('Order History') }}
                    </h4>
                    <div class="text-muted dataTables_info">
                        {{ __('Total Orders') }}: <strong>{{ sizeof($orders) }}</strong>
                    </div>
                </div>

                @if (sizeof($orders) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover data-table">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>{{ __('Order #') }}</th>
                                    <th><i class="bi bi-geo-alt me-1"></i>{{ __('Location') }}</th>
                                    <th><i class="bi bi-box-seam me-1"></i>{{ __('Items') }}</th>
                                    <th><i class="bi bi-info-circle me-1"></i>{{ __('Status') }}</th>
                                    <th><i class="bi bi-activity me-1"></i>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order['bestellNr'] }}</strong>
                                            <div class="text-muted small">
                                                {{ \Carbon\Carbon::parse($order['datum'])->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $order['adresse'] }}</div>
                                            <div class="text-muted small">
                                                {{ $order['plz'] }}, {{ $order['ort'] }}
                                                <span class="badge  text-light">
                                                    {{ \App\Models\Countries::getCountryById($order['country']) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $order['total_items'] }} {{ __('items') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $status = \App\Enums\OrderStatus::tryFrom($order['status']);
                                            @endphp
                                            <span class="badge {{ strtolower($status->bootstrapClass()) }}">
                                                <i class="bi {{ $status->icon() }} me-1"></i>{{ __($status->label()) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary "
                                                href="{{ route('customer.orders.show', $order['bestellNr']) }}">
                                                <i class="bi bi-eye me-1"></i>{{ __('Details') }}
                                            </a>
                                            @if ($order['tracking_url'])
                                                <a href="{{ $order['tracking_url'] }}" class="btn btn-sm btn-warning"
                                                    target="_blank" title="Tracking">
                                                    <i class="bi bi-truck"></i> {{ __('Track') }}
                                                </a>
                                            @endif

                                            @if ($order['valid_for_review'])
                                                <button data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal{{ $order['bestellNr'] }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-star me-1"></i>{{ __('Review') }}
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                    <!-- Review Modal -->
                                    <div class="modal fade" id="reviewModal{{ $order['bestellNr'] }}" tabindex="-1"
                                        aria-labelledby="reviewModalLabel{{ $order['bestellNr'] }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reviewModalLabel{{ $order['bestellNr'] }}">
                                                        {{ __('Review Order #') . $order['bestellNr'] }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('customer.orders.review', $order['bestellNr']) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label class="form-label d-block">{{ __('Rating') }}</label>
                                                            <div class="star-rating" data-selected="5">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <input type="radio" class="d-none" name="rating"
                                                                        id="star{{ $i }}-{{ $order['bestellNr'] }}"
                                                                        value="{{ $i }}"
                                                                        data-order-id="{{ $order['bestellNr'] }}" />
                                                                    <label
                                                                        for="star{{ $i }}-{{ $order['bestellNr'] }}"
                                                                        style="cursor:pointer; font-size:1.5rem;"
                                                                        class="me-1"
                                                                        data-order-id="{{ $order['bestellNr'] }}">
                                                                        <i class="bi bi-star-fill text-muted"></i>
                                                                    </label>
                                                                @endfor
                                                            </div>
                                                        </div>

                                                        {{-- <div class="mb-3">
                                                            <label for="comment"
                                                                class="form-label">{{ __('Comment') }}</label>
                                                            <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
                                                        </div> --}}
                                                        <button type="button"
                                                            id="submitReviewBtn{{ $order['bestellNr'] }}"
                                                            class="btn btn-primary">{{ __('Submit Review') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-4 text-muted mb-3"></i>
                        <h4 class="mb-3">{{ __('no_orders_found') }}</h4>
                        <p class="text-muted mb-4">{{ __('You haven\'t placed any orders yet.') }}</p>
                        <a href="https://baaboo.com" target="_blank" class="btn btn-primary">
                            <i class="bi bi-bag me-1"></i>{{ __('Start Shopping') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .badge.rounded-pill {
            padding: 0.35em 0.65em;
        }

        .card {
            border-radius: 0.75rem;
        }
    </style>
@endsection
@section('script')
    @include('components.data-table-resources')
    <script>
        const customerEmail = "{{ auth()->guard('customer')->user()->email }}";
        $(document).ready(function() {
            const table = $('.data-table').DataTable({
                // DataTable options
            });

            table.on('draw', () => {
                const info = table.page.info();
                // info.start is zero-based
                const start = info.start + 1;
                const end = info.end;
                const total = info.recordsDisplay; // filtered count
                $('.dataTables_info').html(
                    `Showing ${start} to ${end} of ${total} records`
                );
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.star-rating').forEach(function(container) {
                const stars = container.querySelectorAll('label i');
                const radios = container.querySelectorAll('input[type="radio"]');
                const orderId = radios[0].getAttribute('data-order-id');

                function updateStars(selected, orderId) {
                    stars.forEach((star, idx) => {
                        if (idx < selected) {
                            star.classList.add('text-warning');
                            star.classList.remove('text-muted');
                        } else {
                            star.classList.remove('text-warning');
                            star.classList.add('text-muted');
                        }
                    });
                    console.log(`Rating updated to ${selected} stars`);


                    // Send AJAX request to save the rating


                    // Redirect to review page with selected rating

                }
                radios.forEach((radio, idx) => {
                    radio.addEventListener('change', function() {
                        updateStars(idx + 1, orderId);
                    });
                });
                document.getElementById(`submitReviewBtn${orderId}`).addEventListener('click', function() {
                    //change inner button to loading
                    this.innerHTML =
                        '<i class="bi bi-hourglass-split"></i> {{ __('Submitting...') }}';
                    this.disabled = true;
                    const selectedRadio = container.querySelector('input[type="radio"]:checked');
                    if (selectedRadio) {
                        const selectedRating = parseInt(selectedRadio.value);

                        const actionURL = "{{ url('api/customer/order-review') }}";
                        const customerId =
                            "{{ auth()->guard('customer')->user()->id }}"; // Assuming you have customer ID

                        const formData = new FormData();
                        formData.append('rating', selectedRating);
                        formData.append('order_id', orderId);
                        formData.append('customer_id', customerId);
                        formData.append('_token', '{{ csrf_token() }}');
                        fetch(actionURL, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    // console.log('Rating saved successfully');



                                    if (selectedRating <= 3) {
                                        window.open(
                                            "https://bewertung.baaboo.com/baaboo?email=" +
                                            customerEmail + "&stars=" +
                                            selectedRating,
                                            "_blank"
                                        );
                                    } else {
                                        window.open(
                                            "https://www.trustpilot.com/review/baaboo.com?email=" +
                                            customerEmail +
                                            "&stars=" +
                                            selectedRating,
                                            "_blank"
                                        );
                                    }

                                    const modal = document.getElementById(
                                        `reviewModal${orderId}`);
                                    if (modal) {
                                        const bsModal = bootstrap.Modal.getInstance(modal) ||
                                            new bootstrap
                                            .Modal(modal);
                                        bsModal.hide();
                                    }
                                    radios.forEach(radio => radio.checked = false);
                                    stars.forEach(star => {
                                        star.classList.remove('text-warning');
                                        star.classList.add('text-muted');
                                    });
                                    this.innerHTML =
                                        '<i class="bi bi-check-circle"></i> {{ __('Review Submitted') }}';
                                    this.disabled = false;


                                } else {
                                    console.error('Error saving rating:', data.message);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    } else {
                        alert("Please select a rating before submitting.");
                    }
                });
                // Initialize
                let checkedIdx = Array.from(radios).findIndex(r => r.checked);
                // updateStars(checkedIdx >= 0 ? checkedIdx + 1 : 5);
            });
        });
    </script>
@endsection
