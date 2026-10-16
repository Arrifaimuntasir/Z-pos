@extends('layouts.landing')
@section('title', 'Testimonials - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- Testimonials -->
    <section id="testimonials" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">{{ __('Loved by shop owners') }}</h2>
                <p class="text-muted fs-5 mt-3">{{ __('See what our customers are saying about Z-pos.') }}</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex text-warning mb-3">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p class="fst-italic text-muted mb-4">"{{ __('Since I started using Z-pos, I've been able to control theft in my shop. The system is very easy to understand even for my young staff.') }}"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 45px; height: 45px;">AM</div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-bold">Amina M.</h6>
                                    <span class="text-muted small">Hardware Store, Kariakoo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex text-warning mb-3">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p class="fst-italic text-muted mb-4">"{{ __('The offline mode is a lifesaver! When Tanesco cuts power and internet goes down, my cashiers can still print receipts without any issues.') }}"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 45px; height: 45px;">JK</div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-bold">John K.</h6>
                                    <span class="text-muted small">Supermarket, Mbezi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex text-warning mb-3">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p class="fst-italic text-muted mb-4">"{{ __('I can see real-time sales on my phone while travelling. It gives me peace of mind knowing exactly what's happening in all my 3 branches.') }}"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 45px; height: 45px;">SJ</div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-bold">Sarah J.</h6>
                                    <span class="text-muted small">Pharmacy Chain, Arusha</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
