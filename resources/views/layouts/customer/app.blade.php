<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{-- {{ env('APP_ENV') == 'local' ? 'Local - ' : '' }} --}}
        {{ __(env('APP_NAME')) }}</title>
    <link rel="icon" href="{{ $brand ? env('STORAGE_URL') . '/' . $brand->favicon_path : asset('images/logo.svg') }}">

    <meta name="description" content="baaboo 
    ">
    <meta name="author" content="baaboo">


    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Custom CSS -->
    {{-- @vite(['resources/sass/app.scss']) --}}
    <link rel="stylesheet" href="{{ asset('css/style.css' . '?' . time()) }}">
    <link rel="stylesheet" href="{{ asset('css/addon.css' . '?' . time()) }}">

    @if (isset($brand))
        <style>
            :root {
                --primary-color: {{ $brand->primary_color }};
                --secondary-color: {{ $brand->secondary_color }};
            }
        </style>
    @endif

    @yield('style')

    @include('pwa.metatags')
    @laravelPWA


\

</head>

<body>
    <button class="  sidebar-toggle" onclick="toggleSidebar()">☰</button>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <!-- Sidebar -->

    @include('layouts.customer.sidebar')


    <!-- Main Content -->
    <div class="main-content " id="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand navbar-light bg-white mb-4 rounded shadow-sm mt-lg-2 mt-0">
            <div class="container-fluid">
                {{-- <button class="btn btn-link d-lg-none rounded-circle me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button> --}}

                <!-- Search Bar -->
                {{-- <form class="d-none d-md-inline-block form-inline me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="{{ __('Search orders, invoices') }}...">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form> --}}

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown me-3 my-auto">
                        @include('components.translation-dropdown')
                    </li>


                    <!-- Notifications Dropdown -->
                    <li class="nav-item dropdown no-arrow mx-4">
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
                        <a class="nav-link dropdown-toggle user-dropdown-toggle" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <!-- User Avatar -->
                                <div class="user-avatar me-2">
                                    @if (auth()->guard('customer')->user()->avatar)
                                        <img src="{{ auth()->guard('customer')->user()->avatar }}"
                                            alt="{{ auth()->guard('customer')->user()->first_name }}"
                                            class="rounded-circle avatar-img">
                                    @else
                                        <div
                                            class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person-fill text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <!-- User Info (hidden on small screens) -->
                                <div class="user-info d-none d-lg-block">
                                    <div class="user-name">{{ auth()->guard('customer')->user()->name ?? 'User' }}
                                    </div>
                                    <div class="user-role">{{ __('Customer') }}</div>
                                </div>
                                <!-- Dropdown Arrow -->
                                <i class="bi bi-chevron-down ms-2 dropdown-arrow"></i>
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end user-dropdown-menu shadow-lg">
                            <!-- User Header in Dropdown -->
                            <div class="dropdown-header user-header">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-large me-3">
                                        @if (auth()->guard('customer')->user()->avatar && !empty(auth()->guard('customer')->user()->avatar))
                                            <img src="{{ auth()->guard('customer')->user()->avatar }}"
                                                alt="{{ auth()->guard('customer')->user()->first_name }}"
                                                class="rounded-circle avatar-img-large">
                                        @else
                                            <div
                                                class="avatar-placeholder-large rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person-fill text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name-large">
                                            {{ auth()->guard('customer')->user()->name ?? 'User Name' }}</div>
                                        <div class="user-email">
                                            {{ auth()->guard('customer')->user()->email ?? 'user@example.com' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <!-- Menu Items -->
                            <a class="dropdown-item dropdown-item-enhanced" href="{{ route('customer.profile') }}">
                                <div class="dropdown-item-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="dropdown-item-content">
                                    <div class="item-title">{{ __('Profile') }}</div>
                                    <div class="item-subtitle">{{ __('Manage your account') }}</div>
                                </div>
                            </a>

                            <a class="dropdown-item dropdown-item-enhanced" href="{{ route('customer.settings') }}">
                                <div class="dropdown-item-icon">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <div class="dropdown-item-content">
                                    <div class="item-title">{{ __('Settings') }}</div>
                                    <div class="item-subtitle">{{ __('Preferences & privacy') }}</div>
                                </div>
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item dropdown-item-enhanced logout-item" href="#"
                                onclick="document.getElementById('logout-form').submit();">
                                <div class="dropdown-item-icon">
                                    <i class="bi bi-box-arrow-right"></i>
                                </div>
                                <div class="dropdown-item-content">
                                    <div class="item-title">{{ __('Logout') }}</div>
                                    <div class="item-subtitle">{{ __('Sign out of your account') }}</div>
                                </div>
                            </a>

                            <form id="logout-form" action="{{ route('customer.logout') }}" method="POST"
                                class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Page Content -->
        <div class="container-fluid h-100" style="min-height: calc(100vh - 200px);">
            @include('layouts.alerts')
            @yield('content')
            <br><br><br><br>
        </div>


        {{-- @include('components.chat-widget') --}}
        @include('components.footer')
    </div>

    <div id="google_translate_element"></div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // window.onload = function() {
        //     makeSiderbarLinkActive();
        // }
        // Toggle sidebar on mobile


        // Highlight active sidebar item
        // const sidebarLinks = document.querySelectorAll('.sidebar-link');
        // sidebarLinks.forEach(link => {
        //     link.addEventListener('click', function() {
        //         sidebarLinks.forEach(l => l.classList.remove('active'));
        //         this.classList.add('active');
        //     });
        // });
        // Toggle sidebar on button click
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const mainContent = document.querySelector('.main-content');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            mainContent.classList.toggle('full');
        }

        // function makeSiderbarLinkActive() {
        //     // Get the current URL
        //     const currentUrl = window.location.href;

        //     // Select all sidebar items
        //     const sidebarItems = document.querySelectorAll('a.sidebar-link');

        //     // Loop through each sidebar item
        //     sidebarItems.forEach(item => {
        //         // If the href of the sidebar item matches the current URL, add the active class
        //         if (currentUrl.includes(item.href)) {
        //             item.classList.add('active');
        //         }
        //     });
        // }


        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.href;
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            sidebarLinks.forEach(link => {
                const linkHref = link.href;

                if (currentUrl === linkHref || currentUrl.startsWith(linkHref)) {
                    // Add active class to the matched link
                    link.classList.add('active');

                    // Check if this link is inside a collapsed menu
                    const parentCollapse = link.closest('.collapse');
                    if (parentCollapse) {
                        parentCollapse.classList.add('show'); // Open the parent collapse
                        const toggleLink = document.querySelector(`[href="#${parentCollapse.id}"]`);
                        if (toggleLink) {
                            toggleLink.setAttribute('aria-expanded', 'true');
                            toggleLink.classList.remove('collapsed');
                        }
                    }
                }
            });
        });
    </script>
    {{-- <script src="{{ asset('js/google-translate.js') }}"></script> --}}
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @yield('script')


    <script>
        function confirmSupportRedirect(url) {
            swal({
                title: "{{ __('Thanks for your patience!') }}",
                text: "{{ __('thanks.patience.message') }}",
                // icon: "info",
                buttons: true,
                confirmButtonText: "Open Messaging Cente",
                dangerMode: true,
            }).then((willRedirect) => {
                if (willRedirect) {
                    addClickLog('support_redirect');
                    window.location.href = url;
                }
            });
        }


        function addClickLog(action) {
            $.ajax({
                url: "{{ route('customer.click-log.store') }}",
                method: "POST",
                data: {
                    event_name: action,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log('Click log >>:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error adding click log:', error);
                }
            });
        }
    </script>


</body>

</html>
