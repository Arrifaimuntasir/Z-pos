@extends('layouts.landing')

@section('content')

{{-- ========================================================
     DESIGN UPGRADE â€” Navy #0f172a Â· Accent #10b981 Â· Poppins
     HTML unchanged. CSS only. All sections preserved.
     ======================================================== --}}
<style>
/* ===== HERO ===== */
.hero-section {
    background: linear-gradient(150deg, #0f172a 0%, #0c1f30 55%, #082018 100%) !important;
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.hero-section::before {
    content: '';
    position: absolute;
    top: -150px; right: -150px;
    width: 600px; height: 600px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(16,185,129,0.10) 0%, transparent 70%);
    pointer-events: none;
}
.hero-section::after {
    content: '';
    position: absolute;
    bottom: -120px; left: -120px;
    width: 450px; height: 450px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(16,185,129,0.07) 0%, transparent 70%);
    pointer-events: none;
}
.hero-section h1 {
    color: #fff !important;
    font-size: clamp(2.2rem, 5vw, 3.8rem) !important;
    letter-spacing: -1.5px !important;
    line-height: 1.1 !important;
    font-weight: 800 !important;
}
.hero-section p.fs-5 { color: #94a3b8 !important; }
.hero-section .d-inline-flex.bg-success.bg-opacity-10 {
    background: rgba(16,185,129,0.15) !important;
    border: 1px solid rgba(16,185,129,0.3) !important;
    color: #34d399 !important;
}
.hero-section .btn-success {
    background: #10b981 !important;
    border-color: #10b981 !important;
    padding: 13px 28px !important;
    border-radius: 10px !important;
    font-weight: 700 !important;
    box-shadow: 0 4px 18px rgba(16,185,129,0.35) !important;
    transition: all .25s !important;
}
.hero-section .btn-success:hover {
    background: #059669 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(16,185,129,0.4) !important;
}
.hero-section .btn-outline-primary {
    border: 2px solid rgba(255,255,255,0.18) !important;
    color: #e2e8f0 !important;
    padding: 12px 24px !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    background: rgba(255,255,255,0.04) !important;
    transition: all .2s !important;
}
.hero-section .btn-outline-primary:hover {
    background: rgba(255,255,255,0.1) !important;
    color: #fff !important;
}
.hero-section .row.mt-5.pt-3 {
    border-top: 1px solid rgba(255,255,255,0.08) !important;
    padding-top: 24px !important;
}
.hero-section .row.mt-5.pt-3 h4 { color: #fff !important; font-weight: 800 !important; }
.hero-section .row.mt-5.pt-3 p   { color: #64748b !important; }
.hero-section .border-end { border-color: rgba(255,255,255,0.08) !important; }
.hero-section .hero-image {
    padding: 10px;
    background: rgba(255,255,255,0.03);
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.07);
    box-shadow: 0 32px 72px rgba(0,0,0,0.5);
}
.hero-section .hero-image img { border-radius: 10px !important; }

/* ===== PARTNERS ===== */
section.py-5.border-bottom.bg-white { padding: 30px 0 !important; }
section.py-5.border-bottom.bg-white h6 {
    font-size: 0.67rem;
    letter-spacing: 2px;
    color: #94a3b8;
    margin-bottom: 20px !important;
}
section.py-5.border-bottom.bg-white .col-4,
section.py-5.border-bottom.bg-white .col-md-2 {
    opacity: 0.85;
    transition: opacity .2s;
}
section.py-5.border-bottom.bg-white .col-4:hover,
section.py-5.border-bottom.bg-white .col-md-2:hover {
    opacity: 1;
}

/* ===== FEATURES ===== */
#features { padding: 80px 0 !important; }
#features .text-center.mb-5 { margin-bottom: 40px !important; }
.feature-box {
    padding: 28px 24px !important;
    border-radius: 14px !important;
    border: 1px solid #f1f5f9 !important;
    box-shadow: none !important;
    transition: all .25s ease !important;
    position: relative;
    overflow: hidden;
}
.feature-box::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #10b981, #059669);
    opacity: 0;
    transition: opacity .25s;
}
.feature-box:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 16px 40px rgba(16,185,129,0.10) !important;
    border-color: rgba(16,185,129,0.3) !important;
}
.feature-box:hover::after { opacity: 1; }
.feature-box .icon-wrapper {
    width: 52px !important; height: 52px !important;
    border-radius: 12px !important;
    font-size: 1.5rem !important;
    margin-bottom: 18px !important;
    background: rgba(16,185,129,0.1) !important;
    color: #10b981 !important;
}
.feature-box h3 { font-size: 1rem !important; color: #0f172a !important; margin-bottom: 8px !important; }
.feature-box p  { font-size: 0.84rem !important; line-height: 1.7 !important; }

/* ===== STATISTICS ===== */
section.py-5.text-white {
    background: linear-gradient(135deg, #0f172a 0%, #0c1f30 100%) !important;
    padding: 72px 0 !important;
    position: relative; overflow: hidden;
}
section.py-5.text-white::before {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 600px; height: 200px;
    background: radial-gradient(ellipse, rgba(16,185,129,0.1) 0%, transparent 70%);
}
section.py-5.text-white h2.display-4 {
    font-size: clamp(2rem, 4.5vw, 2.8rem) !important;
    font-weight: 900 !important;
    color: #10b981 !important;
    margin-bottom: 6px !important;
}
section.py-5.text-white p {
    font-size: 0.7rem !important;
    letter-spacing: 1.5px;
    color: #64748b !important;
    text-transform: uppercase !important;
}

/* ===== TESTIMONIALS ===== */
#testimonials { padding: 80px 0 !important; }
#testimonials h2 { font-weight: 800 !important; letter-spacing: -0.5px; }
.testimonial-card {
    background: #f8fafc !important;
    border: 1px solid #f1f5f9 !important;
    border-radius: 16px !important;
    padding: 24px !important;
    box-shadow: none !important;
    transition: all .22s !important;
}
.testimonial-card:hover {
    box-shadow: 0 10px 32px rgba(0,0,0,0.07) !important;
    transform: translateY(-4px);
}
.testimonial-card p  { font-size: 0.87rem !important; line-height: 1.75 !important; }
.testimonial-card .client-info img { border: 2px solid #10b981 !important; }
.testimonial-card .quote-icon { color: rgba(16,185,129,0.18) !important; }

/* ===== PRICING ===== */
#pricing { padding: 80px 0 !important; }
#pricing h2 { font-weight: 800 !important; letter-spacing: -0.5px; }
.pricing-card {
    border-radius: 18px !important;
    box-shadow: none !important;
    border: 1.5px solid #e2e8f0 !important;
    transition: all .25s !important;
}
.pricing-card:not(.popular):hover {
    border-color: rgba(16,185,129,0.4) !important;
    box-shadow: 0 12px 40px rgba(16,185,129,0.09) !important;
    transform: translateY(-3px);
}
.pricing-card.popular {
    border-color: #10b981 !important;
    box-shadow: 0 12px 40px rgba(16,185,129,0.15) !important;
}
.pricing-card .price { font-size: 2.6rem !important; font-weight: 900 !important; }
.pricing-card ul li { font-size: 0.84rem !important; margin-bottom: 10px !important; }
.pricing-card .btn-outline-primary {
    border: 2px solid #0f172a !important;
    color: #0f172a !important;
    border-radius: 50px !important;
    font-weight: 700 !important;
    transition: all .2s !important;
}
.pricing-card .btn-outline-primary:hover {
    background: #0f172a !important;
    color: #fff !important;
}
.pricing-card.popular .btn-success {
    background: #10b981 !important;
    border-color: #10b981 !important;
    font-weight: 700 !important;
    border-radius: 50px !important;
    box-shadow: 0 4px 14px rgba(16,185,129,0.3) !important;
}
.pricing-card.popular .btn-success:hover {
    background: #059669 !important;
    transform: translateY(-1px);
}

/* ===== FAQ ===== */
.accordion-button:not(.collapsed) {
    color: #10b981 !important;
    background: #f0fdf4 !important;
    box-shadow: none !important;
}
.accordion-button:focus { box-shadow: none !important; }
.accordion-item {
    border-radius: 14px !important;
    overflow: hidden;
    margin-bottom: 10px !important;
}

/* ===== HEADINGS GLOBAL ===== */
.display-5 { font-weight: 800 !important; letter-spacing: -0.5px; }
.display-6 { font-weight: 800 !important; letter-spacing: -0.5px; }

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .hero-section { min-height: auto !important; }
    .hero-section .hero-image { margin-top: 36px; }
}
@media (max-width: 576px) {
    .hero-section h1 { font-size: 2.1rem !important; }
    .hero-section .d-flex.flex-wrap.gap-3 { flex-direction: column; }
    .hero-section .d-flex.flex-wrap.gap-3 .btn { width: 100%; text-align: center; }
    .pricing-card.popular { transform: scale(1) !important; }
}
</style>

    <script>
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
            document.write('<style>body { display: none !important; }</style>');
            if (document.cookie.indexOf('pwa_onboarding_seen=true') !== -1 || localStorage.getItem('pwa_onboarding_seen') === 'true') {
                window.location.replace("{{ route('login') }}");
            } else {
                window.location.replace("{{ route('pwa.onboarding') }}");
            }
        }
    </script>
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
                    <h5 class="fw-bold mb-2 text-dark">{{ __('Azam') }}</h5>
                    <img src="{{ asset('images/azamtv.png') }}" alt="Azam" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <h5 class="fw-bold mb-2 text-dark">{{ __('Vodacom') }}</h5>
                    <img src="{{ asset('images/images.jfif') }}" alt="Vodacom" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <h5 class="fw-bold mb-2 text-dark">{{ __('CRDB') }}</h5>
                    <img src="{{ asset('images/images.png') }}" alt="CRDB" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="400">
                    <h5 class="fw-bold mb-2 text-dark">{{ __('Shoppers') }}</h5>
                    <img src="{{ asset('images/images (1).png') }}" alt="Shoppers" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="500">
                    <h5 class="fw-bold mb-2 text-dark">{{ __('Yas') }}</h5>
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
            

            {{-- ===== Dynamic DB Testimonials (from Rate Us) ===== --}}
            @if(isset($dbTestimonials) && $dbTestimonials->count() > 0)
            <div class="row g-4 mt-2" id="db-testimonials-visible">
                @foreach($dbTestimonials->take(3) as $t)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="testimonial-card position-relative h-100">
                        <i class="bi bi-quote quote-icon"></i>
                        <div class="mb-2" style="color:#f59e0b;font-size:.85rem;letter-spacing:2px;">
                            @for($i=1;$i<=5;$i++)<i class="bi {{ $i <= $t->rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size:.8rem;"></i>@endfor
                        </div>
                        <p>&ldquo;{{ $t->quote }}&rdquo;</p>
                        <div class="client-info d-flex align-items-center gap-3 mt-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white flex-shrink-0"
                                 style="width:44px;height:44px;font-size:.9rem;border:2px solid #10b981;
                                        background:{{ match($t->avatar_color ?? 'primary') { 'success'=>'#10b981','warning'=>'#f59e0b','dark'=>'#0f172a',default=>'#3b82f6' } }};">
                                {{ $t->avatar_initials ?? 'U' }}
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="font-size:.9rem;">{{ $t->name }}</h5>
                                <span class="text-muted" style="font-size:.75rem;">{{ $t->position }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($dbTestimonials->count() > 3)
            {{-- Hidden extra testimonials --}}
            <div class="row g-4 mt-2 d-none" id="db-testimonials-more">
                @foreach($dbTestimonials->skip(3) as $t)
                <div class="col-md-4" data-aos="fade-up">
                    <div class="testimonial-card position-relative h-100">
                        <i class="bi bi-quote quote-icon"></i>
                        <div class="mb-2" style="color:#f59e0b;font-size:.85rem;letter-spacing:2px;">
                            @for($i=1;$i<=5;$i++)<i class="bi {{ $i <= $t->rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size:.8rem;"></i>@endfor
                        </div>
                        <p>&ldquo;{{ $t->quote }}&rdquo;</p>
                        <div class="client-info d-flex align-items-center gap-3 mt-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white flex-shrink-0"
                                 style="width:44px;height:44px;font-size:.9rem;border:2px solid #10b981;
                                        background:{{ match($t->avatar_color ?? 'primary') { 'success'=>'#10b981','warning'=>'#f59e0b','dark'=>'#0f172a',default=>'#3b82f6' } }};">
                                {{ $t->avatar_initials ?? 'U' }}
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="font-size:.9rem;">{{ $t->name }}</h5>
                                <span class="text-muted" style="font-size:.75rem;">{{ $t->position }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- View More / Show Less Button --}}
            <div class="text-center mt-5" id="view-more-wrap">
                <button onclick="toggleMoreTestimonials()" id="view-more-btn"
                    class="btn px-5 py-2 fw-bold rounded-pill"
                    style="border:2px solid #0f172a;color:#0f172a;background:transparent;transition:all .2s;font-size:.88rem;">
                    <i class="bi bi-chevron-down me-2" id="view-more-icon"></i>
                    <span id="view-more-label">{{ __('Tazama Zaidi') }} ({{ $dbTestimonials->count() - 3 }})</span>
                </button>
            </div>
            <script>
            function toggleMoreTestimonials() {
                const more  = document.getElementById('db-testimonials-more');
                const icon  = document.getElementById('view-more-icon');
                const label = document.getElementById('view-more-label');
                const isHidden = more.classList.contains('d-none');
                if (isHidden) {
                    more.classList.remove('d-none');
                    icon.classList.replace('bi-chevron-down','bi-chevron-up');
                    label.textContent = '{{ __('Onyesha Pungufu') }}';
                } else {
                    more.classList.add('d-none');
                    icon.classList.replace('bi-chevron-up','bi-chevron-down');
                    label.textContent = '{{ __('Tazama Zaidi') }} ({{ $dbTestimonials->count() - 3 }})';
                    document.getElementById('db-testimonials-visible').scrollIntoView({behavior:'smooth',block:'start'});
                }
            }
            </script>
            @endif
            @endif

        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">{{ __('Simple, Transparent Pricing') }}</h2>
                <p class="text-muted fs-5 mt-3">{{ __('No hidden fees. Scale as you grow.') }}</p>

                {{-- Monthly / Yearly Toggle --}}
                <div class="d-inline-flex align-items-center gap-3 mt-4 bg-white px-4 py-2 rounded-pill shadow-sm border" style="border-color:#e2e8f0!important;">
                    <span id="lbl-monthly" class="fw-700 text-dark" style="font-size:.88rem;font-weight:700;">{{ __('Monthly') }}</span>
                    <div class="form-check form-switch mb-0" style="padding:0;">
                        <input class="form-check-input m-0" type="checkbox" id="billingToggle"
                               style="width:48px;height:26px;cursor:pointer;background-color:#10b981;border-color:#10b981;">
                    </div>
                    <span id="lbl-yearly" class="fw-700" style="font-size:.88rem;font-weight:700;color:#94a3b8;">
                        {{ __('Yearly') }}
                        <span class="badge ms-1 rounded-pill" style="background:#d1fae5;color:#059669;font-size:.65rem;padding:3px 8px;">
                            {{ __('Save 2 months') }}
                        </span>
                    </span>
                </div>
            </div>

            <div class="row g-4 justify-content-center">

                {{-- ===== STARTER ===== --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="pc-card h-100">
                        <div class="pc-plan">{{ __('Starter') }}</div>
                        <div class="pc-desc">{{ __('Perfect for single retail shops.') }}</div>
                        <div class="pc-price-wrap">
                            <div class="pc-price">
                                <span class="pc-currency">TSh</span>
                                <span class="pc-amount" data-monthly="15,000" data-yearly="150,000">15,000</span>
                                <span class="pc-period">/<span class="pc-per">{{ __('mo') }}</span></span>
                            </div>
                            <div class="pc-old">
                                <span class="monthly-old">{{ __('Was TSh 20,000/mo') }}</span>
                                <span class="yearly-old d-none">{{ __('Was TSh 200,000/yr') }}</span>
                            </div>
                        </div>
                        <hr class="pc-divider">
                        <ul class="pc-features">
                            <li><i class="bi bi-gift-fill" style="color:#f59e0b;"></i> {{ __('7 Days Free Trial') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('1 Branch') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('2 Users') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Products') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Inventory Management') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Professional Invoicing') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Custom Warranties') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Advanced Analytics') }}</li>
                        </ul>
                        <a href="{{ route('register') }}?package=starter" class="pc-btn pc-btn-outline">{{ __('Get Started') }}</a>
                    </div>
                </div>

                {{-- ===== PROFESSIONAL ===== --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="pc-card pc-popular h-100">
                        <div class="pc-badge">{{ __('MOST POPULAR') }}</div>
                        <div class="pc-plan" style="color:#10b981;">{{ __('Professional') }}</div>
                        <div class="pc-desc">{{ __('For growing multi-branch businesses.') }}</div>
                        <div class="pc-price-wrap">
                            <div class="pc-price">
                                <span class="pc-currency">TSh</span>
                                <span class="pc-amount" data-monthly="45,000" data-yearly="450,000">45,000</span>
                                <span class="pc-period">/<span class="pc-per">{{ __('mo') }}</span></span>
                            </div>
                            <div class="pc-old">
                                <span class="monthly-old">{{ __('Was TSh 50,000/mo') }}</span>
                                <span class="yearly-old d-none">{{ __('Was TSh 600,000/yr') }}</span>
                            </div>
                        </div>
                        <hr class="pc-divider">
                        <ul class="pc-features">
                            <li><i class="bi bi-gift-fill" style="color:#f59e0b;"></i> {{ __('7 Days Free Trial') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Up to 5 Branches') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Users') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Products') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Inventory Management') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Professional Invoicing') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Custom Warranties') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Advanced Analytics') }}</li>
                        </ul>
                        <a href="{{ route('register') }}?package=professional" class="pc-btn pc-btn-filled">{{ __('Get Started') }}</a>
                    </div>
                </div>

                {{-- ===== ENTERPRISE ===== --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="pc-card h-100">
                        <div class="pc-plan">{{ __('Enterprise') }}</div>
                        <div class="pc-desc">{{ __('Custom solutions for large chains.') }}</div>
                        <div class="pc-price-wrap">
                            <div class="pc-price">
                                <span class="pc-currency">TSh</span>
                                <span class="pc-amount" data-monthly="110,000" data-yearly="1,100,000">110,000</span>
                                <span class="pc-period">/<span class="pc-per">{{ __('mo') }}</span></span>
                            </div>
                            <div class="pc-old">
                                <span class="monthly-old">{{ __('Was TSh 130,000/mo') }}</span>
                                <span class="yearly-old d-none">{{ __('Was TSh 1,560,000/yr') }}</span>
                            </div>
                        </div>
                        <hr class="pc-divider">
                        <ul class="pc-features">
                            <li><i class="bi bi-gift-fill" style="color:#f59e0b;"></i> {{ __('7 Days Free Trial') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Branches') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Users') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Products') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Inventory Management') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Professional Invoicing') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Custom Warranties') }}</li>
                            <li><i class="bi bi-check-circle-fill"></i> {{ __('Advanced Analytics') }}</li>
                        </ul>
                        <a href="{{ route('register') }}?package=enterprise" class="pc-btn pc-btn-outline">{{ __('Get Started') }}</a>
                    </div>
                </div>

            </div>{{-- /.row --}}
        </div>
    </section>

    {{-- ===== PRICING STYLES + TOGGLE SCRIPT ===== --}}
    <style>
    /* Pricing Cards */
    .pc-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        padding: 32px 28px;
        display: flex;
        flex-direction: column;
        transition: all .25s;
        position: relative;
    }
    .pc-card:not(.pc-popular):hover {
        border-color: rgba(16,185,129,.4);
        box-shadow: 0 14px 40px rgba(16,185,129,.09);
        transform: translateY(-4px);
    }
    .pc-popular {
        border: 2px solid #10b981 !important;
        box-shadow: 0 14px 40px rgba(16,185,129,.15);
        background: linear-gradient(180deg, rgba(16,185,129,.04) 0%, #fff 35%);
    }
    .pc-badge {
        position: absolute;
        top: -14px; left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: #fff;
        font-size: .67rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 5px 18px;
        border-radius: 100px;
        white-space: nowrap;
    }
    .pc-plan {
        font-size: .72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #64748b;
        margin-bottom: 4px;
    }
    .pc-desc { font-size: .84rem; color: #94a3b8; margin-bottom: 20px; }
    .pc-price-wrap { margin-bottom: 4px; }
    .pc-price {
        display: flex;
        align-items: baseline;
        gap: 3px;
        line-height: 1;
    }
    .pc-currency { font-size: .9rem; font-weight: 700; color: #0f172a; margin-top: 6px; }
    .pc-amount   { font-size: 2.5rem; font-weight: 900; color: #0f172a; }
    .pc-period   { font-size: .85rem; color: #94a3b8; font-weight: 500; }
    .pc-old      { font-size: .72rem; color: #94a3b8; text-decoration: line-through; min-height: 18px; margin-top: 4px; }
    .pc-divider  { border-color: #f1f5f9; margin: 18px 0; }
    .pc-features { list-style: none; padding: 0; margin: 0 0 24px; flex: 1; }
    .pc-features li {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: .83rem;
        color: #374151;
        padding: 6px 0;
        border-bottom: 1px solid #f8fafc;
    }
    .pc-features li:last-child { border-bottom: none; }
    .pc-features li i { color: #10b981; font-size: .9rem; flex-shrink: 0; }
    .pc-btn {
        display: block;
        text-align: center;
        padding: 12px;
        border-radius: 50px;
        font-weight: 700;
        font-size: .88rem;
        text-decoration: none;
        transition: all .2s;
        margin-top: auto;
    }
    .pc-btn-outline {
        border: 2px solid #0f172a;
        color: #0f172a;
        background: transparent;
    }
    .pc-btn-outline:hover { background: #0f172a; color: #fff; }
    .pc-btn-filled {
        background: #10b981;
        color: #fff;
        border: none;
        box-shadow: 0 4px 14px rgba(16,185,129,.35);
    }
    .pc-btn-filled:hover { background: #059669; color: #fff; transform: translateY(-1px); }

    /* Toggle switch green */
    #billingToggle { background-color: #10b981 !important; border-color: #10b981 !important; }
    #billingToggle:checked { background-color: #10b981 !important; }

    @media (max-width: 768px) {
        .pc-card { padding: 24px 20px; }
        .pc-amount { font-size: 2rem; }
        .pc-popular { transform: none !important; }
    }
    </style>

    <script>
    (function() {
        const toggle   = document.getElementById('billingToggle');
        const lblMo    = document.getElementById('lbl-monthly');
        const lblYr    = document.getElementById('lbl-yearly');
        const amounts  = document.querySelectorAll('.pc-amount');
        const perSpans = document.querySelectorAll('.pc-per');
        const moOlds   = document.querySelectorAll('.monthly-old');
        const yrOlds   = document.querySelectorAll('.yearly-old');

        function update() {
            const isYearly = toggle.checked;
            // amounts
            amounts.forEach(el => {
                el.textContent = isYearly ? el.dataset.yearly : el.dataset.monthly;
            });
            // period label
            perSpans.forEach(el => el.textContent = isYearly ? '{{ __('yr') }}' : '{{ __('mo') }}');
            // old prices
            moOlds.forEach(el => el.classList.toggle('d-none', isYearly));
            yrOlds.forEach(el => el.classList.toggle('d-none', !isYearly));
            // labels style
            lblMo.style.color = isYearly ? '#94a3b8' : '#0f172a';
            lblYr.querySelector('span:not(.badge)') && (lblYr.style.color = isYearly ? '#0f172a' : '#94a3b8');
            lblYr.style.color = isYearly ? '#0f172a' : '#94a3b8';
        }
        toggle && toggle.addEventListener('change', update);
    })();
    </script>

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
                    <h5 class="modal-title fw-bold" id="demoVideoModalLabel">{{ __('Z-pos System Demo') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-dark">
                    <div class="ratio ratio-16x9">
                        <video id="demoVideoElement" controls preload="none" class="w-100 h-100" style="object-fit: cover;">
                            <source src="{{ asset('videos/demo.mp4') }}" type="video/mp4">
                            {{ __('Your browser does not support the video tag.') }}
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PWA Install Button -->
    <button id="pwa-install-btn" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 9999; padding: 12px 24px; border-radius: 50px; background-color: #1e293b; color: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-weight: 600; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; transition: all 0.3s ease;">
        <i class="bi bi-phone"></i> {{ __('Install App') }}
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

        // Hide button by default, only show when browser is ready to install
        installBtn.style.display = 'none';

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            // Show the button now that we have the native prompt ready
            if (!isStandalone) {
                installBtn.style.display = 'flex';
            }
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
            }
        });

        window.addEventListener('appinstalled', () => {
            installBtn.style.display = 'none';
        });
    </script>
@endsection

