@extends('layouts.landing')
@section('title', 'Pricing - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- Pricing -->
    <section id="pricing" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">Simple, Transparent Pricing</h2>
                <p class="text-muted fs-5 mt-3">No hidden fees. Scale as you grow.</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <!-- Basic -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing-card h-100">
                        <h4 class="fw-bold text-primary mb-3">Starter</h4>
                        <p class="text-muted">Perfect for single retail shops.</p>
                        <div class="price mt-4">TZS 15K<span>/mo</span></div>
                        <ul class="mb-4">
                            <li><i class="bi bi-check-circle-fill"></i> 1 Branch</li>
                            <li><i class="bi bi-check-circle-fill"></i> 2 Users</li>
                            <li><i class="bi bi-check-circle-fill"></i> Unlimited Products</li>
                            <li><i class="bi bi-check-circle-fill"></i> Standard Reports</li>
                            <li><i class="bi bi-x-circle text-muted"></i> API Access</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 py-2 fw-bold" style="border-radius: 50px;">Get Started</a>
                    </div>
                </div>
                
                <!-- Pro -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing-card popular h-100">
                        <div class="badge-popular">MOST POPULAR</div>
                        <h4 class="fw-bold text-success mb-3">Professional</h4>
                        <p class="text-muted">For growing multi-branch businesses.</p>
                        <div class="price mt-4">TZS 75K<span>/mo</span></div>
                        <ul class="mb-4">
                            <li><i class="bi bi-check-circle-fill"></i> Up to 5 Branches</li>
                            <li><i class="bi bi-check-circle-fill"></i> Unlimited Users</li>
                            <li><i class="bi bi-check-circle-fill"></i> Advanced Inventory</li>
                            <li><i class="bi bi-check-circle-fill"></i> Advanced Analytics</li>
                            <li><i class="bi bi-check-circle-fill"></i> Priority Support</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-success text-white w-100 py-2 fw-bold shadow" style="border-radius: 50px;">Start Free Trial</a>
                    </div>
                </div>
                
                <!-- Enterprise -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="pricing-card h-100">
                        <h4 class="fw-bold text-primary mb-3">Enterprise</h4>
                        <p class="text-muted">Custom solutions for large chains.</p>
                        <div class="price mt-4">Custom</div>
                        <ul class="mb-4">
                            <li><i class="bi bi-check-circle-fill"></i> Unlimited Branches</li>
                            <li><i class="bi bi-check-circle-fill"></i> Custom Developments</li>
                            <li><i class="bi bi-check-circle-fill"></i> Dedicated Account Manager</li>
                            <li><i class="bi bi-check-circle-fill"></i> ERP Integrations</li>
                            <li><i class="bi bi-check-circle-fill"></i> On-Premise Option</li>
                        </ul>
                        <a href="{{ url('/contact') }}" class="btn btn-outline-primary w-100 py-2 fw-bold" style="border-radius: 50px;">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
