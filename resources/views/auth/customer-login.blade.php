   <!DOCTYPE html>
   <html lang="en">

   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel="icon" href="{{ env('STORAGE_URL') . '/' . $brand->favicon_path }}">

       <title>Login | {{ $brand->name ?? 'baaboo' }}</title>
       <!-- Bootstrap 5 CSS -->
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
       <!-- Bootstrap Icons -->
       <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
       <!-- Custom CSS -->
       <link rel="stylesheet" href="{{ asset('css/style.css') }}">

       @if (isset($brand))
           <style>
               :root {
                   --primary-color: {{ $brand->primary_color }};
                   --secondary-color: {{ $brand->secondary_color }};
               }
           </style>
       @endif
       @include('pwa.metatags')
       @laravelPWA

   </head>

   <body>
       <div class="login-container">
           <div class="row g-0">
               <!-- Left Side - Image -->
               <div class="col-lg-7 d-none d-lg-block">
                   <div class="login-image">
                       <h1>{{__("login.welcome-back-heading")}}</h1>
                       <p>
                           {{ __('login.welcome-back-subheading') }}
                       </p>
                       <div class="mt-4">
                           <div class="d-flex align-items-center mb-3">
                               <i class="bi bi-check-circle-fill me-2" style="color: var(--primary-color);"></i>
                               <span>{{ __('login.feature1') }}</span>
                           </div>
                           <div class="d-flex align-items-center mb-3">
                               <i class="bi bi-check-circle-fill me-2" style="color: var(--primary-color);"></i>
                               <span>{{ __('login.feature2') }}</span>
                           </div>
                           <div class="d-flex align-items-center">
                               <i class="bi bi-check-circle-fill me-2" style="color: var(--primary-color);"></i>
                               <span>{{ __('login.feature3') }}</span>
                           </div>
                       </div>
                   </div>
               </div>

               <!-- Right Side - Form -->
               <div class="col-lg-5">
                   <div class="login-form-container">
                       <div class="login-form">
                           <div class="logo">

                               <img src="{{ env('STORAGE_URL') . '/' . $brand->logo_path }}" alt="Company Logo">
                           </div>

                           <h2 class="mb-4">{{ __('login.signin-line') }}</h2>
                           @include('layouts.alerts')
                           <form action="{{ route('magic-link.send') }}" method="post">

                               <div class="mb-3">
                                   <label for="email" class="form-label">
                                       {{ __('Email Address') }}
                                   </label>
                                   <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email') }}" placeholder="{{ __('login.email-placeholder') }}">
                                   @error('email')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                               <div class="my-2 w-100">
                                   <button class="btn w-100 btn-primary" id="send-magic-link-btn">
                                       <span id="btn-text">
                                           {{ __('login.send-magic-link') }}
                                       </span>
                                       <div id="btn-loader"
                                           class="spinner-border spinner-border-sm text-white ms-2 d-none"
                                           role="status"></div>
                                   </button>
                               </div>
                               <div class="w-100">
                                   @include('components.translation-dropdown')
                               </div>


                           </form>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       <!-- Bootstrap 5 JS Bundle with Popper -->
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


       <script>
           document.addEventListener('DOMContentLoaded', function() {
               const form = document.querySelector('form');
               const btn = document.getElementById('send-magic-link-btn');
               const btnText = document.getElementById('btn-text');
               const btnLoader = document.getElementById('btn-loader');

               form.addEventListener('submit', function(event) {
                   // Disable the button and show loader
                   btn.disabled = true;
                   btnText.textContent = 'Sending...';
                   btnLoader.classList.remove('d-none');
               });
           });
       </script>
   </body>

   </html>
