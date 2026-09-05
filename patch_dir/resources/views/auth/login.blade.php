<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <!-- Auto refresh page after session expires (120 minutes = 7200 seconds) -->
    <meta http-equiv="refresh" content="7200">
    <title>{{ config('app.name', 'Z-pos') }} - {{ __('Login - Z-pos Enterprise') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            overscroll-behavior: none; /* Prevents mobile pull-to-refresh jiggle */
            overflow-x: hidden;
        }
        .login-container {
            display: flex;
            min-height: 100vh;
        }
        .login-image-side {
            flex: 1;
            background-image: url('{{ asset('images/pos2.jpg') }}');
            background-size: cover;
            background-position: center;
            position: relative;
            display: none;
        }
        @media (min-width: 992px) {
            .login-image-side {
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                padding: 4rem;
            }
        }
        .login-image-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0) 40%, rgba(0,0,0,0.85) 100%);
        }
        .login-image-content {
            position: relative;
            z-index: 1;
            color: white;
        }
        .login-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: #ffffff;
        }
        .login-form-container {
            width: 100%;
            max-width: 420px;
        }
        .login-logo {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .login-logo i {
            color: #10b981;
            font-size: 2.2rem;
        }
        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 8px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(16,185,129,0.2);
            border-color: #10b981;
            background-color: #ffffff;
        }
        .btn-login {
            background-color: #0f172a;
            color: white;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #1e293b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15,23,42,0.15);
        }
        .btn-google {
            background-color: #ffffff;
            color: #334155;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-google:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            color: #334155;
        }
        @media all and (display-mode: standalone) {
            #backHomeBtn {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Image Side -->
        <div class="login-image-side">
            <div class="login-image-overlay"></div>
            <div class="login-image-content">
                <div class="d-flex align-items-center mb-4">
                    <div style="width: 55px; height: 55px; overflow: hidden; background: white; border-radius: 12px; display: flex; justify-content: center; align-items: center; margin-right: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Logo" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                    </div>
                    <h1 class="fw-bold mb-0">{{ __('Z-pos Enterprise') }}</h1>
                </div>
                <p class="fs-5 opacity-75 mb-0">{{ __('Empowering Retail, One Sale at a Time.') }}<br>{{ __('Fast, Secure, and Reliable Point of Sale.') }}</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="login-form-side position-relative">
            <a href="{{ url('/') }}" id="backHomeBtn" class="position-absolute top-0 start-0 m-4 text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary" style="z-index: 10; transition: color 0.3s;">
                <i class="bi bi-arrow-left"></i> {{ __('Back to Home') }}
            </a>
            <div class="position-absolute top-0 end-0 m-4" style="z-index: 10;">
                <div class="dropdown">
                    <a class="btn btn-light bg-white rounded-pill px-3 py-1 shadow-sm d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.85rem; color: #334155;">
                        @if(App::getLocale() == 'en')
                            <img src="https://flagcdn.com/w20/gb.png" alt="UK" class="me-1" style="width: 20px; border-radius: 2px;"> <span>{{ __('ENG') }}</span>
                        @else
                            <img src="https://flagcdn.com/w20/tz.png" alt="Tanzania" class="me-1" style="width: 20px; border-radius: 2px;"> <span>{{ __('SW') }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="min-width: auto; padding: 0.5rem;">
                        <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'sw') }}"><img src="https://flagcdn.com/w20/tz.png" class="me-2" style="width: 20px; border-radius: 2px;"> {{ __('Swahili') }}</a></li>
                        <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'en') }}"><img src="https://flagcdn.com/w20/gb.png" class="me-2" style="width: 20px; border-radius: 2px;"> {{ __('English') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="login-form-container">
                <div class="login-logo mb-4 d-flex justify-content-center align-items-center">
                    <div style="width: 50px; height: 50px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Icon" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                    </div>
                    <div class="ms-1 d-flex flex-column justify-content-center text-start">
                        <span class="fw-bold fs-2 text-primary lh-1">{{ __('Z-pos') }}</span>
                    </div>
                </div>

                <p class="text-muted text-center mb-4">{{ __('Log in to your account to continue') }}</p>

                @if(request()->has('expired') || session('error'))
                    <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-warning"></i>
                        <div>
                            {{ session('error') ?? __('Kipindi cha usalama kimeisha (Session Expired). Tafadhali jaribu tena.') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Enter your email') }}">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Password') }}</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color: #10b981;">{{ __('Forgot Password?') }}</a>
                            @endif
                        </div>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Enter your password') }}">
                            <span class="input-group-text bg-white @error('password') border-danger @enderror" style="cursor: pointer;" onclick="togglePassword('password', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted small" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    <button type="submit" class="btn-login mt-2 mb-3">{{ __('Log In') }}</button>
                    
                    <!-- TEMPORARILY DISABLED FOR SAFE BROWSING REVIEW
                    <a href="{{ route('auth.google') }}" class="btn-google text-decoration-none">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Logo" height="20" class="me-2"> {{ __('Continue with Google') }}
                    </a>
                    -->
                    
                    <div class="mt-4 text-center">
                        <span class="text-muted small">{{ __('Don\'t have an account?') }} <a href="{{ route('register') }}" class="text-decoration-none" style="color: #10b981; font-weight: 600;">{{ __('Sign Up') }}</a></span>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top text-center">
                        <div class="text-muted small mb-2 fw-semibold">{{ __('Need Help? Contact Customer Support') }}</div>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="mailto:info@z-pos.co.tz" class="text-decoration-none text-muted hover-primary small">
                                <i class="bi bi-envelope-fill me-1"></i> info@z-pos.co.tz
                            </a>
                            <a href="https://wa.me/255683628142" target="_blank" class="text-decoration-none text-muted hover-primary small">
                                <i class="bi bi-whatsapp me-1"></i> +255 683 628 142
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Force reload if page is loaded from bfcache (Back-Forward Cache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            const icon = iconElement.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
