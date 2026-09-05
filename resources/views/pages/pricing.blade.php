@extends('layouts.landing')
@section('title', 'Pricing - Z-pos')
@section('content')
<div style="padding-top: 100px;">
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
                        <p class="text-muted">{{ __('Perfect for single retail shops.') }}</p>
                        <div class="price mt-4">{{ __('TZS 15K') }}<span>/mo</span> <br><small class="text-muted text-decoration-line-through fs-6">{{ __('TZS 20K') }}</small></div>
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
                        <p class="text-muted">{{ __('For growing multi-branch businesses.') }}</p>
                        <div class="price mt-4">{{ __('TZS 45K') }}<span>/mo</span> <br><small class="text-muted text-decoration-line-through fs-6">{{ __('TZS 50K') }}</small></div>
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
                        <p class="text-muted">{{ __('Custom solutions for large chains.') }}</p>
                        <div class="price mt-4">{{ __('TZS 110K') }}<span>/mo</span> <br><small class="text-muted text-decoration-line-through fs-6">{{ __('TZS 130K') }}</small></div>
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
</div>
@endsection
