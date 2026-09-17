@extends('layouts.landing')

@section('content')
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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
* { font-family: 'Inter', sans-serif; }

/* ============================
   GLOBAL & UTILITIES
   ============================ */
:root {
    --primary: #4f46e5;
    --primary-dark: #3730a3;
    --secondary: #10b981;
    --dark: #0f172a;
    --dark-2: #1e293b;
    --muted: #64748b;
    --light-bg: #f8fafc;
    --border: #e2e8f0;
    --radius: 16px;
}

section { position: relative; }

.badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: .3px;
    text-transform: uppercase;
}

.btn-primary-custom {
    background: var(--primary);
    color: #fff;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.92rem;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all .25s;
    box-shadow: 0 4px 20px rgba(79,70,229,0.35);
}
.btn-primary-custom:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(79,70,229,0.45);
    color: #fff;
}

.btn-ghost {
    background: transparent;
    color: var(--dark);
    padding: 13px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.92rem;
    border: 2px solid var(--border);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all .2s;
}
.btn-ghost:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: #ede9fe;
}

.section-label {
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--primary);
    display: block;
    margin-bottom: 12px;
}
.section-title {
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    font-weight: 800;
    color: var(--dark);
    line-height: 1.15;
    letter-spacing: -0.5px;
    margin-bottom: 14px;
}
.section-sub {
    font-size: 1rem;
    color: var(--muted);
    max-width: 580px;
    line-height: 1.7;
}

/* ============================
   HERO
   ============================ */
.hero-wrap {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
    min-height: 100vh;
    padding: 130px 0 80px;
    overflow: hidden;
    position: relative;
}
.hero-wrap::before {
    content: '';
    position: absolute;
    top: -200px; right: -200px;
    width: 700px; height: 700px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(79,70,229,0.25) 0%, transparent 70%);
    pointer-events: none;
}
.hero-wrap::after {
    content: '';
    position: absolute;
    bottom: -150px; left: -150px;
    width: 500px; height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%);
    pointer-events: none;
}

.hero-badge {
    background: rgba(79,70,229,0.2);
    border: 1px solid rgba(79,70,229,0.4);
    color: #a5b4fc;
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 28px;
}
.hero-badge .dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #10b981;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.hero-title {
    font-size: clamp(2.4rem, 6vw, 4rem);
    font-weight: 900;
    color: #fff;
    line-height: 1.08;
    letter-spacing: -1.5px;
    margin-bottom: 20px;
}
.hero-title .accent {
    background: linear-gradient(135deg, #818cf8, #34d399);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-sub {
    font-size: 1.05rem;
    color: #94a3b8;
    max-width: 500px;
    line-height: 1.75;
    margin-bottom: 36px;
}

.hero-btns { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 48px; }

.hero-stats {
    display: flex;
    gap: 32px;
    flex-wrap: wrap;
    padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,0.08);
}
.hero-stat-value {
    font-size: 1.4rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 2px;
}
.hero-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 500; }

/* Dashboard mockup */
.hero-mockup {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px;
    padding: 16px;
    backdrop-filter: blur(10px);
    box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
    position: relative;
}
.hero-mockup::before {
    content: '';
    position: absolute;
    top: -1px; left: 20%; right: 20%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(79,70,229,0.8), transparent);
}
.mockup-bar {
    display: flex;
    gap: 6px;
    margin-bottom: 14px;
    align-items: center;
}
.mockup-dot { width: 10px; height: 10px; border-radius: 50%; }
.mockup-img { border-radius: 10px; width: 100%; display: block; }

/* ============================
   PARTNERS
   ============================ */
.partners-wrap {
    background: #fff;
    padding: 40px 0;
    border-bottom: 1px solid var(--border);
}
.partners-label { font-size: 0.72rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; text-align: center; margin-bottom: 24px; }
.partners-grid { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 32px 48px; }
.partner-logo { opacity: 0.45; transition: opacity .2s; display: flex; align-items: center; gap: 8px; }
.partner-logo:hover { opacity: 0.8; }
.partner-logo img { height: 32px; object-fit: contain; }
.partner-logo span { font-weight: 800; font-size: 1rem; color: #334155; }

/* ============================
   FEATURES
   ============================ */
.features-wrap { background: var(--light-bg); padding: 100px 0; }

.feature-card {
    background: #fff;
    border-radius: var(--radius);
    padding: 28px;
    border: 1px solid var(--border);
    height: 100%;
    transition: all .25s;
    position: relative;
    overflow: hidden;
}
.feature-card:hover {
    border-color: var(--primary);
    box-shadow: 0 8px 32px rgba(79,70,229,0.1);
    transform: translateY(-3px);
}
.feature-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    opacity: 0;
    transition: opacity .25s;
}
.feature-card:hover::after { opacity: 1; }

.feature-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 18px;
}
.feature-title { font-size: 1rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
.feature-desc { font-size: 0.85rem; color: var(--muted); line-height: 1.7; margin: 0; }

/* icon color variants */
.fi-purple { background: #ede9fe; color: #7c3aed; }
.fi-green  { background: #d1fae5; color: #059669; }
.fi-blue   { background: #dbeafe; color: #2563eb; }
.fi-orange { background: #fef3c7; color: #d97706; }
.fi-red    { background: #fee2e2; color: #dc2626; }
.fi-teal   { background: #cffafe; color: #0891b2; }
.fi-pink   { background: #fce7f3; color: #db2777; }
.fi-indigo { background: #e0e7ff; color: #4338ca; }

/* ============================
   STATS
   ============================ */
.stats-wrap {
    background: linear-gradient(135deg, #0f172a, #1e1b4b);
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}
.stats-wrap::before {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 600px; height: 300px;
    background: radial-gradient(ellipse, rgba(79,70,229,0.2) 0%, transparent 70%);
}
.stat-item { text-align: center; }
.stat-number {
    font-size: clamp(2.2rem, 5vw, 3rem);
    font-weight: 900;
    background: linear-gradient(135deg, #818cf8, #34d399);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 8px;
}
.stat-label { font-size: 0.78rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

/* ============================
   TESTIMONIALS
   ============================ */
.testimonials-wrap { background: #fff; padding: 100px 0; }

.testi-card {
    background: var(--light-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 28px;
    height: 100%;
    transition: all .2s;
    position: relative;
}
.testi-card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    transform: translateY(-3px);
}
.testi-stars { color: #f59e0b; font-size: 0.9rem; margin-bottom: 14px; }
.testi-quote { font-size: 0.88rem; color: #374151; line-height: 1.75; margin-bottom: 20px; font-style: italic; }
.testi-author { display: flex; align-items: center; gap: 12px; }
.testi-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--primary);
}
.testi-name { font-size: 0.85rem; font-weight: 700; color: var(--dark); }
.testi-role { font-size: 0.72rem; color: var(--muted); }

/* ============================
   PRICING
   ============================ */
.pricing-wrap { background: var(--light-bg); padding: 100px 0; }

.price-card {
    background: #fff;
    border: 2px solid var(--border);
    border-radius: 20px;
    padding: 32px 28px;
    height: 100%;
    transition: all .25s;
    position: relative;
}
.price-card:hover { box-shadow: 0 12px 40px rgba(0,0,0,0.08); transform: translateY(-4px); }
.price-card.featured {
    border-color: var(--primary);
    background: linear-gradient(180deg, #ede9fe 0%, #fff 40%);
    box-shadow: 0 12px 40px rgba(79,70,229,0.15);
}

.price-badge {
    position: absolute;
    top: -14px; left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, var(--primary), #7c3aed);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 5px 16px;
    border-radius: 100px;
    white-space: nowrap;
}

.price-name { font-size: 0.8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
.price-amount { font-size: 2.4rem; font-weight: 900; color: var(--dark); line-height: 1; }
.price-amount small { font-size: 0.9rem; font-weight: 500; color: var(--muted); }
.price-old { font-size: 0.8rem; color: var(--muted); text-decoration: line-through; margin-bottom: 20px; }
.price-divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
.price-feature {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.83rem;
    color: #374151;
    margin-bottom: 10px;
}
.price-feature i { color: var(--secondary); font-size: 0.9rem; flex-shrink: 0; }
.price-feature .gift { color: #f59e0b; }

.btn-price {
    display: block;
    text-align: center;
    padding: 13px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.88rem;
    text-decoration: none;
    transition: all .2s;
    margin-top: 24px;
}
.btn-price-outline {
    border: 2px solid var(--primary);
    color: var(--primary);
    background: transparent;
}
.btn-price-outline:hover { background: var(--primary); color: #fff; }
.btn-price-filled {
    background: linear-gradient(135deg, var(--primary), #7c3aed);
    color: #fff;
    border: none;
    box-shadow: 0 4px 16px rgba(79,70,229,0.35);
}
.btn-price-filled:hover { opacity: 0.92; color: #fff; transform: translateY(-1px); }

/* ============================
   FAQ
   ============================ */
.faq-wrap { background: #fff; padding: 80px 0; }
.faq-item {
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 10px;
    overflow: hidden;
    transition: border-color .2s;
}
.faq-item:hover { border-color: var(--primary); }
.faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--dark);
    background: #fff;
    border: none;
    width: 100%;
    text-align: left;
    gap: 12px;
}
.faq-icon {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--light-bg);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    color: var(--primary);
    transition: transform .3s;
}
.faq-answer {
    padding: 0 22px 18px;
    font-size: 0.85rem;
    color: var(--muted);
    line-height: 1.75;
    display: none;
}
.faq-item.open .faq-icon { transform: rotate(45deg); background: #ede9fe; }
.faq-item.open .faq-answer { display: block; }

/* ============================
   CTA BOTTOM
   ============================ */
.cta-wrap {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.cta-wrap::before {
    content: '';
    position: absolute;
    top: -100px; right: -100px;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}
.cta-wrap::after {
    content: '';
    position: absolute;
    bottom: -80px; left: -80px;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}
.cta-title { font-size: clamp(1.6rem, 4vw, 2.4rem); font-weight: 900; color: #fff; margin-bottom: 14px; }
.cta-sub { color: rgba(255,255,255,0.7); font-size: 1rem; margin-bottom: 32px; }

.btn-cta-white {
    background: #fff;
    color: var(--primary);
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all .2s;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.btn-cta-white:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.25); color: var(--primary); }

/* ============================
   RESPONSIVE
   ============================ */
@media (max-width: 991px) {
    .hero-wrap { padding: 110px 0 60px; }
    .hero-mockup { margin-top: 40px; }
    .hero-stats { gap: 20px; }
    .features-wrap, .testimonials-wrap, .pricing-wrap { padding: 70px 0; }
}
@media (max-width: 576px) {
    .hero-wrap { padding: 100px 0 50px; min-height: unset; }
    .hero-title { letter-spacing: -0.5px; }
    .hero-btns { flex-direction: column; }
    .hero-btns a, .hero-btns button { width: 100%; justify-content: center; }
    .hero-stats { gap: 16px; }
    .partners-grid { gap: 20px 32px; }
    .stats-wrap { padding: 60px 0; }
    .cta-wrap { padding: 60px 0; }
}
</style>

{{-- ===========================
     HERO SECTION
     =========================== --}}
<section class="hero-wrap">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="900">
                <div class="hero-badge">
                    <span class="dot"></span>
                    Live · Mobile + Web · Built for Tanzania
                </div>
                <h1 class="hero-title">
                    {!! __('Run your shop <br> from your pocket.') !!}
                </h1>
                <p class="hero-sub">
                    {{ __('A complete modern system for managing sales, inventory, and profits for all retail and wholesale shops. Digitize your business with Z-pos.') }}
                </p>
                <div class="hero-btns">
                    <a href="{{ route('register') }}" class="btn-primary-custom">
                        <i class="bi bi-rocket-takeoff-fill"></i>
                        {{ __('Start free trial') }}
                    </a>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#demoVideoModal" class="btn-ghost">
                        <i class="bi bi-play-circle-fill" style="color:#818cf8;"></i>
                        {{ __('Watch 10-sec demo') }}
                    </button>
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-value">150+</div>
                        <div class="hero-stat-label">{{ __('shops onboarded') }}</div>
                    </div>
                    <div>
                        <div class="hero-stat-value">TSh 120M+</div>
                        <div class="hero-stat-label">{{ __('processed monthly') }}</div>
                    </div>
                    <div>
                        <div class="hero-stat-value">4.8 ⭐</div>
                        <div class="hero-stat-label">{{ __('User Rating') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="900" data-aos-delay="200">
                <div class="hero-mockup">
                    <div class="mockup-bar">
                        <div class="mockup-dot" style="background:#ef4444;"></div>
                        <div class="mockup-dot" style="background:#f59e0b;"></div>
                        <div class="mockup-dot" style="background:#10b981;"></div>
                        <div style="flex:1; background:rgba(255,255,255,0.07); height:8px; border-radius:4px; margin-left:8px;"></div>
                    </div>
                    <img src="{{ asset('images/hero_pos2.jfif') }}" alt="Z-pos POS Interface" class="mockup-img">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===========================
     PARTNERS
     =========================== --}}
<section class="partners-wrap">
    <div class="container">
        <div class="partners-label">{{ __('Trusted by Industry Leaders') }}</div>
        <div class="partners-grid">
            <div class="partner-logo"><img src="{{ asset('images/azamtv.png') }}" alt="Azam"><span>Azam</span></div>
            <div class="partner-logo"><img src="{{ asset('images/images.jfif') }}" alt="Vodacom"><span>Vodacom</span></div>
            <div class="partner-logo"><img src="{{ asset('images/images.png') }}" alt="CRDB"><span>CRDB</span></div>
            <div class="partner-logo"><img src="{{ asset('images/images (1).png') }}" alt="Shoppers"><span>Shoppers</span></div>
            <div class="partner-logo"><img src="{{ asset('images/Yas_Tanzania.svg') }}" alt="Yas"><span>Yas</span></div>
        </div>
    </div>
</section>

{{-- ===========================
     FEATURES
     =========================== --}}
<section class="features-wrap" id="features">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">{{ __('Core Features') }}</span>
            <h2 class="section-title">{{ __('Everything you need to scale') }}</h2>
            <p class="section-sub mx-auto">{{ __("From single shops to nationwide chains, we've got you covered.") }}</p>
        </div>
        <div class="row g-3">
            @php
            $features = [
                ['icon'=>'bi-upc-scan','color'=>'fi-purple','title'=>'Lightning Fast POS','desc'=>'Process sales in seconds using barcode scanners, shortcuts, and an intuitive touch-friendly interface designed for speed.'],
                ['icon'=>'bi-box-seam','color'=>'fi-green','title'=>'Smart Inventory','desc'=>'Track stock across multiple branches in real-time. Get low-stock alerts, manage expiry dates, and handle seamless transfers.'],
                ['icon'=>'bi-graph-up-arrow','color'=>'fi-blue','title'=>'Advanced Analytics','desc'=>'Make data-driven decisions with detailed reports on daily sales, profit margins, employee performance, and top-selling items.'],
                ['icon'=>'bi-shield-check','color'=>'fi-teal','title'=>'Enterprise Security','desc'=>'Role-based access control ensures staff only see what they need to. Activity logs track every void, discount, and deletion.'],
                ['icon'=>'bi-credit-card','color'=>'fi-orange','title'=>'Multi-Payment Ready','desc'=>'Accept Cash, Cards, and Mobile Money (M-Pesa, Tigo Pesa, Airtel Money) seamlessly in a single unified checkout flow.'],
                ['icon'=>'bi-printer','color'=>'fi-indigo','title'=>'Hardware Integrated','desc'=>'Plug and play with thermal receipt printers, cash drawers, customer displays, and external barcode scanners without hassle.'],
                ['icon'=>'bi-file-earmark-pdf','color'=>'fi-red','title'=>'Professional Invoicing','desc'=>'Generate, print, and share beautiful A4 invoices for your customers instantly. Keep track of paid and unpaid invoices easily.'],
                ['icon'=>'bi-patch-check','color'=>'fi-pink','title'=>'Custom Warranties','desc'=>"Issue professional digital warranty certificates to your customers with 10 customizable themes, complete with your shop's logo."],
                ['icon'=>'bi-receipt','color'=>'fi-green','title'=>'Digital Receipts','desc'=>'Provide modern PDF receipts that can be downloaded or shared directly to customers via WhatsApp or Email, saving on paper costs.'],
            ];
            @endphp
            @foreach($features as $i => $feat)
            <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                <div class="feature-card">
                    <div class="feature-icon {{ $feat['color'] }}"><i class="bi {{ $feat['icon'] }}"></i></div>
                    <h3 class="feature-title">{{ __($feat['title']) }}</h3>
                    <p class="feature-desc">{{ __($feat['desc']) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===========================
     STATS
     =========================== --}}
<section class="stats-wrap">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="0">
                <div class="stat-item">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">{{ __('Active Stores') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="100">
                <div class="stat-item">
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">{{ __('Daily Transactions') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="200">
                <div class="stat-item">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">{{ __('Uptime') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="300">
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">{{ __('Customer Support') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===========================
     TESTIMONIALS
     =========================== --}}
<section class="testimonials-wrap" id="testimonials">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">{{ __('Testimonials') }}</span>
            <h2 class="section-title">{{ __('Loved by Business Owners') }}</h2>
            <p class="section-sub mx-auto">{{ __("See what our customers are saying about Z-pos.") }}</p>
        </div>
        <div class="row g-3">
            @php
            $testimonials = [
                [
                    'quote' => '"Switching to Z-pos was the best decision for our pharmacy chain. The multi-branch Inventory tracking is flawless and saved us millions in expired stock."',
                    'name' => 'Yassir Zahor',
                    'role' => 'Owner, YZ Pharmacies',
                    'avatar' => 'YZ',
                    'color' => '#4f46e5',
                ],
                [
                    'quote' => '"The speed of the checkout screen is incredible. Even during peak evening hours, our queues move twice as fast as before. Highly recommended for supermarkets!"',
                    'name' => 'Arrifai Muntasir',
                    'role' => 'Manager, AM Supermarket',
                    'avatar' => 'AM',
                    'color' => '#059669',
                ],
                [
                    'quote' => '"The detailed profit and loss reports finally gave me clarity on my wholesale business. I now know exactly which items are moving and which are tying up my capital."',
                    'name' => 'Zakom Shop',
                    'role' => 'Director, Wholesale',
                    'avatar' => 'ZS',
                    'color' => '#d97706',
                ],
            ];
            @endphp
            @foreach($testimonials as $i => $t)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="testi-card">
                    <div class="testi-stars">★★★★★</div>
                    <p class="testi-quote">{{ __($t['quote']) }}</p>
                    <div class="testi-author">
                        <div style="width:44px;height:44px;border-radius:50%;background:{{ $t['color'] }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:0.85rem;flex-shrink:0;">
                            {{ $t['avatar'] }}
                        </div>
                        <div>
                            <div class="testi-name">{{ __($t['name']) }}</div>
                            <div class="testi-role">{{ __($t['role']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===========================
     PRICING
     =========================== --}}
<section class="pricing-wrap" id="pricing">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">{{ __('Pricing') }}</span>
            <h2 class="section-title">{{ __('Simple, Transparent Pricing') }}</h2>
            <p class="section-sub mx-auto">{{ __('No hidden fees. Scale as you grow.') }}</p>
        </div>
        <div class="row g-4 justify-content-center">

            {{-- Starter --}}
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="price-card">
                    <div class="price-name">{{ __('Starter') }}</div>
                    <div class="price-amount">TSh 15K<small>/mo</small></div>
                    <div class="price-old">{{ __('TZS 20K') }}</div>
                    <hr class="price-divider">
                    @foreach(['7 Days Free Trial','1 Branch','2 Users','Unlimited Products','Inventory Management','Professional Invoicing','Custom Warranties','Advanced Analytics'] as $feat)
                    <div class="price-feature">
                        <i class="{{ $loop->first ? 'bi bi-gift-fill gift' : 'bi bi-check-circle-fill' }}"></i>
                        {{ __($feat) }}
                    </div>
                    @endforeach
                    <a href="{{ route('register') }}?package=starter" class="btn-price btn-price-outline">{{ __('Get Started') }}</a>
                </div>
            </div>

            {{-- Professional --}}
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="price-card featured">
                    <div class="price-badge">⭐ {{ __('MOST POPULAR') }}</div>
                    <div class="price-name" style="color: var(--primary);">{{ __('Professional') }}</div>
                    <div class="price-amount" style="color: var(--primary);">TSh 45K<small>/mo</small></div>
                    <div class="price-old">{{ __('TZS 50K') }}</div>
                    <hr class="price-divider">
                    @foreach(['7 Days Free Trial','Up to 5 Branches','Unlimited Users','Unlimited Products','Inventory Management','Professional Invoicing','Custom Warranties','Advanced Analytics'] as $feat)
                    <div class="price-feature">
                        <i class="{{ $loop->first ? 'bi bi-gift-fill gift' : 'bi bi-check-circle-fill' }}"></i>
                        {{ __($feat) }}
                    </div>
                    @endforeach
                    <a href="{{ route('register') }}?package=professional" class="btn-price btn-price-filled">{{ __('Get Started') }}</a>
                </div>
            </div>

            {{-- Enterprise --}}
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="price-card">
                    <div class="price-name">{{ __('Enterprise') }}</div>
                    <div class="price-amount">TSh 110K<small>/mo</small></div>
                    <div class="price-old">{{ __('TZS 130K') }}</div>
                    <hr class="price-divider">
                    @foreach(['7 Days Free Trial','Unlimited Branches','Unlimited Users','Unlimited Products','Inventory Management','Professional Invoicing','Custom Warranties','Advanced Analytics'] as $feat)
                    <div class="price-feature">
                        <i class="{{ $loop->first ? 'bi bi-gift-fill gift' : 'bi bi-check-circle-fill' }}"></i>
                        {{ __($feat) }}
                    </div>
                    @endforeach
                    <a href="{{ route('register') }}?package=enterprise" class="btn-price btn-price-outline">{{ __('Get Started') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===========================
     FAQ
     =========================== --}}
@hasSection('faq')
@else
<section class="faq-wrap" id="faq">
    <div class="container" style="max-width: 720px;">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">FAQ</span>
            <h2 class="section-title">{{ __('Frequently Asked Questions') }}</h2>
        </div>
        @php
        $faqs = [
            ['q'=>'Is my data safe on Z-pos?','a'=>'Yes. Each shop\'s data is completely isolated. No other shop can access your sales, customers, or inventory.'],
            ['q'=>'Can I use Z-pos on my phone?','a'=>'Absolutely! Z-pos works on iOS, Android, and any web browser. You can install it as an app on your phone.'],
            ['q'=>'What devices are supported?','a'=>'Z-pos works on smartphones, tablets, laptops, and desktop computers. Any device with a modern browser.'],
            ['q'=>'How does the free trial work?','a'=>'You get 7 days of full access to all features. No credit card required. After 7 days, choose a plan that works for you.'],
            ['q'=>'Can I manage multiple branches?','a'=>'Yes! The Professional plan supports up to 5 branches. Enterprise supports unlimited branches with individual reporting.'],
        ];
        @endphp
        <div data-aos="fade-up" data-aos-delay="100">
            @foreach($faqs as $i => $faq)
            <div class="faq-item" onclick="toggleFaq(this)">
                <button class="faq-question">
                    {{ __($faq['q']) }}
                    <div class="faq-icon"><i class="bi bi-plus"></i></div>
                </button>
                <div class="faq-answer">{{ __($faq['a']) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===========================
     BOTTOM CTA
     =========================== --}}
<section class="cta-wrap">
    <div class="container position-relative" style="z-index:1;">
        <p class="section-label" style="color:rgba(255,255,255,0.5);">{{ __('Start free trial') }}</p>
        <h2 class="cta-title">{{ __('Ready to grow your business?') }}</h2>
        <p class="cta-sub">{{ __('Join 150+ shops already using Z-pos to manage their business smarter.') }}</p>
        <a href="{{ route('register') }}" class="btn-cta-white">
            <i class="bi bi-rocket-takeoff-fill"></i>
            {{ __('Start free trial') }} — 7 {{ __('Days Free') }}
        </a>
    </div>
</section>

<script>
function toggleFaq(el) {
    const isOpen = el.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(e => e.classList.remove('open'));
    if (!isOpen) el.classList.add('open');
}
</script>
@endsection
