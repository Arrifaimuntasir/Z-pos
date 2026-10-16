@extends('layouts.landing')
@section('title', 'About Us - Z-pos')
@section('content')
@php use App\Models\CmsSetting; @endphp
<div style="padding-top: 100px;">
    <section class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3 border border-success">{{ CmsSetting::get('about', 'hero_badge', 'Our Story') }}</span>
                    <h2 class="fw-bold text-primary display-5 mb-4">{{ CmsSetting::get('about', 'hero_title', 'Empowering Tanzanian Businesses') }}</h2>
                    <p class="text-muted fs-5 mb-4">{{ CmsSetting::get('about', 'hero_paragraph_1', '') }}</p>
                    <p class="text-muted fs-5 mb-4">{{ CmsSetting::get('about', 'hero_paragraph_2', '') }}</p>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-primary px-4 py-3 fw-bold rounded-3">{{ __('Get in Touch') }}</a>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <div class="p-4 bg-light rounded-4 shadow-sm border border-white position-relative">
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
                    <h1 class="text-success fw-bold display-4 mb-2">{{ CmsSetting::get('about', 'stat_1_number', '5K+') }}</h1>
                    <h5 class="fw-bold text-dark">{{ CmsSetting::get('about', 'stat_1_label', 'Active Users') }}</h5>
                    <p class="text-muted">{{ CmsSetting::get('about', 'stat_1_desc', 'Trusting our system daily') }}</p>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <h1 class="text-success fw-bold display-4 mb-2">{{ CmsSetting::get('about', 'stat_2_number', '24/7') }}</h1>
                    <h5 class="fw-bold text-dark">{{ CmsSetting::get('about', 'stat_2_label', 'Customer Support') }}</h5>
                    <p class="text-muted">{{ CmsSetting::get('about', 'stat_2_desc', 'We are always here to help') }}</p>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <h1 class="text-success fw-bold display-4 mb-2">{{ CmsSetting::get('about', 'stat_3_number', '99%') }}</h1>
                    <h5 class="fw-bold text-dark">{{ CmsSetting::get('about', 'stat_3_label', 'Uptime') }}</h5>
                    <p class="text-muted">{{ CmsSetting::get('about', 'stat_3_desc', 'Reliability you can count on') }}</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
