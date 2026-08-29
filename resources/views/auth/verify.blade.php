<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - Z-pos Enterprise</title>

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
        .verify-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .verify-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 500px;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .verify-icon-container {
            width: 80px;
            height: 80px;
            background-color: rgba(16, 185, 129, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem auto;
        }
        .verify-icon-container i {
            font-size: 2.5rem;
            color: #10b981;
        }
        .btn-resend {
            background-color: #0f172a;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: all 0.3s;
            margin-top: 1.5rem;
        }
        .btn-resend:hover {
            background-color: #1e293b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15,23,42,0.15);
        }
        .top-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #10b981, #3b82f6);
        }
    </style>
</head>
<body>

    <div class="verify-container">
        <div class="verify-card">
            <div class="top-accent"></div>
            
            <div class="verify-icon-container">
                <i class="bi bi-envelope-check"></i>
            </div>

            <h3 class="fw-bold mb-3">{{ __('Verify Your Email Address') }}</h3>
            
            <p class="text-muted mb-4" style="line-height: 1.6;">
                {{ __('Almost there! We just need to verify your email address to ensure your account is secure. Please check your inbox for a verification link.') }}
            </p>

            @if (session('resent'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ __('A fresh verification link has been sent to your email address.') }}</div>
                </div>
            @endif

            <div class="mt-4 pt-4 border-top">
                <p class="text-muted small mb-2">{{ __('Didn\'t receive the email? Check your spam folder or request a new one below.') }}</p>
                
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn-resend">
                        <i class="bi bi-arrow-clockwise me-2"></i> {{ __('Click here to request another link') }}
                    </button>
                </form>
            </div>
            
            <div class="mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted text-decoration-none">
                        <i class="bi bi-box-arrow-left me-1"></i> Logout and return to home
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
