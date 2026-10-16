<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Z-pos - Enterprise Point of Sale')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/sass/landing.scss', 'resources/js/app.js'])

    <!-- PWA Setup -->
    <link rel="manifest" href="/manifest.json?v=4">
    <meta name="theme-color" content="#10b981">
    <link rel="apple-touch-icon" href="/images/logo_pos.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('ServiceWorker registration successful');
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
    <style>
        /* Prevent global horizontal scroll on mobile */
        html, body {
            overflow-x: hidden;
            width: 100%;
        }
        /* Make mega-menu responsive */
        .mega-menu-dropdown {
            border-radius: 1rem;
            width: max-content;
            left: 50%;
            transform: translateX(-50%);
        }
        @media (max-width: 991px) {
            .mega-menu-dropdown {
                width: 100%;
                left: 0;
                transform: none;
                border-radius: 0.5rem;
            }
            .mega-menu-dropdown .d-flex {
                flex-direction: column;
                gap: 1.5rem !important;
            }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-landing" style="padding-top: 0.2rem; padding-bottom: 0.2rem;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div style="width: 40px; height: 40px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                    <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Icon" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                </div>
                <div class="ms-2 d-flex flex-column justify-content-center">
                    <span class="fw-bold fs-4 text-primary lh-1">Z-pos</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">{{ __('Home') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('features') ? 'active' : '' }}" href="{{ url('/features') }}" id="megaMenu" role="button" data-bs-toggle="dropdown">
                            {{ __('Features') }}
                        </a>
                        <div class="dropdown-menu shadow border-0 p-4 mt-0 mega-menu-dropdown" aria-labelledby="megaMenu">
                            <div class="d-flex gap-5">
                                <div>
                                    <h6 class="text-primary fw-bold mb-3"><i class="bi bi-cart3 text-success me-2"></i> {{ __('POS & Sales') }}</h6>
                                    <ul class="list-unstyled">
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Fast Checkout') }}</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Barcode Scanning') }}</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Multi-Payment') }}</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h6 class="text-primary fw-bold mb-3"><i class="bi bi-file-earmark-text text-success me-2"></i> {{ __('Documents') }}</h6>
                                    <ul class="list-unstyled">
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Custom Warranties') }}</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('A4 Invoices') }}</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Digital Receipts') }}</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h6 class="text-primary fw-bold mb-3"><i class="bi bi-box-seam text-success me-2"></i> {{ __('Inventory') }}</h6>
                                    <ul class="list-unstyled">
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Multi-Warehouse') }}</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Stock Alerts') }}</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Stock Adjustments') }}</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h6 class="text-primary fw-bold mb-3"><i class="bi bi-graph-up-arrow text-success me-2"></i> {{ __('Analytics') }}</h6>
                                    <ul class="list-unstyled">
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Sales Reports') }}</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Profit & Loss') }}</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ url('/features') }}">{{ __('Staff Performance') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top text-center">
                                <a href="{{ url('/features') }}" class="text-decoration-none fw-bold text-primary">{{ __('View All Features') }} <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pricing') ? 'active' : '' }}" href="{{ url('/pricing') }}">{{ __('Pricing') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('testimonials') ? 'active' : '' }}" href="{{ url('/testimonials') }}">{{ __('Testimonials') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">{{ __('About Us') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">{{ __('Contact') }}</a>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-3">
                        @if(App::getLocale() == 'en')
                            <a href="{{ route('lang.switch', 'sw') }}" class="btn btn-light bg-white rounded-pill px-3 py-1 shadow-sm d-flex align-items-center text-decoration-none mt-1" style="border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.9rem; color: #334155;">
                                <img src="https://flagcdn.com/w20/tz.png" alt="Tanzania" class="me-2" style="width: 20px; border-radius: 2px;"> Swahili
                            </a>
                        @else
                            <a href="{{ route('lang.switch', 'en') }}" class="btn btn-light bg-white rounded-pill px-3 py-1 shadow-sm d-flex align-items-center text-decoration-none mt-1" style="border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.9rem; color: #334155;">
                                <img src="https://flagcdn.com/w20/gb.png" alt="UK" class="me-2" style="width: 20px; border-radius: 2px;"> English
                            </a>
                        @endif
                    </li>
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link fw-bold text-dark me-3">{{ __('Sign in') }}</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-success text-white fw-bold shadow-sm px-4 py-2" style="border-radius: 8px;">{{ __('Try free for 7 days') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer id="contact-footer" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <a href="{{ url('/') }}" class="text-decoration-none d-inline-flex align-items-center mb-3 bg-white px-3 py-2 rounded-3 shadow-sm">
                        <div style="width: 45px; height: 45px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                            <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Icon" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                        </div>
                        <div class="ms-2 d-flex flex-column justify-content-center text-start">
                            <span class="fw-bold fs-4 text-primary lh-1">Z-pos</span>
                        </div>
                    </a>
                    <p class="text-white-50">The most advanced and reliable Point of Sale system {{ __('Built for East Africa') }}n enterprises. Accelerate your growth today.</p>
                    <div class="social-icons">
                        <a href="https://wa.me/255683628142" target="_blank"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://instagram.com/zpos.tz" target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@z-pos.co.tz" target="_blank"><i class="bi bi-envelope-fill"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5>{{ __('Product') }}</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/features') }}">{{ __('Features') }}</a></li>
                        <li><a href="{{ url('/pricing') }}">{{ __('Pricing') }}</a></li>
                        <li><a href="#">Hardware</a></li>
                        <li><a href="#">Updates</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5>{{ __('Company') }}</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/about') }}">{{ __('About Us') }}</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Partners</a></li>
                        <li><a href="{{ url('/contact') }}">{{ __('Contact') }}</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-4">
                    <h5>{{ __('Find Us') }}</h5>
                    <!-- Google Map -->
                    <div class="map-container mb-3" style="background: none; border: none; overflow: hidden; border-radius: 10px;">
                        <iframe src="https://www.google.com/maps?q=Uhuru+Plaza+Kariakoo+Dar+es+Salaam&output=embed" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            
            <div class="row footer-bottom align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; {{ date('Y') }} Z-pos. All rights reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="{{ url('/privacy') }}" class="text-decoration-none me-3" style="color: rgba(255,255,255,0.7);">Privacy Policy</a>
                    <a href="{{ url('/terms') }}" class="text-decoration-none me-3" style="color: rgba(255,255,255,0.7);">Terms of Service</a>
                    <a href="{{ url('/cookies') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.7);">Cookies</a>
                </div>
            </div>
        </div>
    </footer>


    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize Animations
        AOS.init({
            once: true,
            offset: 50,
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar-landing').classList.add('shadow-sm');
                document.querySelector('.navbar-landing').style.padding = '0.1rem 0';
            } else {
                document.querySelector('.navbar-landing').classList.remove('shadow-sm');
                document.querySelector('.navbar-landing').style.padding = '0.2rem 0';
            }
        });
        
        // Stop video when modal is closed
        var demoModal = document.getElementById('demoVideoModal');
        if (demoModal) {
            demoModal.addEventListener('hidden.bs.modal', function () {
                var video = document.getElementById('demoVideoElement');
                if (video) {
                    video.pause();
                    video.currentTime = 0; // Rewind the video to start
                }
            });
        }
    </script>
</body>
</html>
