@extends('layouts.landing')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section pb-5" style="padding-top: 120px;">
        <div class="container py-4">
            <div class="row align-items-center">
                <!-- Left Side: Text and CTA -->
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    
                    <!-- Top Badge -->
                    <div class="d-inline-flex align-items-center bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 mb-4" style="font-weight: 500; font-size: 0.85rem;">
                        <span class="badge bg-success rounded-circle p-1 me-2" style="width: 6px; height: 6px; padding: 0 !important;"></span>
                        New &bull; Mobile + Web &bull; Built for Tanzania
                    </div>
                    
                    <!-- Main Heading -->
                    <h1 class="fw-bold mb-4" style="font-size: clamp(2.5rem, 8vw, 4.5rem); letter-spacing: -1.5px; line-height: 1.1; color: #0f172a;">
                        {!! __('Run your shop <br> from your pocket.') !!}
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="fs-5 text-muted mb-5" style="max-width: 500px; line-height: 1.6;">
                        {{ __('A complete modern system for managing sales, inventory, and profits for all retail and wholesale shops. Digitize your business with Z-pos.') }}
                    </p>
                    
                    <!-- Buttons -->
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="{{ route('register') }}" class="btn btn-success text-white fw-bold px-4 py-3 shadow-sm" style="border-radius: 8px;">
                            {{ __('Start free trial') }} <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#demoVideoModal" class="btn btn-outline-primary fw-bold px-4 py-3 shadow-sm" style="border-radius: 8px;">
                            <i class="bi bi-play-fill me-1"></i> {{ __('Watch 10-sec demo') }}
                        </button>
                    </div>
                    
                    <!-- Stats Section -->
                    <div class="row mt-5 pt-3" style="max-width: 500px;">
                        <div class="col-4 border-end border-light">
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">150+</h4>
                            <p class="text-muted small mb-0">{{ __('shops onboarded') }}</p>
                        </div>
                        <div class="col-4 border-end border-light">
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">Tsh 120M+</h4>
                            <p class="text-muted small mb-0">{{ __('processed monthly') }}</p>
                        </div>
                        <div class="col-4">
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">4.8 <i class="bi bi-star-fill text-warning ms-1" style="font-size: 0.9rem;"></i></h4>
                            <p class="text-muted small mb-0">{{ __('User Rating') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side: Image -->
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="hero-image text-center">
                        <img src="{{ asset('images/hero_pos2.jfif') }}" alt="Z-pos interface" class="img-fluid" style="max-height: 480px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners / Clients -->
    <section class="py-5 border-bottom bg-white">
        <div class="container text-center">
            <h6 class="text-muted fw-bold mb-5 text-uppercase tracking-wider">{{ __('Trusted by Industry Leaders') }}</h6>
            <div class="row align-items-center justify-content-center">
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <h5 class="fw-bold mb-2 text-dark">Azam</h5>
                    <img src="{{ asset('images/azamtv.png') }}" alt="Azam" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <h5 class="fw-bold mb-2 text-dark">Vodacom</h5>
                    <img src="{{ asset('images/images.jfif') }}" alt="Vodacom" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <h5 class="fw-bold mb-2 text-dark">CRDB</h5>
                    <img src="{{ asset('images/images.png') }}" alt="CRDB" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="400">
                    <h5 class="fw-bold mb-2 text-dark">Shoppers</h5>
                    <img src="{{ asset('images/images (1).png') }}" alt="Shoppers" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="500">
                    <h5 class="fw-bold mb-2 text-dark">Yas</h5>
                    <img src="{{ asset('images/Yas_Tanzania.svg') }}" alt="Yas" style="height: 40px; object-fit: contain;">
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-2 border border-success">{{ __('Core Features') }}</span>
                <h2 class="fw-bold text-primary display-5">{{ __('Everything you need to scale') }}</h2>
                <p class="text-muted fs-5 mt-3 max-w-2xl mx-auto">{{ __('From single shops to nationwide chains, we\'ve got you covered.') }}</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-upc-scan"></i></div>
                        <h3>{{ __('Lightning Fast POS') }}</h3>
                        <p>{{ __('Process sales in seconds using barcode scanners, shortcuts, and an intuitive touch-friendly interface designed for speed.') }}</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-box-seam"></i></div>
                        <h3>{{ __('Smart Inventory') }}</h3>
                        <p>{{ __('Track stock across multiple branches in real-time. Get low-stock alerts, manage expiry dates, and handle seamless transfers.') }}</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-pie-chart"></i></div>
                        <h3>{{ __('Advanced Analytics') }}</h3>
                        <p>{{ __('Make data-driven decisions with detailed reports on daily sales, profit margins, employee performance, and top-selling items.') }}</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-shield-check"></i></div>
                        <h3>{{ __('Enterprise Security') }}</h3>
                        <p>{{ __('Role-based access control ensures staff only see what they need to. Activity logs track every void, discount, and deletion.') }}</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-credit-card"></i></div>
                        <h3>{{ __('Multi-Payment Ready') }}</h3>
                        <p>{{ __('Accept Cash, Cards, and Mobile Money (M-Pesa, Tigo Pesa, Airtel Money) seamlessly in a single unified checkout flow.') }}</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-printer"></i></div>
                        <h3>{{ __('Hardware Integrated') }}</h3>
                        <p>{{ __('Plug and play with thermal receipt printers, cash drawers, customer displays, and external barcode scanners without hassle.') }}</p>
                    </div>
                </div>
                <!-- New Features added from recent updates -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="700">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-file-earmark-pdf"></i></div>
                        <h3>{{ __('Professional Invoicing') }}</h3>
                        <p>{{ __('Generate, print, and share beautiful A4 invoices for your customers instantly. Keep track of paid and unpaid invoices easily.') }}</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="800">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-shield-check"></i></div>
                        <h3>{{ __('Custom Warranties') }}</h3>
                        <p>{{ __('Issue professional digital warranty certificates to your customers with 10 customizable themes, complete with your shop\'s logo.') }}</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="900">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-receipt"></i></div>
                        <h3>{{ __('Digital Receipts') }}</h3>
                        <p>{{ __('Provide modern PDF receipts that can be downloaded or shared directly to customers via WhatsApp or Email, saving on paper costs.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="py-5 text-white" style="background-color: #0f172a;">
        <div class="container py-4">
            <div class="row text-center">
                <div class="col-md-3 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="100">
                    <h2 class="display-4 fw-bold text-success mb-0">150+</h2>
                    <p class="text-light mt-2 text-uppercase tracking-wider opacity-75">{{ __('Active Stores') }}</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="200">
                    <h2 class="display-4 fw-bold text-success mb-0">50K+</h2>
                    <p class="text-light mt-2 text-uppercase tracking-wider opacity-75">{{ __('Daily Transactions') }}</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="300">
                    <h2 class="display-4 fw-bold text-success mb-0">99.9%</h2>
                    <p class="text-light mt-2 text-uppercase tracking-wider opacity-75">{{ __('Uptime') }}</p>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="400">
                    <h2 class="display-4 fw-bold text-success mb-0">24/7</h2>
                    <p class="text-light mt-2 text-uppercase tracking-wider opacity-75">{{ __('Customer Support') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">{{ __('Loved by Business Owners') }}</h2>
                <p class="text-muted fs-5 mt-3">{{ __('Don\'t just take our word for it.') }}</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card position-relative h-100">
                        <i class="bi bi-quote quote-icon"></i>
                        <p>{{ __('"Switching to Z-pos was the best decision for our pharmacy chain. The multi-branch Inventory tracking is flawless and saved us millions in expired stock."') }}</p>
                        <div class="client-info">
                            <img src="data:image/jpeg;base64,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" alt="Client">
                            <div>
                                <h5>Yassir Zahor</h5>
                                <span>Owner, YZ Pharmacies</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card position-relative h-100">
                        <i class="bi bi-quote quote-icon"></i>
                        <p>{{ __('"The speed of the checkout screen is incredible. Even during peak evening hours, our queues move twice as fast as before. Highly recommended for supermarkets!"') }}</p>
                        <div class="client-info">
                            <img src="data:image/jpeg;base64,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" alt="Client">
                            <div>
                                <h5>Arrifai Muntasir</h5>
                                <span>Manager, AM Supermarket</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card position-relative h-100">
                        <i class="bi bi-quote quote-icon"></i>
                        <p>{{ __('"The detailed profit and loss reports finally gave me clarity on my wholesale business. I now know exactly which items are moving and which are tying up my capital."') }}</p>
                        <div class="client-info">
                            <img src="data:image/jpeg;base64,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" alt="Client">
                            <div>
                                <h5>Zakom Shop</h5>
                                <span>Director, Wholesale</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">{{ __('Simple, Transparent Pricing') }}</h2>
                <p class="text-muted fs-5 mt-3">{{ __('No hidden fees. Scale as you grow.') }}</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <!-- Basic -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing-card h-100">
                        <h4 class="fw-bold text-primary mb-3">{{ __('Starter') }}</h4>
                        <p class="text-muted">Perfect for single retail shops.</p>
                        <div class="price mt-4">TZS 15K<span>/mo</span> <br><small class="text-muted text-decoration-line-through fs-6">TZS 20K</small></div>
                        <ul class="mb-4">
                            <li><i class="bi bi-gift-fill text-success"></i> {{ __('7 Days Free Trial') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('1 Branch') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('2 Users') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Products') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Inventory Management') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Professional Invoicing') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Custom Warranties') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Advanced Analytics') }}</li>
                        </ul>
                        <a href="{{ route('register') }}?package=starter" class="btn btn-outline-primary w-100 py-2 fw-bold" style="border-radius: 50px;">{{ __('Get Started') }}</a>
                    </div>
                </div>
                
                <!-- Pro -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing-card popular h-100">
                        <div class="badge-popular">{{ __('MOST POPULAR') }}</div>
                        <h4 class="fw-bold text-success mb-3">{{ __('Professional') }}</h4>
                        <p class="text-muted">For growing multi-branch businesses.</p>
                        <div class="price mt-4">TZS 45K<span>/mo</span> <br><small class="text-muted text-decoration-line-through fs-6">TZS 50K</small></div>
                        <ul class="mb-4">
                            <li><i class="bi bi-gift-fill text-success"></i> {{ __('7 Days Free Trial') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Up to 5 Branches') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Users') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Products') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Inventory Management') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Professional Invoicing') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Custom Warranties') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Advanced Analytics') }}</li>
                        </ul>
                        <a href="{{ route('register') }}?package=professional" class="btn btn-success text-white w-100 py-2 fw-bold shadow" style="border-radius: 50px;">{{ __('Get Started') }}</a>
                    </div>
                </div>
                
                <!-- Enterprise -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="pricing-card h-100">
                        <h4 class="fw-bold text-primary mb-3">{{ __('Enterprise') }}</h4>
                        <p class="text-muted">Custom solutions for large chains.</p>
                        <div class="price mt-4">TZS 110K<span>/mo</span> <br><small class="text-muted text-decoration-line-through fs-6">TZS 130K</small></div>
                        <ul class="mb-4">
                            <li><i class="bi bi-gift-fill text-success"></i> {{ __('7 Days Free Trial') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Branches') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Users') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Products') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Inventory Management') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Professional Invoicing') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Custom Warranties') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Advanced Analytics') }}</li>
                        </ul>
                        <a href="{{ route('register') }}?package=enterprise" class="btn btn-outline-primary w-100 py-2 fw-bold" style="border-radius: 50px;">{{ __('Get Started') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 border border-primary">{{ __('Support & Help') }}</span>
                    <h2 class="fw-bold text-primary display-6 mb-4">{{ __('Frequently Asked Questions') }}</h2>
                    <p class="text-muted fs-5 mb-5">{{ __('Have questions? We\'re here to help you understand how Z-pos can transform your business.') }}</p>
                    
                    <div class="bg-light p-4 rounded-4 shadow-sm border border-white">
                        <h5 class="fw-bold text-dark mb-4">{{ __('Still have questions?') }}</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@z-pos.co.tz" target="_blank" class="btn btn-outline-primary rounded-pill py-2 px-3 d-flex align-items-center transition-all hover-shadow flex-grow-1 justify-content-center">
                                <i class="bi bi-envelope-fill me-2"></i> 
                                <span class="fw-bold">info@z-pos.co.tz</span>
                            </a>
                            <a href="https://wa.me/255683628142" target="_blank" class="btn btn-success rounded-pill py-2 px-3 d-flex align-items-center shadow-sm transition-all hover-shadow flex-grow-1 justify-content-center">
                                <i class="bi bi-whatsapp me-2"></i> 
                                <span class="fw-bold">{{ __('Chat on WhatsApp') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-primary bg-white p-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    {{ __('Do I need internet to use the POS?') }}
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body p-4 pt-0 text-muted bg-white">
                                    {{ __('Yes, Z-pos is a modern cloud-based system. This allows you to monitor sales and manage your business from anywhere, anytime via your phone or computer.') }}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-primary bg-white p-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    {{ __('What devices are supported?') }}
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body p-4 pt-0 text-muted bg-white">
                                    {{ __('Z-pos works on any device with internet access. You can use a Smartphone, PC/Laptop, or Tablet. You don\'t need to buy expensive hardware!') }}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-primary bg-white p-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    {{ __('How secure is my data?') }}
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body p-4 pt-0 text-muted bg-white">
                                    {{ __('Your data is 100% secure. The system stores data in the cloud so even if your phone or computer breaks, your data is safe. Also, every user has their own password.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Modal -->
    <div class="modal fade" id="demoVideoModal" tabindex="-1" aria-labelledby="demoVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 bg-dark text-white p-3">
                    <h5 class="modal-title fw-bold" id="demoVideoModalLabel">Z-pos System Demo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-dark">
                    <div class="ratio ratio-16x9">
                        <video id="demoVideoElement" controls preload="none" class="w-100 h-100" style="object-fit: cover;">
                            <source src="{{ asset('videos/demo.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PWA Install Button -->
    <button id="pwa-install-btn" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 9999; padding: 12px 24px; border-radius: 50px; background-color: #1e293b; color: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-weight: 600; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; transition: all 0.3s ease;">
        <i class="bi bi-phone"></i> Install App
    </button>

    <style>
        @media all and (display-mode: standalone) {
            #pwa-install-btn {
                display: none !important;
            }
        }
        #pwa-install-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            background-color: #0f172a;
        }
    </style>

    <script>
        let deferredPrompt = null;
        const installBtn = document.getElementById('pwa-install-btn');
        
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

        // ALWAYS show the button if they are viewing on a browser (not standalone app)
        if (!isStandalone) {
            installBtn.style.display = 'flex';
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
                if (outcome === 'accepted') {
                    installBtn.style.display = 'none';
                }
            } else if (isIOS) {
                alert("Ili ku-install kwenye iPhone:\n\n1. Bofya alama ya 'Share' (mshale unaoangalia juu) hapo chini.\n2. Shuka chini na uchague 'Add to Home Screen'.");
            } else {
                alert("Kivinjari chako bado hakijakamilisha kusoma mfumo, au App tayari imeshawekwa. \n\nKama unatumia Android, bofya vidoti vitatu (Menu) juu kulia na uchague 'Install App' au 'Add to Home Screen'.");
            }
        });

        window.addEventListener('appinstalled', () => {
            installBtn.style.display = 'none';
        });
    </script>
@endsection
