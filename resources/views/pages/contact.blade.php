@extends('layouts.landing')
@section('title', 'Contact Us - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- Contact -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">{{ __('Get in Touch') }}</h2>
                <p class="text-muted fs-5 mt-3">We'd love to hear from you. Drop us a line below.</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" data-aos="fade-up" data-aos-delay="100">
                        <div class="row g-4">
                            <div class="col-md-5 border-md-end pe-md-4">
                                <h4 class="fw-bold mb-4">{{ __('Contact Information') }}</h4>
                                
                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-geo-alt"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ __('Our Office') }}</h6>
                                        <p class="text-muted mb-0">{{ __('Uhuru Plaza Kkoo,') }}<br>{{ __('Dar es Salaam, Tanzania') }}</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-envelope"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ __('Email Us') }}</h6>
                                        <p class="text-muted mb-0">info@z-pos.co.tz</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-telephone"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ __('Call Us') }}</h6>
                                        <p class="text-muted mb-0">+255 683 628 142<br>+255 716 465 511</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-7 ps-md-4">
                                @if(session('success'))
                                    <div class="alert alert-success rounded-3 mb-4">
                                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                    </div>
                                @endif
                                <form action="{{ route('contact.submit') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('Full Name') }}</label>
                                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0 @error('name') is-invalid @enderror" placeholder="{{ __('Enter your name') }}" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('Email Address') }}</label>
                                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 @error('email') is-invalid @enderror" placeholder="{{ __('Enter your email') }}" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('Phone Number') }}</label>
                                        <input type="tel" name="phone" class="form-control form-control-lg bg-light border-0 @error('phone') is-invalid @enderror" placeholder="{{ __('Enter your phone number') }}" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">{{ __('Message') }}</label>
                                        <textarea name="message" class="form-control bg-light border-0 @error('message') is-invalid @enderror" rows="4" placeholder="{{ __('How can we help you?') }}" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-success text-white w-100 py-3 fw-bold rounded-3">{{ __('Send Message') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
