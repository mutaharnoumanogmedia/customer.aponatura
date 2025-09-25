    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <a href="{{ route('customer.dashboard') }}">
                    <img src="{{ $brand ? env('STORAGE_URL') . '/' . $brand->logo_path : asset('images/logo.svg') }}"
                        alt="Logo" class="img-fluid">
                </a>
            </div>
            <div class="brand-text">{{ __('Customer Portal') }}</div>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-item notranslate">
                <a href="{{ route('customer.dashboard') }}" class="sidebar-link ">
                    <i class="bi bi-speedometer2"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link collapsed" data-bs-toggle="collapse" href="#ordersSubMenu" role="button"
                    aria-expanded="false" aria-controls="ordersSubMenu">
                    <i class="bi bi-basket"></i>
                    <span>{{ __('Orders') }}</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="collapse list-unstyled ps-3" id="ordersSubMenu">
                    <li>
                        <a href="{{ route('customer.orders') }}" class="sidebar-link d-flex align-items-center">
                            <i class="bi bi-cart3 me-2"></i>
                            <span>{{ __('My Orders') }}</span>

                        </a>
                    </li>

                    <li>
                        <a href="{{ route('customer.orders.invoices') }}"
                            class="sidebar-link d-flex align-items-center">
                            <i class="bi bi-receipt me-2"></i>
                            <span>{{ __('Invoices') }}</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- <li class="sidebar-item">
                <a href="{{ Session::get('baaboo_books_magic_link') }}" target="_blank" class="sidebar-link">
                    <i class="bi bi-book"></i>
                    <span class="notranslate">{{ __('baaboo Books') }}</span>
                    <span class="badge rounded-pill bg-primary ms-1">{{ __('New') }}</span>
                </a>
            </li>
                    --}}

            {{-- <li class="sidebar-item ">
                <a href="javascript:void(0);" class="sidebar-link chat-whatsapp" onclick="openChat();addClickLog('chat_whatsapp');">
                    <i class="bi bi-whatsapp"></i>
                    <span class="notranslate">{{ __('Chat with us') }}</span>
                </a>
            </li> --}}
         
             <li class="sidebar-item ">
                <a href="javascript:void(0);" class="sidebar-link"
                    onclick="confirmSupportRedirect('{{ Session::get('support_magic_link') }}')">
                    <i class="bi bi-headset"></i>
                    <span class="notranslate">{{ __('Contact Support') }}</span>
                </a>
            </li>

            {{-- <li>
                <div class="sidebar-divider"></div>
            </li> --}}
            {{-- <li class="sidebar-item">
                <a href="{{ route('customer.profile') }}" class="sidebar-link">
                    <i class="bi bi-person"></i>
                    <span>{{ __('My Profile') }}</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('customer.settings') }}" class="sidebar-link">
                    <i class="bi bi-gear"></i>
                    <span>{{ __('Settings') }}</span>
                </a>
            </li> --}}
            <li>
                <div class="sidebar-divider"></div>
            </li>

            <li class="sidebar-item">
                <a href="#" class="sidebar-link " onclick="document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>
                        {{ __('Logout') }}
                    </span>
                </a>
            </li>

        </ul>
    </div>
