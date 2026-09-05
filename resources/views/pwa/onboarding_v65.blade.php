<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#10b981">
    <title>Z-pos (enterprise Point Of Sell)</title>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.png') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            overflow: hidden; /* Prevent scrolling, full app feel */
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
        }

        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            text-align: center;
            padding: 3.5rem 1.5rem 5rem 1.5rem;
            box-sizing: border-box;
        }
        
        .slide-content-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100%;
            width: 100%;
        }

        .icon-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin-bottom: 2rem;
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border: 1px solid rgba(255,255,255,0.8);
            color: #10b981;
        }

        .slide-title {
            font-weight: 800;
            font-size: 1.5rem;
            color: #0f172a;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .slide-desc {
            font-size: 1.05rem;
            color: #64748b;
            line-height: 1.6;
            max-width: 320px;
        }

        .swiper-pagination {
            bottom: 1.5rem !important;
        }

        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background-color: #cbd5e1;
            opacity: 1;
            transition: all 0.3s ease;
            margin: 0 5px !important;
        }

        .swiper-pagination-bullet-active {
            width: 24px;
            border-radius: 4px;
            background-color: #10b981;
        }

        .btn-start {
            background-color: #10b981;
            color: white;
            font-weight: 700;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.15rem;
            width: 100%;
            border: none;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-start:active {
            transform: scale(0.98);
        }

        .btn-login {
            background-color: white;
            color: #0f172a;
            font-weight: 700;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.15rem;
            width: 100%;
            border: 2px solid #e2e8f0;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }
        
        .btn-login:active {
            background-color: #f8fafc;
            transform: scale(0.98);
        }

        /* Slide specific icon colors */
        .color-sales { color: #3b82f6; }
        .color-inventory { color: #f59e0b; }
        .color-branches { color: #8b5cf6; }
        .color-warranty { color: #ec4899; }
        .color-reports { color: #14b8a6; }
        .color-expense { color: #ef4444; }
        .color-staff { color: #6366f1; }
        
        .skip-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 20;
            color: #64748b;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
            padding: 6px 14px;
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            backdrop-filter: blur(5px);
            border: 1px solid #e2e8f0;
        }

        .lang-dropdown {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 20;
        }

        .lang-btn {
            color: #334155;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 6px 12px;
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* Pricing Specific Styles */
        .pricing-card-mini {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
            text-align: left;
            width: 100%;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        .pricing-title { font-weight: 700; font-size: 1.1rem; }
        .pricing-price { font-weight: 800; font-size: 1.15rem; color: #0f172a; margin: 5px 0; }
        .pricing-features { font-size: 0.8rem; color: #64748b; margin-bottom: 0; padding-left: 0; list-style: none; }
        .pricing-features li { margin-bottom: 4px; display: flex; align-items: flex-start; }
        .pricing-features i { color: #10b981; margin-right: 6px; margin-top: 2px; }
        
        .swiper-slide::-webkit-scrollbar {
            width: 4px;
        }
        .swiper-slide::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <!-- Language Switcher (Left) -->
    <div class="lang-dropdown dropdown">
        <a class="lang-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            @if(app()->getLocale() == 'en')
                <img src="https://flagcdn.com/w20/gb.png" alt="EN" style="width: 18px; border-radius: 2px; margin-right: 6px;"> ENG
            @else
                <img src="https://flagcdn.com/w20/tz.png" alt="SW" style="width: 18px; border-radius: 2px; margin-right: 6px;"> SW
            @endif
        </a>
        <ul class="dropdown-menu shadow border-0" style="min-width: auto; padding: 0.5rem;">
            <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'sw') }}"><img src="https://flagcdn.com/w20/tz.png" class="me-2" style="width: 20px; border-radius: 2px;"> {{ __('Swahili') }}</a></li>
            <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'en') }}"><img src="https://flagcdn.com/w20/gb.png" class="me-2" style="width: 20px; border-radius: 2px;"> {{ __('English') }}</a></li>
        </ul>
    </div>

    <!-- Skip Button (Right) -->
    <a href="{{ route('register') }}" class="skip-btn shadow-sm" id="skipBtn">{{ __('pwa.skip') }}</a>

    <!-- Swiper -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            
            <!-- Slide 1: Welcome -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <img src="{{ asset('images/icon-192.png') }}" alt="Logo" class="shadow-sm rounded-circle mb-4" style="width: 110px; height: 110px; object-fit: contain;">
                    <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-3 fw-bold">{{ __('pwa.welcome') }}</div>
                    <h2 class="slide-title">{{ __('Z-pos') }} <br><span class="text-success fs-4">(enterprise Point Of Sell)</span></h2>
                    <p class="slide-desc mt-2">{{ __('pwa.welcome.desc') }}</p>
                </div>
            </div>

            <!-- Slide 2: Sales -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <div class="icon-circle color-sales">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <h2 class="slide-title">{{ __('pwa.sales') }}</h2>
                    <p class="slide-desc">{{ __('pwa.sales.desc') }}</p>
                </div>
            </div>

            <!-- Slide 3: Inventory -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <div class="icon-circle color-inventory">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h2 class="slide-title">{{ __('pwa.inventory') }}</h2>
                    <p class="slide-desc">{{ __('pwa.inventory.desc') }}</p>
                </div>
            </div>

            <!-- Slide 4: Branches -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <div class="icon-circle color-branches">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h2 class="slide-title">{{ __('pwa.branches') }}</h2>
                    <p class="slide-desc">{{ __('pwa.branches.desc') }}</p>
                </div>
            </div>

            <!-- Slide 5: Warranty -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <div class="icon-circle color-warranty">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h2 class="slide-title">{{ __('pwa.warranty') }}</h2>
                    <p class="slide-desc">{{ __('pwa.warranty.desc') }}</p>
                </div>
            </div>

            <!-- Slide 6: Expenses -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <div class="icon-circle color-expense">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h2 class="slide-title">{{ __('pwa.expenses') }}</h2>
                    <p class="slide-desc">{{ __('pwa.expenses.desc') }}</p>
                </div>
            </div>

            <!-- Slide 7: Reports -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <div class="icon-circle color-reports">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <h2 class="slide-title">{{ __('pwa.reports') }}</h2>
                    <p class="slide-desc">{{ __('pwa.reports.desc') }}</p>
                </div>
            </div>

            <!-- Slide 8: Staff -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <div class="icon-circle color-staff">
                        <i class="bi bi-people"></i>
                    </div>
                    <h2 class="slide-title">{{ __('pwa.staff') }}</h2>
                    <p class="slide-desc">{{ __('pwa.staff.desc') }}</p>
                </div>
            </div>

            <!-- Slide 9: Pricing - Starter -->
            <div class="swiper-slide" style="justify-content: center; padding-top: 2rem;">
                <h2 class="slide-title mb-1" style="font-size: 1.4rem;">{{ __('pwa.pricing') }}</h2>
                <p class="slide-desc mb-3" style="font-size: 0.85rem;">{{ __('pwa.pricing.desc') }}</p>
                
                <a href="{{ route('register') }}?package=starter" class="text-decoration-none w-100 px-2" style="color: inherit; max-width: 100%; width: 100%;">
                    <div class="pricing-card-mini mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="pricing-title text-primary">{{ __('Starter') }}</span>
                            <span class="badge bg-success bg-opacity-10 text-success">TZS 15K/mo</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-gift-fill text-success"></i> <span>{{ __('7 Days Free Trial') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('1 Branch') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('2 Users') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Unlimited Products') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Inventory Management') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Professional Invoicing') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Custom Warranties') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Advanced Analytics') }}</span></li>
                        </ul>
                    </div>
                </a>
                <div class="mt-2 text-muted" style="font-size: 0.8rem;"><i class="bi bi-arrow-left me-1"></i> {{ __('Swipe for more packages') }} <i class="bi bi-arrow-right ms-1"></i></div>
            </div>

            <!-- Slide 10: Pricing - Professional -->
            <div class="swiper-slide" style="justify-content: center; padding-top: 2rem;">
                <h2 class="slide-title mb-1" style="font-size: 1.4rem;">{{ __('pwa.pricing') }}</h2>
                <p class="slide-desc mb-3" style="font-size: 0.85rem;">{{ __('pwa.pricing.desc') }}</p>

                <a href="{{ route('register') }}?package=professional" class="text-decoration-none w-100 px-2" style="color: inherit; max-width: 100%; width: 100%;">
                    <div class="pricing-card-mini mb-0" style="border: 2px solid #10b981;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="pricing-title text-success">{{ __('Professional') }}</span>
                            <span class="badge bg-success">TZS 45K/mo</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-gift-fill text-success"></i> <span>{{ __('7 Days Free Trial') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Up to 5 Branches') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Unlimited Users') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Unlimited Products') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Inventory Management') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Professional Invoicing') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Custom Warranties') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Advanced Analytics') }}</span></li>
                        </ul>
                    </div>
                </a>
                <div class="mt-2 text-muted" style="font-size: 0.8rem;"><i class="bi bi-arrow-left me-1"></i> {{ __('Swipe for more packages') }} <i class="bi bi-arrow-right ms-1"></i></div>
            </div>

            <!-- Slide 11: Pricing - Enterprise -->
            <div class="swiper-slide" style="justify-content: center; padding-top: 2rem;">
                <h2 class="slide-title mb-1" style="font-size: 1.4rem;">{{ __('pwa.pricing') }}</h2>
                <p class="slide-desc mb-3" style="font-size: 0.85rem;">{{ __('pwa.pricing.desc') }}</p>

                <a href="{{ route('register') }}?package=enterprise" class="text-decoration-none w-100 px-2" style="color: inherit; max-width: 100%; width: 100%;">
                    <div class="pricing-card-mini mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="pricing-title text-dark">{{ __('Enterprise') }}</span>
                            <span class="badge bg-dark">TZS 110K/mo</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-gift-fill text-success"></i> <span>{{ __('7 Days Free Trial') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Unlimited Branches') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Unlimited Users') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Unlimited Products') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Inventory Management') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Professional Invoicing') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Custom Warranties') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Advanced Analytics') }}</span></li>
                        </ul>
                    </div>
                </a>
            </div>

            <!-- Slide 10: Get Started -->
            <div class="swiper-slide">
                <div class="slide-content-wrapper">
                    <img src="{{ asset('images/icon-192.png') }}" alt="Logo" class="shadow-sm rounded-circle mb-3" style="width: 100px; height: 100px;">
                    <h2 class="slide-title mb-2">{{ __('pwa.ready') }}</h2>
                    <p class="slide-desc mx-auto">{{ __('pwa.ready.desc') }}</p>
                    
                    <div class="w-100 mt-4" style="max-width: 320px;">
                        <a href="{{ route('register') }}" class="btn-start text-center d-block">{{ __('Register') }}</a>
                        <a href="{{ route('login') }}" class="btn-login text-center d-block">{{ __('Login') }}</a>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
        <div class="position-absolute bottom-0 end-0 p-2 text-muted" style="font-size: 0.6rem; z-index: 100;">{{ __('v64') }}</div>
    </div>

    <!-- Bootstrap & Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check for saved slide in URL query param to prevent resetting to slide 1 on language change
            var initialSlide = 0;
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('slide')) {
                var slideVal = parseInt(urlParams.get('slide'));
                if(!isNaN(slideVal) && slideVal >= 0 && slideVal <= 11) {
                    initialSlide = slideVal;
                }
            }

            var swiper = new Swiper(".mySwiper", {
                initialSlide: initialSlide,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                // Allow touch swipe
                simulateTouch: true,
                grabCursor: true,
                on: {
                    slideChange: function () {
                        // Update URL silently so language switch reload remembers slide without interrupting swipe
                        if (history.replaceState) {
                            var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?slide=' + swiper.activeIndex;
                            history.replaceState({path:newUrl}, '', newUrl);
                        }
                        
                        var skipBtn = document.getElementById('skipBtn');
                        // Hide skip button on the last slide (index 11)
                        if(swiper.activeIndex === 11) {
                            skipBtn.style.display = 'none';
                        } else {
                            skipBtn.style.display = 'block';
                        }
                    }
                }
            });
            
            // Execute once on load to handle skip button visibility if restored to last slide
            if(swiper.activeIndex === 11) {
                document.getElementById('skipBtn').style.display = 'none';
            }
            
            // Mark onboarding as seen when clicking login, register, or skip
            document.querySelectorAll('a[href*="login"], a[href*="register"], #skipBtn').forEach(function(el) {
                el.addEventListener('click', function() {
                    localStorage.setItem('pwa_onboarding_seen', 'true');
                    document.cookie = "pwa_onboarding_seen=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
                });
            });

            // Append current slide query param to language switcher links before navigating
            document.querySelectorAll('.dropdown-item').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    var href = this.getAttribute('href');
                    var char = href.indexOf('?') === -1 ? '?' : '&';
                    this.setAttribute('href', href + char + 'slide=' + swiper.activeIndex);
                });
            });
        });
    </script>
</body>
</html>
</body>
</html>
