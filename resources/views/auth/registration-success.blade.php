<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Registration Successful - Z-pos Enterprise') }}</title>

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
            overflow-y: auto;
        }
        .login-form-container {
            width: 100%;
            max-width: 480px;
            padding-top: 40px;
            padding-bottom: 40px;
            text-align: center;
        }
        .login-logo {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 2rem;
        }
        .btn-login {
            background-color: #0f172a;
            color: white;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
        }
        .btn-login:hover {
            background-color: #1e293b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15,23,42,0.15);
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
                <p class="fs-5 opacity-75 mb-0">{{ __('Join thousands of retailers growing their businesses.') }}<br>{{ __('Create your account today.') }}</p>
            </div>
        </div>

        <!-- Content Side -->
        <div class="login-form-side position-relative">
            <a href="{{ url('/') }}" class="position-absolute top-0 start-0 m-4 text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary" style="z-index: 10; transition: color 0.3s;">
                <i class="bi bi-arrow-left"></i> {{ __('Back to Home') }}
            </a>
            
            <!-- Language Switcher -->
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
                    <div style="width: 60px; height: 60px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Icon" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                    </div>
                    <div class="ms-2 d-flex flex-column justify-content-center text-start">
                        <span class="fw-bold text-primary lh-1" style="font-size: 2.2rem;">{{ __('Z-pos') }}</span>
                    </div>
                </div>

                <div class="success-icon shadow-sm mt-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <h2 class="fw-bold mb-3 text-dark">{{ __('Registration Successful!') }}</h2>
                <p class="text-muted mb-5 fs-5 px-3" style="line-height: 1.6;">
                    {{ __('Thank you for registering with Z-pos Enterprise. Your account has been created successfully. You can now log in to your dashboard to start managing your business.') }}
                </p>

                <div class="px-4">
                    <a href="{{ route('login') }}" class="btn-login mb-4 shadow-sm d-flex align-items-center justify-content-center gap-2 fs-5">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('Proceed to Login') }}
                    </a>
                </div>
                
                <div class="text-center mt-3">
                    <p class="text-muted small">
                        {{ __('Need help?') }} <a href="https://wa.me/255683628142" target="_blank" class="text-decoration-none" style="color: #10b981; font-weight: 600;"><i class="bi bi-whatsapp"></i> {{ __('Contact Support') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
