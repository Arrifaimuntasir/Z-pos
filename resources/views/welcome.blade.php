@extends('layouts.landing')

@section('content')
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
                        Run your shop <br> from your pocket.
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="fs-5 text-muted mb-5" style="max-width: 500px; line-height: 1.6;">
                        Z-pos is a complete POS for Tanzanian retailers — products, suppliers, customers, and finances, on iOS, Android and the web.
                    </p>
                    
                    <!-- Buttons -->
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="{{ route('register') }}" class="btn btn-success text-white fw-bold px-4 py-3 shadow-sm" style="border-radius: 8px;">
                            Start free trial <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#demoVideoModal" class="btn btn-outline-primary fw-bold px-4 py-3 shadow-sm" style="border-radius: 8px;">
                            <i class="bi bi-play-fill me-1"></i> Watch 10-sec demo
                        </button>
                    </div>
                    
                    <!-- Stats Section -->
                    <div class="row mt-5 pt-3" style="max-width: 500px;">
                        <div class="col-4 border-end border-light">
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">5,000+</h4>
                            <p class="text-muted small mb-0">shops onboarded</p>
                        </div>
                        <div class="col-4 border-end border-light">
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">Tsh 8.4B</h4>
                            <p class="text-muted small mb-0">processed monthly</p>
                        </div>
                        <div class="col-4">
                            <h4 class="fw-bold mb-1" style="color: #0f172a;">4.9 <i class="bi bi-star-fill text-warning ms-1" style="font-size: 0.9rem;"></i></h4>
                            <p class="text-muted small mb-0">App Store rating</p>
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
            <h6 class="text-muted fw-bold mb-5 text-uppercase tracking-wider">Trusted by Industry Leaders</h6>
            <div class="row align-items-center justify-content-center">
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <h5 class="fw-bold mb-2 text-dark">Azam</h5>
                    <img src="{{ asset('images/azamtv.png') }}" alt="Azam" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <h5 class="fw-bold mb-2 text-dark">Vodacom</h5>
                    <img src="{{ asset('images/images.jfif') }}" alt="Vodacom" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <h5 class="fw-bold mb-2 text-dark">CRDB</h5>
                    <img src="{{ asset('images/images.png') }}" alt="CRDB" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="400">
                    <h5 class="fw-bold mb-2 text-dark">Shoppers</h5>
                    <img src="{{ asset('images/images (1).png') }}" alt="Shoppers" style="height: 40px; object-fit: contain;">
                </div>
                <div class="col-4 col-md-2 mb-4" data-aos="zoom-in" data-aos-delay="500">
                    <h5 class="fw-bold mb-2 text-dark">Yas</h5>
                    <img src="{{ asset('images/Yas_Tanzania.svg') }}" alt="Yas" style="height: 40px; object-fit: contain;">
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-2 border border-success">Core Features</span>
                <h2 class="fw-bold text-primary display-5">Everything you need to scale</h2>
                <p class="text-muted fs-5 mt-3 max-w-2xl mx-auto">From single shops to nationwide chains, we've got you covered.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-upc-scan"></i></div>
                        <h3>Lightning Fast POS</h3>
                        <p>Process sales in seconds using barcode scanners, shortcuts, and an intuitive touch-friendly interface designed for speed.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-box-seam"></i></div>
                        <h3>Smart Inventory</h3>
                        <p>Track stock across multiple branches in real-time. Get low-stock alerts, manage expiry dates, and handle seamless transfers.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-pie-chart"></i></div>
                        <h3>Advanced Analytics</h3>
                        <p>Make data-driven decisions with detailed reports on daily sales, profit margins, employee performance, and top-selling items.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-shield-check"></i></div>
                        <h3>Enterprise Security</h3>
                        <p>Role-based access control ensures staff only see what they need to. Activity logs track every void, discount, and deletion.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-credit-card"></i></div>
                        <h3>Multi-Payment Ready</h3>
                        <p>Accept Cash, Cards, and Mobile Money (M-Pesa, Tigo Pesa, Airtel Money) seamlessly in a single unified checkout flow.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-box">
                        <div class="icon-wrapper"><i class="bi bi-printer"></i></div>
                        <h3>Hardware Integrated</h3>
                        <p>Plug and play with thermal receipt printers, cash drawers, customer displays, and external barcode scanners without hassle.</p>
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
                    <h2 class="display-4 fw-bold text-success mb-0">5K+</h2>
                    <p class="text-muted mt-2 text-uppercase tracking-wider">Active Stores</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="200">
                    <h2 class="display-4 fw-bold text-success mb-0">2M+</h2>
                    <p class="text-muted mt-2 text-uppercase tracking-wider">Daily Transactions</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="300">
                    <h2 class="display-4 fw-bold text-success mb-0">99.9%</h2>
                    <p class="text-muted mt-2 text-uppercase tracking-wider">Uptime</p>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="400">
                    <h2 class="display-4 fw-bold text-success mb-0">24/7</h2>
                    <p class="text-muted mt-2 text-uppercase tracking-wider">Customer Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">Loved by Business Owners</h2>
                <p class="text-muted fs-5 mt-3">Don't just take our word for it.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card position-relative h-100">
                        <i class="bi bi-quote quote-icon"></i>
                        <p>"Switching to Z-pos was the best decision for our pharmacy chain. The multi-branch inventory tracking is flawless and saved us millions in expired stock."</p>
                        <div class="client-info">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=100&q=80" alt="Client">
                            <div>
                                <h5>Fatma Juma</h5>
                                <span>Owner, Afya Pharmacies</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card position-relative h-100">
                        <i class="bi bi-quote quote-icon"></i>
                        <p>"The speed of the checkout screen is incredible. Even during peak evening hours, our queues move twice as fast as before. Highly recommended for supermarkets!"</p>
                        <div class="client-info">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80" alt="Client">
                            <div>
                                <h5>Michael Shirima</h5>
                                <span>Manager, Shoppers Mart</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card position-relative h-100">
                        <i class="bi bi-quote quote-icon"></i>
                        <p>"The detailed profit and loss reports finally gave me clarity on my wholesale business. I now know exactly which items are moving and which are tying up my capital."</p>
                        <div class="client-info">
                            <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=100&q=80" alt="Client">
                            <div>
                                <h5>Salum K.</h5>
                                <span>Director, K-Wholesale</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                        <a href="{{ route('register') }}?package=starter" class="btn btn-outline-primary w-100 py-2 fw-bold" style="border-radius: 50px;">Get Started</a>
                    </div>
                </div>
                
                <!-- Pro -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing-card popular h-100">
                        <div class="badge-popular">MOST POPULAR</div>
                        <h4 class="fw-bold text-success mb-3">Professional</h4>
                        <p class="text-muted">For growing multi-branch businesses.</p>
                        <div class="price mt-4">TZS 65K<span>/mo</span></div>
                        <ul class="mb-4">
                            <li><i class="bi bi-check-circle-fill"></i> Up to 5 Branches</li>
                            <li><i class="bi bi-check-circle-fill"></i> Unlimited Users</li>
                            <li><i class="bi bi-check-circle-fill"></i> Advanced Inventory</li>
                            <li><i class="bi bi-check-circle-fill"></i> Advanced Analytics</li>
                            <li><i class="bi bi-check-circle-fill"></i> Priority Support</li>
                        </ul>
                        <a href="{{ route('register') }}?package=professional" class="btn btn-success text-white w-100 py-2 fw-bold shadow" style="border-radius: 50px;">Start Free Trial</a>
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
                        <a href="{{ route('register') }}?package=enterprise" class="btn btn-outline-primary w-100 py-2 fw-bold" style="border-radius: 50px;">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                    <h2 class="fw-bold text-primary display-6">Frequently Asked Questions</h2>
                    <p class="text-muted mt-3 mb-4">Have questions? We're here to help you understand how Z-pos can transform your business.</p>
                    <a href="#contact" class="btn btn-outline-success rounded-pill px-4">Still have questions?</a>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item border-bottom mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-primary bg-white px-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Do I need internet to use the POS?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 text-muted">
                                    The system requires internet to sync data across branches in real-time. However, our offline-mode feature allows you to continue making sales if the connection drops, automatically syncing when restored.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-bottom mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-primary bg-white px-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    What hardware is supported?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 text-muted">
                                    Z-pos works on any PC, Laptop, or Tablet using a modern web browser. It integrates with any standard USB/Bluetooth barcode scanner, ESC/POS thermal receipt printers, and cash drawers.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-bottom mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-primary bg-white px-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    How secure is my data?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 text-muted">
                                    We use enterprise-grade encryption and daily automated backups. Your data is isolated, and strict role-based access control prevents unauthorized staff from viewing sensitive financial reports.
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
                    <h5 class="modal-title fw-bold" id="demoVideoModalLabel">Z-pos System Demo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-dark">
                    <div class="ratio ratio-16x9">
                        <video id="demoVideoElement" controls preload="none" class="w-100 h-100" style="object-fit: cover;">
                            <source src="{{ asset('videos/demo.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
