<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Manage Orders, Track Deliveries & More</title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="baaboo Customer Portal - All-in-One Customer Experience">
    <meta property="og:description"
        content="Manage orders, track deliveries, access book summaries, and get premium support in one place">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://customerportal.baaboo.com">
    <meta property="og:image" content="{{ asset('images/logo.webp') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="baaboo Customer Portal">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="baaboo Customer Portal - All-in-One Customer Experience">
    <meta name="twitter:description"
        content="Manage orders, track deliveries, access book summaries, and get premium support in one place">
    <meta name="twitter:image" content="{{ asset('images/logo.webp') }}">
    <meta name="twitter:image:alt" content="baaboo Customer Portal">
    <meta name="twitter:site" content="@baaboo_official">

    <!-- Additional SEO Meta Tags -->
    <meta name="description"
        content="baaboo Customer Portal: Manage orders, track deliveries, access exclusive book summaries, and get premium support. All in one place.">
    <meta name="keywords"
        content="baaboo, customer portal, order management, delivery tracking, book summaries, support">
    <meta name="author" content="baaboo">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/addon.css') }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    @include('pwa.metatags')

    <style>
        .main-content {
            padding: 10rem 0 5rem 0;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{-- <span>baa</span>boo --}}
                <img src="{{ asset('images/logo.webp') }}" class="logo" alt="baaboo Logo" style="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-3 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#books">baaboo Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#support">Support</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-4 mb-3 mb-lg-0">
                        <a href="{{ env('APP_LOGIN_URL') ? env('APP_LOGIN_URL') . '?key=customer' : route('customer.login.form') }}"
                            class="btn btn-login  animate__animated animate__pulse animate__infinite animate__slower">
                            {{ __('Login to portal') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        @include('components.translation-dropdown')
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    @yield('hero')
    <main class="container-fluid main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')
    {{-- @vite(['resources/js/app.js']) --}}


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize animations on scroll
        document.addEventListener('DOMContentLoaded', function() {
            // Add scroll event listener
            window.addEventListener('scroll', animateElements);

            // Initial call to animate elements in viewport
            animateElements();

            function animateElements() {
                const elements = document.querySelectorAll(
                    '.animate-up, .animate-down, .animate-left, .animate-right');

                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect();
                    const windowHeight = window.innerHeight;

                    if (elementPosition.top < windowHeight - 50) {
                        element.style.visibility = 'visible';
                        element.style.animationPlayState = 'running';
                    }
                });
            }
        });
    </script>
    @include('pwa.scripts')
</body>

</html>
