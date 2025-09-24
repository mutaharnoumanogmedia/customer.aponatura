@extends('layouts.guest')
@section('hero')
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-content animate-up">
                        <h1>{{ __('Your All-in-One Customer Portal Experience') }}</h1>
                        <p>{{ __('Manage orders, track deliveries, explore book summaries, and get premium support - all in one place. Designed for simplicity and efficiency.') }}
                        </p>
                        <a href="{{ route('customer.login.form') }}"
                            class="btn btn-hero animate__animated animate__pulse animate__infinite animate__slower">
                            {{ __('Get Started Today') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <!-- Hero Section -->


    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-title animate-down">
                <h2>{{ __('Portal Features') }}</h2>
                <p class="text-muted">{{ __('Everything you need in one powerful platform') }}</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card animate-left delay-1">
                        <div class="feature-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="feature-content text-center">
                            <h3>{{ __('Manage Orders') }}</h3>
                            <p>{{ __('View, modify, and track all your purchases in real-time with our intuitive order management system.') }}
                            </p>
                            <a href="#" class="feature-link">{{ __('Explore More') }} <i
                                    class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card animate-left delay-2">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="feature-content text-center">
                            <h3>{{ __('Track Deliveries') }}</h3>
                            <p>{{ __('Get real-time updates on your shipments with detailed tracking information and delivery notifications.') }}
                            </p>
                            <a href="#" class="feature-link">{{ __('Explore More') }} <i
                                    class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card animate-right delay-2">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="feature-content text-center">
                            <h3>{{ __('Support System') }}</h3>
                            <p>{{ __('24/7 premium support with our dedicated team ready to solve any issues you encounter.') }}
                            </p>
                            <a href="#" class="feature-link">{{ __('Explore More') }} <i
                                    class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card animate-right delay-1">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="feature-content text-center">
                            <h3>{{ __('baaboo Books') }}</h3>
                            <p>{{ __('Access exclusive book summaries crafted for busy professionals who want knowledge on the go.') }}
                            </p>
                            <a href="#" class="feature-link">{{ __('Explore More') }} <i
                                    class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Books Section -->
    <section id="books" class="books">
        <div class="container">
            <div class="section-title animate-down">
                <h2>{{ __('baaboo Book Summaries') }}</h2>
                <p class="text-muted">{{ __('Knowledge distilled for your success') }}</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate-up delay-1">
                        <div class="book-img">
                            <img src="https://images.unsplash.com/photo-1495640388908-05fa85288e61?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80"
                                alt="{{ __('Atomic Habits') }}">
                        </div>
                        <div class="book-content">
                            <div class="book-category">{{ __('Personal Development') }}</div>
                            <h3 class="book-title">{{ __('Atomic Habits') }}</h3>
                            <div class="book-author">{{ __('James Clear') }}</div>
                            <p class="book-summary">
                                {{ __('Learn how tiny changes in behavior can create remarkable results in your personal and professional life.') }}
                            </p>
                            <button class="btn btn-sm btn-outline-primary">{{ __('Read Summary') }}</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate-up delay-2">
                        <div class="book-img">
                            <img src="https://images.unsplash.com/photo-1531346878377-a5be20888e57?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80"
                                alt="{{ __('Deep Work') }}">
                        </div>
                        <div class="book-content">
                            <div class="book-category">{{ __('Productivity') }}</div>
                            <h3 class="book-title">{{ __('Deep Work') }}</h3>
                            <div class="book-author">{{ __('Cal Newport') }}</div>
                            <p class="book-summary">
                                {{ __('Master focused work in a distracted world to produce better results in less time.') }}
                            </p>
                            <button class="btn btn-sm btn-outline-primary">{{ __('Read Summary') }}</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate-up delay-3">
                        <div class="book-img">
                            <img src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80"
                                alt="{{ __('The Psychology of Money') }}">
                        </div>
                        <div class="book-content">
                            <div class="book-category">{{ __('Finance') }}</div>
                            <h3 class="book-title">{{ __('The Psychology of Money') }}</h3>
                            <div class="book-author">{{ __('Morgan Housel') }}</div>
                            <p class="book-summary">
                                {{ __('Understand the psychological aspects of money and wealth creation beyond technical knowledge.') }}
                            </p>
                            <button class="btn btn-sm btn-outline-primary">{{ __('Read Summary') }}</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="book-card animate-up delay-4">
                        <div class="book-img">
                            <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80"
                                alt="{{ __('The Lean Startup') }}">
                        </div>
                        <div class="book-content">
                            <div class="book-category">{{ __('Business') }}</div>
                            <h3 class="book-title">{{ __('The Lean Startup') }}</h3>
                            <div class="book-author">{{ __('Eric Ries') }}</div>
                            <p class="book-summary">
                                {{ __('Learn how continuous innovation can transform how companies build products and businesses.') }}
                            </p>
                            <button class="btn btn-sm btn-outline-primary">{{ __('Read Summary') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="section-header text-center mb-5">
            <h2>{{ __('Order & Delivery Lifecycle') }}</h2>
            <p>{{ __('Track your order through every step of the journey from placement to delivery') }}</p>
        </div>

        <div class="lifecycle-container">
            <div class="timeline">
                <div class="progress-bar"></div>
                <div class="steps">
                    <div class="step completed">
                        <div class="step-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">{{ __('Order Placed') }}</div>
                        </div>
                        <div class="step-description">
                            <h4>{{ __('Order Confirmed') }}</h4>
                            <p>{{ __('Your order has been successfully placed and confirmed by our system.') }}</p>
                        </div>
                    </div>

                    <div class="step completed">
                        <div class="step-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">{{ __('Order Confirmed') }}</div>
                        </div>
                        <div class="step-description">
                            <h4>{{ __('Payment Processed') }}</h4>
                            <p>{{ __('Payment has been successfully processed and verified.') }}</p>
                        </div>
                    </div>

                    <div class="step completed">
                        <div class="step-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">{{ __('Processing') }}</div>
                        </div>
                        <div class="step-description">
                            <h4>{{ __('Preparing Your Order') }}</h4>
                            <p>{{ __('Our team is preparing your items for shipment.') }}</p>
                        </div>
                    </div>

                    <div class="step active">
                        <div class="step-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">{{ __('Shipped') }}</div>
                        </div>
                        <div class="step-description">
                            <h4>{{ __('On The Way') }}</h4>
                            <p>{{ __('Your order has been shipped and is on its way to you.') }}</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">{{ __('Out for Delivery') }}</div>
                        </div>
                        <div class="step-description">
                            <h4>{{ __('Delivery in Progress') }}</h4>
                            <p>{{ __('Your package is with our delivery partner and will arrive soon.') }}</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">{{ __('Delivered') }}</div>
                        </div>
                        <div class="step-description">
                            <h4>{{ __('Delivery Completed') }}</h4>
                            <p>{{ __('Your order has been successfully delivered.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Support Section -->
    <section id="support" class="support">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="support-img animate-left">
                        <img src="{{ asset('images/img (2).webp') }}" class="img-fluid" alt="{{ __('Support Team') }}">
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="ps-lg-4 animate-right">
                        <h2 class="mb-4">{{ __('Premium Support System') }}</h2>
                        <p class="lead">
                            {{ __('Our dedicated support team is available 24/7 to ensure you get the help you need, whenever you need it.') }}
                        </p>
                        <ul class="support-features">
                            <li>{{ __('24/7 customer support via chat, email, and phone') }}</li>
                            <li>{{ __('Average response time under 15 minutes') }}</li>
                            <li>{{ __('Dedicated account managers for premium users') }}</li>
                            <li>{{ __('Comprehensive knowledge base and tutorials') }}</li>
                            <li>{{ __('Community forum for peer-to-peer assistance') }}</li>
                        </ul>
                        <button class="btn btn-login">{{ __('Contact Support') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2 class="animate-down">{{ __('Ready to Experience baaboo Portal?') }}</h2>
            <p class="animate-down delay-1">
                {{ __('Join thousands of satisfied customers who manage everything in one place') }}</p>
            <a href="{{ route('customer.login.form') }}"
                class="btn btn-cta animate-up delay-2">{{ __('Login to Your Account') }}</a>
        </div>
    </section>
@endsection
