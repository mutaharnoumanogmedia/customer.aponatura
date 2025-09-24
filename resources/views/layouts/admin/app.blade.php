<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Admin</title>
    <link rel="icon" href="{{ asset('images/favicon-192x192.webp') }}">

    <meta name="description" content="baaboo Customer Portal">
    <meta name="author" content="baaboo">


    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    @vite(['resources/sass/app.scss'])
    @yield('style')


    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    <button class="  sidebar-toggle" onclick="toggleSidebar()">☰</button>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <!-- Sidebar -->

    @include('layouts.admin.sidebar')


    <!-- Main Content -->
    <div class="main-content " id="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand navbar-light bg-white mb-4 rounded shadow-sm">
            <div class="container-fluid">
                {{-- <button class="btn btn-link d-lg-none rounded-circle me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button> --}}

                <!-- Search Bar -->
                <form class="d-none d-md-inline-block form-inline me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">

                </form>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications Dropdown -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 badge rounded-pill bg-danger" style="left: 30px">
                                {{ $notificationsCount }}
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow">
                            <h6 class="dropdown-header">
                                {{ __('Notifications') }}
                            </h6>
                            @if ($notificationsCount > 0)
                                @foreach ($notifications as $notification)
                                    <a class="dropdown-item" href="{{ $notification->link ?? '#' }}">
                                        <div class="d-flex justify-content-between">
                                            <div class="text-dark">
                                                {{ $notification->title }}
                                            </div>
                                            <span>{{ $notification->data }}</span>
                                            <span class="text-muted small">
                                                {{ $notification->created_at ? $notification->created_at->diffForHumans() : '' }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                                <a class="dropdown-item text-center small text-muted"
                                    href="{{ route('customer.notifications.index') }}">View All
                                    Notifications</a>
                            @else
                                <a class="dropdown-item text-center small text-muted">
                                    {{ __('No notifications found') }}
                                </a>
                            @endif
                        </div>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                            <i class="fa fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow">
                            <a class="dropdown-item" href="{{route("admin.profile")}}">
                                <i class="bi bi-person me-2"></i>
                                {{ __('Profile') }}
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-gear me-2"></i>
                                {{ __('Settings') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Page Content -->
        <div class="container-fluid">
            @include('layouts.alerts')
            @yield('content')
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        window.onload = function() {
            makeSiderbarLinkActive();
        }
        // Toggle sidebar on mobile


        // Highlight active sidebar item
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                sidebarLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
        // Toggle sidebar on button click
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const mainContent = document.querySelector('.main-content');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            mainContent.classList.toggle('full');
        }

        function makeSiderbarLinkActive() {
            // Get the current URL
            const currentUrl = window.location.href;

            // Select all sidebar items
            const sidebarItems = document.querySelectorAll('a.sidebar-link');

            // Loop through each sidebar item
            sidebarItems.forEach(item => {
                // If the href of the sidebar item matches the current URL, add the active class
                if (currentUrl.includes(item.href)) {
                    item.classList.add('active');
                }
            });
        }

        function formatNumber(num, decimals = 1) {
            const tiers = [{
                    value: 1e12,
                    suffix: 'T'
                },
                {
                    value: 1e9,
                    suffix: 'B'
                },
                {
                    value: 1e6,
                    suffix: 'M'
                },
                {
                    value: 1e3,
                    suffix: 'K'
                }
            ];

            for (const tier of tiers) {
                if (num >= tier.value) {
                    const scaled = num / tier.value;
                    // toFixed may produce trailing zeros; strip them with parseFloat
                    const formatted = parseFloat(scaled.toFixed(decimals));
                    return `${formatted}${tier.suffix}`;
                }
            }
            return num.toString();
        }
    </script>
    @yield('script')

</body>

</html>
