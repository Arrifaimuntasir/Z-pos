<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - Z-pos Enterprise</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
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
        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 1rem;
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
                    <h1 class="fw-bold mb-0">Z-pos Enterprise</h1>
                </div>
                <p class="fs-5 opacity-75 mb-0">Securely recover your account access.</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="login-form-side position-relative">
            <a href="{{ route('login') }}" class="position-absolute top-0 start-0 m-4 text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary" style="z-index: 10; transition: color 0.3s;">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
            <div class="login-form-container">
                <div class="login-logo mb-4 d-flex justify-content-center align-items-center">
                    <div style="width: 50px; height: 50px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Icon" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                    </div>
                    <div class="ms-3 d-flex flex-column justify-content-center text-start">
                        <span class="fw-bold fs-2 text-primary lh-1">Z-pos</span>
                    </div>
                </div>

                <h4 class="fw-bold mb-1 text-center">{{ __('Reset Password') }}</h4>
                <p class="text-muted text-center mb-4">Enter your email and we'll send you a link to reset your password.</p>

                @if (session('status'))
                    <div class="alert alert-success mb-4 text-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Enter your email') }}">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-login mt-2 mb-3 d-flex justify-content-center align-items-center gap-2">
                        <i class="bi bi-envelope-paper"></i> Send Reset Link
                    </button>
                    
                </form>
            </div>
        </div>
    </div>

</body>
</html>
