<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="img-fluid">
        </div>
        <div class="brand-text">Admin Panel <br> Customer Support</div>
    </div>

    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @role('Admin')
            <li class="sidebar-item">
                <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>


            <li class="sidebar-item">
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link">
                    <i class="bi bi-shield-lock"></i>
                    <span>Roles & Permissions</span>
                </a>
            </li>
        @endrole


        @can('brand.read')
            <li class="sidebar-item">
                <a href="{{ route('admin.brands.index') }}" class="sidebar-link">
                    <i class="bi bi-diagram-3"></i>
                    <span>Brands</span>
                </a>
            </li>
        @endcan

        @can('product.read')
            <li class="sidebar-item">
                <a class="sidebar-link collapsed" data-bs-toggle="collapse" href="#productsSubMenu" role="button"
                    aria-expanded="false" aria-controls="productsSubMenu">
                    <i class="bi bi-box-seam"></i>
                    <span>Products</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="collapse list-unstyled ps-3" id="productsSubMenu">
                    <li>
                        <a class="sidebar-link d-flex align-items-center"
                            href="{{ route('admin.shopify-products.index') }}">
                            <i class="bi bi-shop-window me-2"></i>
                            <span>Shopify Products</span>
                        </a>
                    </li>
                    <li>
                        <a class="sidebar-link d-flex align-items-center"
                            href="{{ route('admin.product-internal.index') }}">
                            <i class="bi bi-file-earmark-code me-2"></i>
                            <span>Internal Products</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('banner.read')
            <li class="sidebar-item">
                <a href="{{ route('admin.promotional-banners.index') }}" class="sidebar-link">
                    <i class="bi bi-images"></i>
                    <span>Promotional Banners</span>
                </a>
            </li>
        @endcan

        @can('order.read')
            <li class="sidebar-item">
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link">
                    <i class="bi bi-bag-check"></i>
                    <span>Orders</span>
                </a>
            </li>
        @endcan

        @can('invoices.read')
            <li class="sidebar-item">
                <a href="{{ '#' }}" class="sidebar-link">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>Invoices</span>
                </a>
            </li>
        @endcan

        @can('customer.read')
            <li class="sidebar-item">
                <a href="{{ route('admin.customers.index') }}" class="sidebar-link">
                    <i class="bi bi-people"></i>
                    <span>Customers</span>
                </a>
            </li>
        @endcan

        @can('report.view')
            <li class="sidebar-item">
                <a href="{{ '#' }}" class="sidebar-link">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Reports</span>
                </a>
            </li>
        @endcan


        @role('Admin')
            {{-- <li class="sidebar-item">
                <a class="sidebar-link collapsed" data-bs-toggle="collapse" href="#chatSubMenu" role="button"
                    aria-expanded="false" aria-controls="chatSubMenu">
                    <i class="bi bi-chat-dots"></i>
                    <span>Chat Logs</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="collapse list-unstyled ps-3" id="chatSubMenu">
                    <li>
                        <a href="{{ route('admin.support-chat-logs.index') }}" class="sidebar-link">
                            <i class="bi bi-chat-left-text"></i>
                            <span>
                                Widget Support Chat
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.whatsapp-support-chat-logs.index') }}" class="sidebar-link">
                            <i class="bi bi-chat-left-text"></i>
                            <span>
                                Whatsapp Support Chat
                            </span>
                        </a>
                    </li>


                </ul>
            </li> --}}
            <li>
                <a href="{{ route('admin.click-logs.index') }}" class="sidebar-link">
                    <i class="bi bi-mouse"></i>
                    <span>
                        Click Logs
                    </span>
                </a>
            </li>
        @endrole

        <li>
            <div class="sidebar-divider"></div>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link">
                <i class="bi bi-question-circle"></i>
                <span>Help Center</span>
            </a>
        </li>

        <li>
            <div class="sidebar-divider"></div>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link logout"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>

        </li>
    </ul>


</div>
