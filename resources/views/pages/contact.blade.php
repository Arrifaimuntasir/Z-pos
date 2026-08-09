@extends('layouts.landing')
@section('title', 'Contact Us - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- Contact -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">Get in Touch</h2>
                <p class="text-muted fs-5 mt-3">We'd love to hear from you. Drop us a line below.</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" data-aos="fade-up" data-aos-delay="100">
                        <div class="row g-4">
                            <div class="col-md-5 border-md-end pe-md-4">
                                <h4 class="fw-bold mb-4">Contact Information</h4>
                                
                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-geo-alt"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Our Office</h6>
                                        <p class="text-muted mb-0">Uhuru Plaza Kkoo,<br>Dar es Salaam, Tanzania</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-envelope"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Email Us</h6>
                                        <p class="text-muted mb-0">arrifaimuntasir@gmail.com<br>yasirszahor@gmail.com</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-telephone"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Call Us</h6>
                                        <p class="text-muted mb-0">+255 683 628 142<br>+255 716 465 511</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-7 ps-md-4">
                                <form>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Full Name</label>
                                        <input type="text" class="form-control form-control-lg bg-light border-0" placeholder="Enter your name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email Address</label>
                                        <input type="email" class="form-control form-control-lg bg-light border-0" placeholder="Enter your email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Phone Number</label>
                                        <input type="tel" class="form-control form-control-lg bg-light border-0" placeholder="Enter your phone number">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Message</label>
                                        <textarea class="form-control bg-light border-0" rows="4" placeholder="How can we help you?" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success text-white w-100 py-3 fw-bold rounded-3">Send Message</button>
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
