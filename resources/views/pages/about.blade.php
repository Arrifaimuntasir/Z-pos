@extends('layouts.landing')
@section('title', 'About Us - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- About Us -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3 border border-success">Our Story</span>
                    <h2 class="fw-bold text-primary display-5 mb-4">{{ __('Empowering Tanzanian Businesses') }}</h2>
                    <p class="text-muted fs-5 mb-4">
                        {{ __('Z-pos was built with a simple mission: to provide a world-class Point of Sale system tailored specifically for the East African market. We understand the unique challenges faced by local shop owners, from internet connectivity issues to complex inventory tracking.') }}
                    </p>
                    <p class="text-muted fs-5 mb-4">
                        {{ __('Our team is dedicated to building software that is not only powerful and secure, but also incredibly easy to use. Whether you are running a single hardware store or a chain of supermarkets, Z-pos scales with you. We\'ve also integrated powerful tools for generating custom warranties, professional A4 invoices, and digital PDF receipts to give your business a premium, professional edge.') }}
                    </p>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-primary px-4 py-3 fw-bold rounded-3">{{ __('Get in Touch') }}</a>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <div class="p-4 bg-light rounded-4 shadow-sm border border-white position-relative">
                        <!-- Abstract illustration or image placeholder -->
                        <div class="bg-primary bg-opacity-10 rounded-3 p-5 d-flex align-items-center justify-content-center" style="height: 400px;">
                            <div class="text-center">
                                <i class="bi bi-building text-primary mb-3" style="font-size: 5rem;"></i>
                                <h4 class="text-primary fw-bold">{{ __('Built for East Africa') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5 pt-5 text-center">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <h1 class="text-success fw-bold display-4 mb-2">5K+</h1>
                    <h5 class="fw-bold text-dark">{{ __('Active Users') }}</h5>
                    <p class="text-muted">{{ __('Trusting our system daily') }}</p>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <h1 class="text-success fw-bold display-4 mb-2">24/7</h1>
                    <h5 class="fw-bold text-dark">{{ __('Customer Support') }}</h5>
                    <p class="text-muted">{{ __('We are always here to help') }}</p>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <h1 class="text-success fw-bold display-4 mb-2">99%</h1>
                    <h5 class="fw-bold text-dark">{{ __('Uptime') }}</h5>
                    <p class="text-muted">{{ __('Reliability you can count on') }}</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
