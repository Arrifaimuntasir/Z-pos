<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Z-pos Enterprise</title>

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
            max-width: 420px;
            padding-top: 40px;
            padding-bottom: 40px;
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
                <p class="fs-5 opacity-75 mb-0">Join thousands of retailers growing their businesses.<br>Create your account today.</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="login-form-side position-relative">
            <a href="{{ url('/') }}" class="position-absolute top-0 start-0 m-4 text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary" style="z-index: 10; transition: color 0.3s;">
                <i class="bi bi-arrow-left"></i> Back to Home
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

                <h4 class="fw-bold mb-1 text-center">{{ __('Create an Account') }}</h4>
                <p class="text-muted text-center mb-4">Sign up to get started with Z-pos</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    @if(isset($package))
                    <input type="hidden" name="package" value="{{ $package }}">
                    <div class="alert alert-primary bg-primary bg-opacity-10 border-0 d-flex align-items-center mb-4">
                        <i class="bi bi-box-seam text-primary fs-4 me-3"></i>
                        <div>
                            <div class="small text-muted">Selected Plan</div>
                            <strong class="text-primary text-uppercase">{{ $package }}</strong>
                        </div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label for="shop_name" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">Shop / Business Name</label>
                        <input id="shop_name" type="text" class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" value="{{ old('shop_name') }}" required autocomplete="shop_name" autofocus placeholder="e.g. My Zamar Shop">
                        @error('shop_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label for="first_name" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">First Name</label>
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" placeholder="First name">
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">Last Name</label>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" placeholder="Last name">
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

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
                        <label for="password" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Password') }}</label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Create a strong password">
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

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Confirm Password') }}</label>
                        <div class="input-group">
                            <input id="password-confirm" type="password" class="form-control border-end-0" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm your password') }}">
                            <span class="input-group-text bg-white" style="cursor: pointer;" onclick="togglePassword('password-confirm', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn-login mt-2 mb-3">{{ __('Create Account') }}</button>
                    
                    <!-- TEMPORARILY DISABLED FOR SAFE BROWSING REVIEW
                    <a href="#" id="googleAuthBtn" class="btn-google text-decoration-none mb-4" style="background-color: #ffffff; color: #334155; padding: 0.8rem; border-radius: 8px; font-weight: 600; width: 100%; border: 1px solid #e2e8f0; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Logo" height="20" class="me-2"> Continue with Google
                    </a>
                    
                    <script>
                        document.getElementById('googleAuthBtn').addEventListener('click', function(e) {
                            e.preventDefault();
                            var shopNameInput = document.getElementById('shop_name');
                            if (!shopNameInput.value.trim()) {
                                shopNameInput.classList.add('is-invalid');
                                shopNameInput.focus();
                                alert('Please enter your Shop / Business Name first before continuing with Google.');
                            } else {
                                var packageVal = "{{ $package ?? 'starter' }}";
                                window.location.href = "{{ route('auth.google') }}?shop_name=" + encodeURIComponent(shopNameInput.value.trim()) + "&package=" + packageVal;
                            }
                        });
                    </script>
                    -->
                    
                    <div class="text-center">
                        <span class="text-muted small">{{ __('Already have an account?') }} <a href="{{ route('login') }}" class="text-decoration-none" style="color: #10b981; font-weight: 600;">{{ __('Log In') }}</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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
