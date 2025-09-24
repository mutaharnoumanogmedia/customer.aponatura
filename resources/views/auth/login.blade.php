<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Admin Panel | {{ env('APP_NAME') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }

        .login-container {
            height: 100vh;
        }

        .brand-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }

        .brand-content {
            max-width: 500px;
            margin: 0 auto;
        }

        .brand-logo {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .brand-description {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .login-section {
            background-color: var(--light-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }

        .login-card {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 2rem;
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .btn-login {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.875rem;
        }

        .forgot-password:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

       

        @media (max-width: 992px) {
            .brand-section {
                display: none;
            }

            .login-section {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid login-container">
        <div class="row h-100">
            <!-- Brand/Visual Section (Left Side) -->
            <div class="col-lg-6 d-none d-lg-block brand-section p-0">
                <div class="row text-center brand-content d-flex align-content-center h-100">
                    <div class="col-12">

                        <h1 class="brand-title w-100">Admin Panel | {{ env('APP_NAME') }}</h1>
                    </div>
                    <div class="col-12">
                        <p class="brand-description">
                            Welcome back! Please login to access your customer dashboard.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Login Form Section (Right Side) -->
            <div class="col-lg-6 login-section p-0">
                <div class="login-card">
                    <h2 class="login-title"><i class="fas fa-lock me-2"></i>Admin Login</h2>

                    <!-- Laravel's built-in authentication error handling -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>

                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a class="forgot-password" href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
