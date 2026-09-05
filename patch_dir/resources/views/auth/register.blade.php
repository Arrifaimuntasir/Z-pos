<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Register - Z-pos Enterprise') }}</title>

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
    
    <!-- Intl Tel Input CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">

    <style>
        .iti { width: 100%; }
        .iti__flag {background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/img/flags.png");}
        @media (min-resolution: 2x) {
            .iti__flag {background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/img/flags@2x.png");}
        }
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
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
                    <h1 class="fw-bold mb-0">{{ __('Z-pos Enterprise') }}</h1>
                </div>
                <p class="fs-5 opacity-75 mb-0">{{ __('Join thousands of retailers growing their businesses.') }}<br>{{ __('Create your account today.') }}</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="login-form-side position-relative">
            <a href="{{ url('/') }}" class="position-absolute top-0 start-0 m-4 text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary" style="z-index: 10; transition: color 0.3s;">
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

                <h4 class="fw-bold mb-1 text-center">{{ __('Create an Account') }}</h4>
                <p class="text-muted text-center mb-4">{{ __('Sign up to get started with Z-pos') }}</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="alert alert-primary bg-primary bg-opacity-10 border-0 d-flex align-items-center mb-4 dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer; position: relative;">
                        <i class="bi bi-box-seam text-primary fs-4 me-3"></i>
                        <div class="w-100">
                            <div class="small text-muted">{{ __('Selected Plan') }}</div>
                            <div class="d-flex justify-content-between align-items-center">
                                @php
                                    $currentPackage = old('package', $package ?? 'starter');
                                    $packageText = __('STARTER (TZS 15K/month)');
                                    if ($currentPackage == 'professional') $packageText = __('PROFESSIONAL (TZS 45K/month)');
                                    if ($currentPackage == 'enterprise') $packageText = __('ENTERPRISE (TZS 110K/month)');
                                @endphp
                                <strong class="text-primary text-uppercase" id="selectedPlanText" style="font-size: 1.05rem;">{{ $packageText }}</strong>
                                <i class="bi bi-chevron-down text-primary fs-5"></i>
                            </div>
                        </div>
                        <ul class="dropdown-menu shadow border-0 w-100 mt-1" style="border-radius: 10px; overflow: hidden; padding: 0.5rem 0;">
                            <li><a class="dropdown-item py-2 px-3 fw-semibold {{ $currentPackage == 'starter' ? 'bg-primary text-white' : 'text-dark' }}" href="#" onclick="selectPlan('starter', '{{ __('STARTER (TZS 15K/month)') }}', this)">
                                {{ __('STARTER (TZS 15K/month)') }}
                            </a></li>
                            <li><a class="dropdown-item py-2 px-3 fw-semibold {{ $currentPackage == 'professional' ? 'bg-primary text-white' : 'text-dark' }}" href="#" onclick="selectPlan('professional', '{{ __('PROFESSIONAL (TZS 45K/month)') }}', this)">
                                {{ __('PROFESSIONAL (TZS 45K/month)') }}
                            </a></li>
                            <li><a class="dropdown-item py-2 px-3 fw-semibold {{ $currentPackage == 'enterprise' ? 'bg-primary text-white' : 'text-dark' }}" href="#" onclick="selectPlan('enterprise', '{{ __('ENTERPRISE (TZS 110K/month)') }}', this)">
                                {{ __('ENTERPRISE (TZS 110K/month)') }}
                            </a></li>
                        </ul>
                        <input type="hidden" name="package" id="packageInput" value="{{ $currentPackage }}">
                    </div>

                    <script>
                        function selectPlan(val, text, el) {
                            event.preventDefault();
                            document.getElementById('packageInput').value = val;
                            document.getElementById('selectedPlanText').innerText = text;
                            
                            let items = el.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
                            items.forEach(item => {
                                item.classList.remove('bg-primary', 'text-white');
                                item.classList.add('text-dark');
                            });
                            
                            el.classList.remove('text-dark');
                            el.classList.add('bg-primary', 'text-white');
                        }
                    </script>

                    <div class="mb-4">
                        <label for="business_type" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Business Category') }}</label>
                        <select id="business_type" name="business_type" class="form-control @error('business_type') is-invalid @enderror" required style="cursor: pointer; appearance: auto;">
                            <option value="Retail / General" {{ old('business_type') == 'Retail / General' ? 'selected' : '' }}>{{ __('Retail / General') }}</option>
                            <option value="Electronics / IT" {{ old('business_type') == 'Electronics / IT' ? 'selected' : '' }}>{{ __('Electronics & IT (Phones, Computers, etc)') }}</option>
                            <option value="Pharmacy / Health" {{ old('business_type') == 'Pharmacy / Health' ? 'selected' : '' }}>{{ __('Pharmacy / Health & Beauty') }}</option>
                            <option value="Supermarket / Grocery" {{ old('business_type') == 'Supermarket / Grocery' ? 'selected' : '' }}>{{ __('Supermarket / Grocery') }}</option>
                            <option value="Restaurant / Food" {{ old('business_type') == 'Restaurant / Food' ? 'selected' : '' }}>{{ __('Restaurant / Cafe / Food') }}</option>
                            <option value="Hardware / Construction" {{ old('business_type') == 'Hardware / Construction' ? 'selected' : '' }}>{{ __('Hardware / Construction') }}</option>
                            <option value="Clothing / Boutique" {{ old('business_type') == 'Clothing / Boutique' ? 'selected' : '' }}>{{ __('Clothing / Boutique') }}</option>
                            <option value="Services / Consulting" {{ old('business_type') == 'Services / Consulting' ? 'selected' : '' }}>{{ __('Services / Consulting') }}</option>
                        </select>
                        @error('business_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="shop_name" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Shop / Business Name') }}</label>
                        <input id="shop_name" type="text" class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" value="{{ old('shop_name') }}" required autocomplete="shop_name" placeholder="{{ __('e.g. My Zamar Shop') }}">
                        @error('shop_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label for="first_name" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('First Name') }}</label>
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" placeholder="{{ __('First name') }}">
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Last Name') }}</label>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" placeholder="{{ __('Last name') }}">
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
                        <label for="phone" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Phone Number') }}</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="{{ __('e.g. 0712345678') }}">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-muted small text-uppercase tracking-wider">{{ __('Password') }}</label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Create a strong password') }}">
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
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Logo" height="20" class="me-2"> {{ __('Continue with Google') }}
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

    <!-- Intl Tel Input JS -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var input = document.querySelector("#phone");
            var iti = window.intlTelInput(input, {
                initialCountry: "tz",
                preferredCountries: ["tz", "ke", "ug", "rw", "bi", "cd", "zm", "mw"],
                separateDialCode: true,
                countrySearch: true, // Enable country search
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js",
            });

            var form = document.querySelector("form");
            form.addEventListener("submit", function(e) {
                if (input.value.trim() === '') {
                    e.preventDefault();
                    alert("Tafadhali jaza namba yako ya simu.");
                    input.focus();
                    return;
                }

                if (iti.isValidNumber()) {
                    input.value = iti.getNumber();
                } else {
                    e.preventDefault();
                    alert("Tafadhali andika namba ya simu iliyo sahihi kulingana na nchi yako (Hakikisha idadi ya namba imekamilika).");
                    input.focus();
                }
            });
        });
    </script>

    <!-- Coming Soon Overlay for Services/Consulting -->
    <div id="coming-soon-overlay" class="position-fixed w-100 h-100 top-0 start-0 d-none align-items-center justify-content-center" style="background: rgba(248, 250, 252, 0.85); z-index: 9999; backdrop-filter: blur(4px); opacity: 0; transition: opacity 0.3s ease;">
        <div class="bg-white rounded-4 shadow-lg p-5 text-center" style="max-width: 450px; border: 1px solid rgba(0,0,0,0.08); transform: translateY(20px); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); opacity: 0;" id="coming-soon-modal">
            <div class="mb-4 d-flex justify-content-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="bi bi-briefcase text-dark" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <h2 class="fw-bold text-dark mb-2" style="font-size: 1.7rem;">{{ __('Services & Consulting') }}</h2>
            <div class="mb-4">
                <span class="badge bg-dark px-3 py-2 rounded-pill fw-semibold" style="letter-spacing: 0.5px; font-size: 0.75rem;">{{ __('COMING SOON') }}</span>
            </div>
            <p class="text-muted mb-4" style="font-size: 0.95rem; line-height: 1.6;">
                {{ __('A dedicated system for managing service and consulting businesses is in the final stages of development. Please select another business category for now.') }}
            </p>
            <button type="button" class="btn btn-dark rounded-pill px-5 py-3 fw-semibold w-100" onclick="closeComingSoon()">
                {{ __('Okay, Select Another') }}
            </button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const businessTypeSelect = document.getElementById('business_type');
            const comingSoonOverlay = document.getElementById('coming-soon-overlay');
            const comingSoonModal = document.getElementById('coming-soon-modal');
            
            function showOverlay() {
                comingSoonOverlay.classList.remove('d-none');
                comingSoonOverlay.classList.add('d-flex');
                void comingSoonOverlay.offsetWidth;
                comingSoonOverlay.style.opacity = '1';
                setTimeout(() => {
                    comingSoonModal.style.transform = 'translateY(0)';
                    comingSoonModal.style.opacity = '1';
                }, 50);
            }

            let previousValue = businessTypeSelect.value === 'Services / Consulting' ? '' : businessTypeSelect.value;

            if (businessTypeSelect.value === 'Services / Consulting') {
                showOverlay();
            }

            businessTypeSelect.addEventListener('change', function() {
                if (this.value === 'Services / Consulting') {
                    showOverlay();
                    this.value = previousValue;
                } else {
                    previousValue = this.value;
                }
            });

            window.closeComingSoon = function() {
                comingSoonModal.style.transform = 'translateY(20px)';
                comingSoonModal.style.opacity = '0';
                setTimeout(() => {
                    comingSoonOverlay.style.opacity = '0';
                    setTimeout(() => {
                        comingSoonOverlay.classList.remove('d-flex');
                        comingSoonOverlay.classList.add('d-none');
                    }, 300);
                }, 150);
            };
        });
    </script>
</body>
</html>
