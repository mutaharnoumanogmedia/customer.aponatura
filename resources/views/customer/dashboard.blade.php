    @extends('layouts.customer.app')

    @section('content')
        <?php
        // $affiliate_register_link = "https://smart-chance.net/customer?customer_email=" .auth()->guard('customer')->user()->email . "&customer_first_name=" . auth()->guard('customer')->user()->first_name . "&customer_last_name=" . auth()->guard('customer')->user()->last_name;
        
        $affiliate_register_link = 'http://bbinfo24.com/';
        ?>
        <div class="container">

            <div class="dashboard-header d-sm-flex align-items-center justify-content-between">
                <h1 class="dashboard-title notranslate">
                    {{ __('Welcome') }} {{ ucwords(auth()->guard('customer')->user()->name) }}!
                </h1>
               

                <div class="header-actions">
                   
                    <a href="{{ $brand->website_url }}" class="btn btn-sm btn-primary" target="_blank"
                        onclick="addClickLog('baaboo.com_new_order');">
                        <i class="bi bi-plus-circle me-1"></i> {{ __('New Order') }}
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row align-items-stretch mb-4">
                <div class="col-xl-6 col-md-6 mb-4 d-flex">
                    <a href="{{ route('customer.orders', ['status' => 'active']) }}"
                        class="text-decoration-none text-dark flex-fill">

                        <div class="card dashboard-stat-card flex-fill">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col me-2">
                                        <div class="text-stat  ">
                                            {{ __('Active Orders') }}</div>
                                        <div class="figure-stat">{{ sizeof($activeOrders) }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-cart3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-6 col-md-6 mb-4 d-flex">
                    <a href="{{ route('customer.orders', ['status' => 'completed']) }}"
                        class="text-decoration-none text-dark flex-fill">
                        <div class="card dashboard-stat-card flex-fill">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col me-2">
                                        <div class="text-stat  dashboard-screen-text">
                                            {{ __('Completed Orders') }}</div>
                                        <div class="figure-stat">
                                            {{ sizeof($completedOrders) }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- <div class="col-xl-4 col-md-6 mb-4 d-flex">
                    <a href="{{ $affiliate_register_link }}" target="_blank"
                        class="text-decoration-none text-dark flex-fill"
                        onclick="addClickLog('partner.baaboo.com_affiliate_link');">

                        <div class="card dashboard-stat-card flex-fill affiliate-card" style="">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col me-2">
                                        <div class="   dashboard-screen-text text-white" style="">
                                            <div style='font-size: 1.2rem'>
                                                {{ __('Earn with baaboo') }}</div>
                                            <div style="font-size: 0.8rem">
                                                {!! __('dashboard.join-affiliate') !!} <br>

                                            </div>
                                            <div class="mt-2">
                                                {{ __('Click Here') }}
                                            </div>
                                            <div class="figure-stat">

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                    </a>
                </div> --}}
            </div>

            <!-- Content Row -->
            <div class="row">
                <div class="col-lg-9 mb-4">
                    @if (sizeof($promotional_banners) > 0)
                        <section class="w-100" id="promotionalCarousel">
                            <div id="simpleCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($promotional_banners as $banner)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                            <a href="https://baaboo.com/" target="_blank">
                                                <img src="{{ env('STORAGE_URL') . '/' . $banner->banner_file }}"
                                                    class="d-block w-100" alt="Slide {{ $loop->iteration }}">
                                            </a>
                                        </div>
                                    @endforeach

                                </div>

                                <!-- Controls -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#simpleCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                    <span class="visually-hidden">
                                        {{ __('Previous') }}
                                    </span>
                                </button>

                                <button class="carousel-control-next" type="button" data-bs-target="#simpleCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                    <span class="visually-hidden">
                                        {{ __('Next') }}
                                    </span>
                                </button>

                                <!-- Indicators -->
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#simpleCarousel" data-bs-slide-to="0"
                                        class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#simpleCarousel" data-bs-slide-to="1"
                                        aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#simpleCarousel" data-bs-slide-to="2"
                                        aria-label="Slide 3"></button>
                                </div>
                            </div>

                        </section>
                    @endif
                    @if (sizeof($orders) > 0)
                        <div class="card">
                            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold">{{ __('Recent Orders') }}</h6>
                                <a href="{{ route('customer.orders') }}"
                                    class="btn btn-sm btn-link">{{ __('View All') }}</a>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @foreach (array_slice($orders, 0, 5) as $order)
                                        <a href="{{ route('customer.orders.show', $order['bestellNr']) }}"
                                            class="list-group-item list-group-item-action order-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ __('Order #') . $order['bestellNr'] }}</h5>
                                                @php
                                                    $status = \App\Enums\OrderStatus::tryFrom($order['status']);
                                                @endphp

                                                <div class="badge {{ $status->bootstrapClass() }}">
                                                    {{ __($status->label()) }}</div>
                                            </div>

                                            <div class="d-flex justify-content-between mt-2">
                                                <div>
                                                    <small class="text-muted">
                                                        {{ __('Placed on:') }}
                                                        {{ \Carbon\Carbon::createFromTimestamp($order['datum'])->format('M d, Y') }}
                                                    </small><br>
                                                    <small class="text-muted">
                                                        {{ $order['adresse'] }},
                                                        {{ $order['plz'] }} {{ $order['ort'] }},
                                                        {{ \App\Models\Countries::getCountryById($order['country']) }}
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <strong>
                                                        €{{ number_format($order['gesamtpreis'] / 100, 2, ',', '.') }}
                                                    </strong><br>
                                                    <small class="text-muted">
                                                        {{ $order['total_items'] ?? '0' }} items
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="text-muted">
                                    {{ __('No recent orders found.') }}
                                </div>

                                <center>
                                    <a href="{{ $brand->website_url }}" class="btn btn-primary mt-3" target="_blank">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        {{ __('Place a new order') }}
                                    </a>
                                </center>

                            </div>
                        </div>
                    @endif
                </div>

                <!-- Profile Summary -->
                <div class="col-lg-3 mb-4">
                    <section id="product-slider" class="w-100 bg-light  rounded p-3">
                        <div class="  " style="background: transparent;">
                            <div class="  py-3">
                                <h5 class="text-center ">
                                    {{ __('Recommended Products') }}
                                </h5>
                            </div>
                            <div class="card-body p-0" style="background: transparent;">
                                <div class="vertical-slider w-100">
                                    <div class="slide w-100" style="cursor: pointer">
                                        <a href="https://aponatura.de/products/augen-plus-kapseln?variant=39643752232930">
                                            <div class="product-card row g-0 ">
                                                <div class="col-md-12 position-relative">
                                                    <img src="https://aponatura.de/cdn/shop/files/Augen_Kapseln_ApoNatura_1280x.jpg?v=1742971856"
                                                        alt="Augen+ Kapseln im Maxi-Pack (90 St.)" class="product-img">
                                                </div>

                                                <div class="col-md-12 p-3">
                                                    {{-- Title --}}
                                                    <div class="product-title mb-1">
                                                        Augen+ Kapseln im Maxi-Pack (90 St.)
                                                    </div>

                                                    {{-- Price --}}
                                                    <div class="price-section">
                                                        <span class="original-price me-2">
                                                            {{ '€' }}
                                                            {{ number_format(29.9, 2, ',', '.') }}
                                                        </span>
                                                        {{-- if you have a discounted price, swap this in --}}
                                                        {{-- <span class="discounted-price">€149.99</span> --}}
                                                    </div>

                                                    {{-- Example rating & button (static here—you could also pull rating from data) --}}
                                                    <div class="d-flex align-items-center w-100 position-relative">
                                                        <div class="rating me-2 flex-grow-1">
                                                            <span class="text-warning">★★★★★</span>
                                                        </div>
                                                        <a href="https://aponatura.de/products/augen-plus-kapseln?variant=39643752232930"
                                                            target="_blank"
                                                            class="btn text-white btn-add-to-card-link btn-sm flex-auto">
                                                            <i class="bi bi-cart-fill"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="slide w-100">
                                        <a class="text-decoration-none" target="_blank"
                                            href="https://aponatura.de/products/agila-vital-mikronahrstoffkonzentrat">
                                            <div class="product-card row g-0 ">
                                                <div class="col-md-12 position-relative">
                                                    <img src=https://aponatura.de/cdn/shop/files/AGILA_VITAL_ApoNatura_1280x.jpg?v=1742901906"
                                                        alt="AGILA VITAL Mikronährstoffkonzentrat (500 ml)"
                                                        class="product-img">
                                                </div>

                                                <div class="col-md-12 p-3">
                                                    {{-- Title --}}
                                                    <div class="product-title mb-1">
                                                        AGILA VITAL Mikronährstoffkonzentrat (500 ml)
                                                    </div>

                                                    {{-- Price --}}
                                                    <div class="price-section">
                                                        <span class="original-price me-2">
                                                            {{ '€' }}
                                                            {{ number_format(29.9, 2, ',', '.') }}
                                                        </span>
                                                        {{-- if you have a discounted price, swap this in --}}
                                                        {{-- <span class="discounted-price">€149.99</span> --}}
                                                    </div>

                                                    {{-- Example rating & button (static here—you could also pull rating from data) --}}
                                                    <div class="d-flex align-items-center w-100 position-relative">
                                                        <div class="rating me-2 flex-grow-1">
                                                            <span class="text-warning">★★★★★</span>
                                                        </div>
                                                        <a href="https://aponatura.de/products/agila-vital-mikronahrstoffkonzentrat"
                                                            target="_blank"
                                                            class="btn text-white btn-add-to-card-link btn-sm flex-auto">
                                                            <i class="bi bi-cart-fill"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="slide w-100" style="cursor: pointer">
                                        <a class="text-decoration-none" target="_blank"
                                            href="https://aponatura.de/products/green-nutrition-bittertropfen">
                                            <div class="product-card row g-0 ">
                                                <div class="col-md-12 position-relative">
                                                    <img src="https://aponatura.de/cdn/shop/files/Green-Nutrition-Bitter-tropfen-ApoNatura_1280x.jpg?v=1752217747"
                                                        alt="GREEN NUTRITION Bittertropfen (100 ml)" class="product-img">
                                                </div>

                                                <div class="col-md-12 p-3">
                                                    {{-- Title --}}
                                                    <div class="product-title mb-1">
                                                        GREEN NUTRITION Bittertropfen (100 ml)
                                                    </div>

                                                    {{-- Price --}}
                                                    <div class="price-section">
                                                        <span class="original-price me-2">
                                                            {{ '€' }}
                                                            {{ number_format(18.85, 2, ',', '.') }}
                                                        </span>
                                                        {{-- if you have a discounted price, swap this in --}}
                                                        {{-- <span class="discounted-price">€149.99</span> --}}
                                                    </div>

                                                    {{-- Example rating & button (static here—you could also pull rating from data) --}}
                                                    <div class="d-flex align-items-center w-100 position-relative">
                                                        <div class="rating me-2 flex-grow-1">
                                                            <span class="text-warning">★★★★★</span>
                                                        </div>
                                                        <a href="https://aponatura.de/products/green-nutrition-bittertropfen"
                                                            target="_blank"
                                                            class="btn text-white btn-add-to-card-link btn-sm flex-auto">
                                                            <i class="bi bi-cart-fill"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>




                                    @foreach ($products as $product)
                                        <div class="slide w-100" style="cursor: pointer"
                                            onclick="window.open('{{ $product->online_store_url }}', '_blank', 'noopener,noreferrer')">

                                            <div class="product-card row g-0 ">
                                                <div class="col-md-12 position-relative">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->title }}"
                                                        class="product-img">
                                                </div>

                                                <div class="col-md-12 p-3">
                                                    {{-- Title --}}
                                                    <div class="product-title mb-1">
                                                        {{ $product->title }}
                                                    </div>

                                                    {{-- Price --}}
                                                    <div class="price-section">
                                                        <span class="original-price me-2">
                                                            {{ $product->currency }}
                                                            {{ number_format($product->price, 2, ',', '.') }}
                                                        </span>
                                                        {{-- if you have a discounted price, swap this in --}}
                                                        {{-- <span class="discounted-price">€149.99</span> --}}
                                                    </div>

                                                    {{-- Example rating & button (static here—you could also pull rating from data) --}}
                                                    <div class="d-flex align-items-center w-100 position-relative">
                                                        <div class="rating me-2 flex-grow-1">
                                                            <span class="text-warning">★★★★★</span>
                                                        </div>
                                                        <a href="{{ $product->online_store_url }}" target="_blank"
                                                            class="btn text-white btn-add-to-card-link btn-sm flex-auto">
                                                            <i class="bi bi-cart-fill"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- <div class="card profile-card mt-5">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold">
                            {{ __('My Profile') }}
                        </h6>
                    </div>
                    <div class="card-body">

                        <h5 class="mb-3">
                            <i class="bi bi-person-circle"></i>
                            {{ auth()->guard('customer')->user()->name }}
                        </h5>
                        <div class="d-flex justify-content-LEFT mb-3">
                            <div class="pe-3 text-center">
                                <div class="h5 mb-0">12</div>
                                <small class="text-muted">Orders</small>
                            </div>
                            <div class="px-3 text-center">
                                <div class="h5 mb-0">2</div>
                                <small class="text-muted">Pending</small>
                            </div>
                            <div class="px-3 text-center">
                                <div class="h5 mb-0">10</div>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>
                            {{ __('Edit Profile') }}
                        </button>
                    </div>
                </div> --}}

                    <!-- Quick Links -->
                    {{-- <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">
                                {{ __('Quick Links') }}
                            </h6>
                        </div>
                        <div class="card-body px-2">
                            
                                <button class="btn btn-outline-secondary text-start mt-2 mb-1">
                                    <i class="bi bi-truck me-2"></i> {{ __('Track Order') }}
                                </button>
                                <button class="btn btn-outline-secondary text-start mb-1">
                                    <i class="bi bi-arrow-return-left me-2"></i> {{ __('Request Return') }}
                                </button>
                                <button class="btn btn-outline-secondary text-start mb-1">
                                    <i class="bi bi-chat-left-text me-2 "></i> {{ __('Contact Support') }}
                                </button>
                                <a href="{{ Session::get('support_magic_link') }}" target="_blank"
                                    class="btn btn-outline-secondary text-start mb-1">
                                    <i class="bi bi-question-circle me-2"></i> {{ __('Help Center') }}
                                </a>
                            
                        </div>
                    </div> --}}


                </div>
            </div>
        </div>

    @endsection

    @section('script')
        <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

        <script>
            $(function() {
                const $sliders = $('.vertical-slider').slick({
                    vertical: true,
                    verticalSwiping: true,
                    arrows: true,
                    dots: false,
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    pauseOnHover: true, // ⟵ pauses when hovered
                    pauseOnFocus: false, // optional: don’t pause on focus
                    speed: 300, // slide animation speed (used by throttle below)
                    responsive: [{
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });

                // Mouse wheel to navigate when hovering the slider
                $sliders.each(function() {
                    const el = this;
                    let locked = false; // simple throttle so one tick == one slide

                    el.addEventListener('wheel', function(e) {
                        // Only act if the pointer is over the slider
                        // and prevent the page from scrolling.
                        e.preventDefault();
                        e.stopPropagation();

                        if (locked) return;
                        locked = true;

                        if (e.deltaY < 0) {
                            $(el).slick('slickPrev');
                        } else {
                            $(el).slick('slickNext');
                        }

                        // unlock after the slide animation finishes
                        setTimeout(() => (locked = false), 350);
                    }, {
                        passive: false
                    });
                });

                // (Optional) If you want *strict* pause on hover regardless of Slick option
                $sliders.on('mouseenter', function() {
                        $(this).slick('slickPause');
                    })
                    .on('mouseleave', function() {
                        $(this).slick('slickPlay');
                    });
            });
        </script>
    @endsection

    @section('style')
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
        {{-- <style>
            #product-slider {}

            #product-slider .vertical-slider {
                width: 200px;
                margin: 20px auto;

            }

            #product-slider .vertical-slider .product-img {
                width: 100%;
                height: auto;
                max-height: 200px;
                object-fit: cover;
                /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); */
                border-radius: 20px 20px 0 0;
            }

            #product-slider .slide {
                background: transparent;
                border-radius: 8px;
                padding: 5px;
                margin: 5px auto;

                min-height: 100px;
                display: inline;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                color: #333;
            }

            #product-slider .slide .product-name {
                font-size: 12px;
                color: #333;
                font-weight: 600;
                text-align: center;
                margin-top: 5px;
                width: 100%;
                display: block;
            }

            /* Vertical slider arrows */
            #product-slider .slick-prev,
            #product-slider .slick-next {
                left: 50%;
                transform: translateX(-50%) rotate(90deg);
                width: 30px;
                height: 30px;
            }

            #product-slider .slick-prev {
                top: -40px;
            }

            #product-slider .slick-next {
                top: auto;
                bottom: -40px;
            }

            #product-slider .slick-prev:before,
            #product-slider .slick-next:before {
                font-size: 30px;
                color: #333;
            }





            .product-card {
                background: #f9f9f9;
                margin: 0;
                border: 1px solid #eee;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                border-radius: 20px;
                padding: 1px
            }

            .product-title {
                font-size: 13px;
                font-weight: 600;
                color: #333;
            }

            .product-img {
                height: 100%;
                object-fit: cover;
            }

            .original-price {
                text-decoration: none;
                color: #6c757d;
                font-size: 1rem;
            }

            .discounted-price {
                color: #dc3545;
                font-weight: bold;
                font-size: 1rem;
            }

            .save-percentage {
                font-size: 0.8rem;
                padding: 2px 5px;
                border-radius: 5px;
            }

            .badge-sale {
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 0.8rem;
            }
        </style> --}}
        <style>
            #product-slider .vertical-slider {
                width: 200px;
                margin: 20px auto;
                overflow: hidden;

            }

            .text-decoration-none {
                text-decoration: none !important;
            }
        </style>
    @endsection
