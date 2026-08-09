@extends('layouts.landing')
@section('title', 'Features - Z-pos')
@section('content')
<div style="padding-top: 100px;">
    <!-- Features -->
    <section id="features" class="py-5 bg-light" style="min-height: 80vh;">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-2 border border-success">Core Features</span>
                <h2 class="fw-bold text-primary display-5">Everything you need to scale</h2>
                <p class="text-muted fs-5 mt-3 max-w-2xl mx-auto">From single shops to nationwide chains, we've got you covered.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-box p-4 bg-white rounded shadow-sm h-100">
                        <div class="icon-wrapper mb-3 text-primary fs-2"><i class="bi bi-upc-scan"></i></div>
                        <h3>Lightning Fast POS</h3>
                        <p class="text-muted">Process sales in seconds using barcode scanners, shortcuts, and an intuitive touch-friendly interface designed for speed.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-box p-4 bg-white rounded shadow-sm h-100">
                        <div class="icon-wrapper mb-3 text-primary fs-2"><i class="bi bi-box-seam"></i></div>
                        <h3>Smart Inventory</h3>
                        <p class="text-muted">Track stock across multiple branches in real-time. Get low-stock alerts, manage expiry dates, and handle seamless transfers.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-box p-4 bg-white rounded shadow-sm h-100">
                        <div class="icon-wrapper mb-3 text-primary fs-2"><i class="bi bi-pie-chart"></i></div>
                        <h3>Advanced Analytics</h3>
                        <p class="text-muted">Make data-driven decisions with detailed reports on daily sales, profit margins, employee performance, and top-selling items.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-box p-4 bg-white rounded shadow-sm h-100">
                        <div class="icon-wrapper mb-3 text-primary fs-2"><i class="bi bi-shield-check"></i></div>
                        <h3>Enterprise Security</h3>
                        <p class="text-muted">Role-based access control ensures staff only see what they need to. Activity logs track every void, discount, and deletion.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-box p-4 bg-white rounded shadow-sm h-100">
                        <div class="icon-wrapper mb-3 text-primary fs-2"><i class="bi bi-credit-card"></i></div>
                        <h3>Multi-Payment Ready</h3>
                        <p class="text-muted">Accept Cash, Cards, and Mobile Money (M-Pesa, Tigo Pesa, Airtel Money) seamlessly in a single unified checkout flow.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-box p-4 bg-white rounded shadow-sm h-100">
                        <div class="icon-wrapper mb-3 text-primary fs-2"><i class="bi bi-printer"></i></div>
                        <h3>Hardware Integrated</h3>
                        <p class="text-muted">Plug and play with thermal receipt printers, cash drawers, customer displays, and external barcode scanners without hassle.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
