@php
    $cat = strtolower($shop->business_type ?? '');
    $bgImage = 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80'; // Default: people working/office
    
    if (strpos($cat, 'electronics') !== false || strpos($cat, 'phone') !== false || strpos($cat, 'computer') !== false) {
        $bgImage = 'https://images.unsplash.com/photo-1519389953888-9d31c4fcc025?auto=format&fit=crop&w=600&q=80';
    } elseif (strpos($cat, 'restaurant') !== false || strpos($cat, 'food') !== false || strpos($cat, 'cafe') !== false) {
        $bgImage = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80';
    } elseif (strpos($cat, 'pharmacy') !== false || strpos($cat, 'medical') !== false || strpos($cat, 'health') !== false || strpos($cat, 'clinic') !== false) {
        $bgImage = 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&w=600&q=80';
    } elseif (strpos($cat, 'hardware') !== false || strpos($cat, 'construction') !== false || strpos($cat, 'tools') !== false) {
        $bgImage = 'https://images.unsplash.com/photo-1581166397057-235af2b3c6dd?auto=format&fit=crop&w=600&q=80';
    } elseif (strpos($cat, 'boutique') !== false || strpos($cat, 'clothes') !== false || strpos($cat, 'fashion') !== false) {
        $bgImage = 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=600&q=80';
    } elseif (strpos($cat, 'beauty') !== false || strpos($cat, 'salon') !== false || strpos($cat, 'cosmetics') !== false) {
        $bgImage = 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=600&q=80';
    } elseif (strpos($cat, 'supermarket') !== false || strpos($cat, 'grocery') !== false || strpos($cat, 'mini mart') !== false) {
        $bgImage = 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?auto=format&fit=crop&w=600&q=80';
    }
@endphp
@extends('layouts.admin')

@section('title', 'Business Card')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">{{ __('Digital Business Card') }}</h4>
            <span class="text-muted small">{{ __('Your modern, scannable contact card') }}</span>
        </div>
    </div>

    <div class="row">
        <!-- Editor Controls (Left Side) -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 d-print-none rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 border-bottom pb-3">{{ __('Customize Card') }}</h6>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">{{ __('1. Choose Theme or Custom Color') }}</label>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <div class="d-flex flex-wrap gap-2 mb-2 w-100">
                                <button type="button" class="btn btn-sm btn-outline-dark {{ $shop->card_theme == 'theme-dark' || !$shop->card_theme ? 'active' : '' }}" onclick="switchTheme('theme-dark', this)">Dark</button>
                                <button type="button" class="btn btn-sm btn-outline-primary {{ $shop->card_theme == 'theme-blue' ? 'active' : '' }}" onclick="switchTheme('theme-blue', this)">Blue</button>
                                <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-bold {{ $shop->card_theme == 'theme-gold' ? 'active' : '' }}" onclick="switchTheme('theme-gold', this)">Gold</button>
                                <button type="button" class="btn btn-sm btn-outline-success {{ $shop->card_theme == 'theme-green' ? 'active' : '' }}" onclick="switchTheme('theme-green', this)">Green</button>
                                <button type="button" class="btn btn-sm btn-outline-danger {{ $shop->card_theme == 'theme-red' ? 'active' : '' }}" onclick="switchTheme('theme-red', this)">Red</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-purple' ? 'active' : '' }}" onclick="switchTheme('theme-purple', this)" style="border-color: #a855f7; color: #a855f7;">Purple</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-orange' ? 'active' : '' }}" onclick="switchTheme('theme-orange', this)" style="border-color: #f97316; color: #f97316;">Orange</button>
                                <button type="button" class="btn btn-sm btn-outline-info {{ $shop->card_theme == 'theme-teal' ? 'active' : '' }}" onclick="switchTheme('theme-teal', this)">Teal</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-pink' ? 'active' : '' }}" onclick="switchTheme('theme-pink', this)" style="border-color: #ec4899; color: #ec4899;">Pink</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-indigo' ? 'active' : '' }}" onclick="switchTheme('theme-indigo', this)" style="border-color: #6366f1; color: #6366f1;">Indigo</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-cyan' ? 'active' : '' }}" onclick="switchTheme('theme-cyan', this)" style="border-color: #06b6d4; color: #06b6d4;">Cyan</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-lime' ? 'active' : '' }}" onclick="switchTheme('theme-lime', this)" style="border-color: #84cc16; color: #84cc16;">Lime</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-emerald' ? 'active' : '' }}" onclick="switchTheme('theme-emerald', this)" style="border-color: #10b981; color: #10b981;">Emerald</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-rose' ? 'active' : '' }}" onclick="switchTheme('theme-rose', this)" style="border-color: #f43f5e; color: #f43f5e;">Rose</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary {{ $shop->card_theme == 'theme-slate' ? 'active' : '' }}" onclick="switchTheme('theme-slate', this)" style="border-color: #64748b; color: #64748b;">Slate</button>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 rounded-3 border w-100">
                                <span class="small fw-bold text-muted"><i class="bi bi-palette"></i> {{ __('Or pick any color:') }}</span>
                                <input type="color" id="custom-color-picker" class="form-control form-control-color border-0 p-0 bg-transparent ms-auto" value="{{ $shop->card_color ?? '#3b82f6' }}" style="width: 40px; height: 35px; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('2. Phone Number') }}</label>
                        <input type="text" class="form-control bg-light" id="input-phone" value="{{ $shop->card_phone ?? $shop->phone }}" placeholder="{{ __('Example: 07xx xxx xxx') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('3. Email Address') }}</label>
                        <input type="email" class="form-control bg-light" id="input-email" value="{{ $shop->card_email ?? auth()->user()->email }}" placeholder="{{ __('Example: info@yourshop.com') }}">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">{{ __('4. Bottom Message') }}</label>
                        <input type="text" class="form-control bg-light" id="input-message" value="{{ $shop->card_message }}" placeholder="{{ __('Example: Scan for Details') }}">
                    </div>

                    <hr class="my-4">

                    <!-- Action Buttons -->
                    <button id="btn-save-design" class="btn btn-primary fw-bold py-2 shadow-sm rounded-3 w-100">
                        <i class="bi bi-save me-2"></i> {{ __('Save Design') }}
                    </button>
                    
                    <div class="d-flex flex-column gap-3 mt-3">
                        <button id="btn-download-img" class="btn btn-success fw-bold py-2 shadow-sm rounded-3 w-100">
                            <i class="bi bi-image me-2"></i> {{ __('Download PNG') }}
                        </button>
                        <button id="btn-download-pdf" class="btn btn-danger fw-bold py-2 shadow-sm rounded-3 w-100">
                            <i class="bi bi-file-pdf me-2"></i> {{ __('Download PDF') }}
                        </button>
                    </div>
                    
                    <div class="mt-3 text-center" id="download-status" style="display: none;">
                        <span class="text-primary small fw-bold"><span class="spinner-border spinner-border-sm me-2"></span> {{ __('Preparing, please wait...') }}</span>
                    </div>
                </div>
            </div>
        </div>

                <!-- Card Container (Right Side) -->
        <div class="col-lg-7 d-flex flex-column align-items-center justify-content-start pt-3">
            <div class="bg-light w-100 rounded-4 d-flex flex-column justify-content-center align-items-center pt-5 pb-5 shadow-sm border h-100 position-relative" style="min-height: 500px; overflow: hidden;">
                
                <div class="tap-instruction mb-4 d-print-none text-center">
                    <div class="d-inline-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-hand-index-thumb fs-2 text-primary pulse-animation" style="transform: rotate(180deg) scaleX(-1);"></i>
                        <span class="fw-bold text-muted mt-1 bg-white px-3 py-1 rounded-pill shadow-sm border">Tap to view back</span>
                    </div>
                </div>

                <div class="business-card-container {{ $shop->card_theme ?? 'theme-dark' }}" id="business-card" style="{{ $shop->card_color ? '--brand-color:'.$shop->card_color.';--logo-bg:'.$shop->card_color.';--accent-bg:'.$shop->card_color.';--icon-color:'.$shop->card_color.';' : '' }}" onclick="this.classList.toggle('flipped')">
                    <div class="business-card shadow-lg">
                        
                        <!-- Front Side -->
                        <div class="card-face card-front d-flex overflow-hidden" id="card-front-capture" style="background: white;">
                            
                            <!-- Left White Section -->
                            <div class="bg-white p-4 d-flex flex-column justify-content-center position-relative" style="width: 65%;">
                                <!-- Theme color shape accent on far left -->
                                <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 16px; background: var(--brand-color, #3b82f6); border-top-left-radius: 12px; border-bottom-left-radius: 12px; clip-path: polygon(0 0, 100% 0, 60% 100%, 0 100%);"></div>
                                
                                <div class="ps-3 position-relative z-1">
                                    @if($shop->logo)
                                        <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo" style="height: 65px; object-fit: contain; margin-bottom: 12px;">
                                    @else
                                        <img src="{{ asset('images/zamar_logo.jpg') }}" alt="Logo" style="height: 65px; object-fit: contain; margin-bottom: 12px;" onerror="this.style.display='none'">
                                    @endif
                                    
                                    <h3 class="fw-bold mb-1 text-truncate" style="color: var(--brand-color, #0d6efd); font-family: 'Arial Black', impact, sans-serif; letter-spacing: 1px;">{{ strtoupper($shop->name) }}</h3>
                                    <h6 class="fw-bold text-dark mb-4 text-truncate" id="display-message">{{ $shop->card_message ?: 'Scan for Details' }}</h6>
                                    
                                    <div class="d-flex gap-3 mt-2" style="font-size: 0.75rem; font-weight: 700; color: #4b5563;">
                                        <div class="d-flex align-items-center"><i class="bi bi-clock me-1 fs-6" style="color: var(--brand-color, #3b82f6);"></i> FAST</div>
                                        <div class="d-flex align-items-center"><i class="bi bi-shield-check me-1 fs-6" style="color: var(--brand-color, #3b82f6);"></i> SAFE</div>
                                        <div class="d-flex align-items-center">RELIABLE</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Image Section -->
                            <div style="width: 35%; background-image: url('{{ $bgImage }}'); background-size: cover; background-position: center; border-radius: 0 12px 12px 0; border-left: 1px solid #f3f4f6;">
                            </div>
                        </div>

                        <!-- Back Side -->
                        <div class="card-face card-back d-flex overflow-hidden" id="card-back-capture" style="background: white;">
                            
                            <!-- Left Theme Section -->
                            <div class="p-4 d-flex flex-column text-white justify-content-center" style="width: 62%; background-color: var(--brand-color, #3b82f6); border-radius: 12px 0 0 12px;">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    <h4 class="fw-bold mb-0 text-truncate" style="letter-spacing: 0.5px;">{{ strtoupper($shop->name) }}</h4>
                                </div>
                                <span class="mb-4 d-block" style="font-size: 0.75rem; opacity: 0.85; font-weight: 500;">Head Office</span>

                                @php
                                    $displayPhone = $shop->card_phone ?? $shop->phone;
                                    $displayEmail = $shop->card_email ?? auth()->user()->email;
                                @endphp

                                <div class="d-flex align-items-start mb-3" id="box-phone" style="{{ $displayPhone ? '' : 'display:none;' }}">
                                    <div class="d-flex justify-content-center align-items-center rounded-circle me-3 mt-1" style="width: 24px; height: 24px; background: rgba(255,255,255,0.2);">
                                        <i class="bi bi-telephone-fill" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div style="width: calc(100% - 40px);">
                                        <span class="d-block" style="font-size: 0.65rem; opacity: 0.85;">Phone Number</span>
                                        <span class="fw-bold fs-6 text-truncate d-block" id="display-phone">{{ $displayPhone }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-3" id="box-email" style="{{ $displayEmail ? '' : 'display:none;' }}">
                                    <div class="d-flex justify-content-center align-items-center rounded-circle me-3 mt-1" style="width: 24px; height: 24px; background: rgba(255,255,255,0.2);">
                                        <i class="bi bi-envelope-fill" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div style="width: calc(100% - 40px);">
                                        <span class="d-block" style="font-size: 0.65rem; opacity: 0.85;">Email Address</span>
                                        <span class="fw-bold text-truncate d-block" style="font-size: 0.8rem;" id="display-email">{{ $displayEmail }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start">
                                    <div class="d-flex justify-content-center align-items-center rounded-circle me-3 mt-1" style="width: 24px; height: 24px; background: rgba(255,255,255,0.2);">
                                        <i class="bi bi-geo-alt-fill" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div style="width: calc(100% - 40px);">
                                        <span class="d-block" style="font-size: 0.65rem; opacity: 0.85;">Address</span>
                                        <span class="fw-bold fs-6 text-truncate d-block">{{ $shop->address ?? 'kkoo, uhuru plaza' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Yellow Divider -->
                            <div style="width: 3%; background-color: #fbbf24; z-index: 2;"></div>

                            <!-- Right Image Section -->
                            <div class="position-relative" style="width: 35%; background-image: url('{{ $bgImage }}'); background-size: cover; background-position: center; border-radius: 0 12px 12px 0;">
                                
                                <!-- Dark overlay to make QR pop slightly -->
                                <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.15); border-radius: 0 12px 12px 0;"></div>

                                <!-- QR Code Overlay -->
                                <div class="position-absolute top-50 start-50 translate-middle bg-white p-1 rounded-3 shadow-lg" style="border: 3px solid #fbbf24; width: 110px; z-index: 10;">
                                    <img src="{{ $qrCode }}" alt="QR Code" style="width: 100%; height: auto; border-radius: 4px;">
                                    <div class="w-100 text-center text-white mt-1 py-1 rounded-1 shadow-sm" style="background-color: var(--brand-color, #3b82f6); font-size: 0.65rem; font-weight: 800; letter-spacing: 0.5px;">SCAN TO SCAN</div>
                                </div>

                                <!-- Social Overlay -->
                                <div class="position-absolute bottom-0 end-0 bg-white p-2 text-center" style="border-top-left-radius: 12px; margin-right: 12px; box-shadow: -2px -2px 15px rgba(0,0,0,0.15); z-index: 10;">
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">Follow us</div>
                                    <div class="d-flex gap-1 justify-content-center mb-1">
                                        <div style="width: 18px; height: 18px; background: #1877F2; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 10px;"><i class="bi bi-facebook"></i></div>
                                        <div style="width: 18px; height: 18px; background: #E1306C; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 10px;"><i class="bi bi-instagram"></i></div>
                                        <div style="width: 18px; height: 18px; background: #000000; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 10px;"><i class="bi bi-tiktok"></i></div>
                                    </div>
                                    <span class="fw-bold text-dark" style="font-size: 0.55rem;">{{ strtolower(str_replace(' ', '_', $shop->name)) }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    function switchTheme(themeName, btn) {
        const card = document.getElementById('business-card');
        card.className = 'business-card-container ' + themeName;

        card.style.removeProperty('--brand-color');
        card.style.removeProperty('--logo-bg');
        card.style.removeProperty('--accent-bg');
        card.style.removeProperty('--icon-color');

        const buttons = btn.parentElement.querySelectorAll('button');
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const colorPicker = document.getElementById('custom-color-picker');
        const card = document.getElementById('business-card');
        
        if(colorPicker) {
            colorPicker.addEventListener('input', function() {
                const color = this.value;
                document.querySelectorAll('.btn-group button').forEach(b => b.classList.remove('active'));
                card.style.setProperty('--brand-color', color);
                card.style.setProperty('--logo-bg', color);
                card.style.setProperty('--accent-bg', color);
                card.style.setProperty('--icon-color', color);
            });
        }

        const inputPhone = document.getElementById('input-phone');
        const inputEmail = document.getElementById('input-email');
        const inputMessage = document.getElementById('input-message');
        const displayPhone = document.getElementById('display-phone');
        const boxPhone = document.getElementById('box-phone');
        const displayEmail = document.getElementById('display-email');
        const boxEmail = document.getElementById('box-email');
        const displayMessage = document.getElementById('display-message');

        inputPhone.addEventListener('input', function() {
            if(this.value) { displayPhone.textContent = this.value; boxPhone.style.display = 'flex'; } 
            else { boxPhone.style.display = 'none'; }
        });

        inputEmail.addEventListener('input', function() {
            if(this.value) { displayEmail.textContent = this.value; boxEmail.style.display = 'flex'; } 
            else { boxEmail.style.display = 'none'; }
        });

        inputMessage.addEventListener('input', function() {
            displayMessage.textContent = this.value ? this.value : 'Scan for Details';
        });

        // --- SAVE DESIGN TO DB ---
        const btnSaveDesign = document.getElementById('btn-save-design');
        if(btnSaveDesign) {
            btnSaveDesign.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Inasave...';
                this.disabled = true;

                // get theme
                let activeTheme = 'theme-dark';
                card.classList.forEach(cls => {
                    if(cls.startsWith('theme-')) activeTheme = cls;
                });

                const payload = {
                    _token: '{{ csrf_token() }}',
                    card_theme: activeTheme,
                    card_color: colorPicker.value,
                    card_phone: inputPhone.value,
                    card_email: inputEmail.value,
                    card_message: inputMessage.value
                };

                fetch('{{ route("shop.business-card.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        this.innerHTML = '<i class="bi bi-check-circle me-2"></i> Imesave!';
                        this.classList.replace('btn-primary', 'btn-success');
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.replace('btn-success', 'btn-primary');
                            this.disabled = false;
                        }, 2000);
                    } else {
                        alert(data.message || 'Kuna tatizo.');
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Kuna tatizo kwenye mtandao.');
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            });
        }

        const btnDownloadImg = document.getElementById('btn-download-img');
        const btnDownloadPdf = document.getElementById('btn-download-pdf');
        const statusMsg = document.getElementById('download-status');

        function forceDownload(dataUrl, filename) {
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // --- DOWNLOAD AS PNG ---
        if(btnDownloadImg) {
            btnDownloadImg.addEventListener('click', function() {
                btnDownloadImg.disabled = true;
                btnDownloadPdf.disabled = true;
                statusMsg.style.display = 'block';

                card.classList.add('capture-mode');
                
                setTimeout(() => {
                    html2canvas(card, { scale: 4, useCORS: true, backgroundColor: null }).then(canvas => {
                        card.classList.remove('capture-mode');
                        forceDownload(canvas.toDataURL('image/png', 1.0), 'Business-Card.png');
                        btnDownloadImg.disabled = false;
                        btnDownloadPdf.disabled = false;
                        statusMsg.style.display = 'none';
                    }).catch(err => {
                        console.error(err);
                        alert('Error generating Image');
                        card.classList.remove('capture-mode');
                        btnDownloadImg.disabled = false;
                        btnDownloadPdf.disabled = false;
                        statusMsg.style.display = 'none';
                    });
                }, 300);
            });
        }

        // --- DOWNLOAD AS PDF ---
        if(btnDownloadPdf) {
            btnDownloadPdf.addEventListener('click', async function() {
                btnDownloadImg.disabled = true;
                btnDownloadPdf.disabled = true;
                statusMsg.style.display = 'block';

                card.classList.add('capture-mode');
                
                setTimeout(async () => {
                    try {
                        const { jsPDF } = window.jspdf;
                        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: [85, 55] });
                        
                        // Capture Front
                        const frontEl = document.getElementById('card-front-capture');
                        const canvasFront = await html2canvas(frontEl, { scale: 4, useCORS: true });
                        doc.addImage(canvasFront.toDataURL('image/png', 1.0), 'PNG', 0, 0, 85, 55);
                        
                        doc.addPage();
                        
                        // Capture Back
                        const backEl = document.getElementById('card-back-capture');
                        const canvasBack = await html2canvas(backEl, { scale: 4, useCORS: true });
                        doc.addImage(canvasBack.toDataURL('image/png', 1.0), 'PNG', 0, 0, 85, 55);
                        
                        doc.save('Business-Card.pdf');
                    } catch (error) {
                        console.error(error);
                        alert('Error generating PDF');
                    }
                    
                    card.classList.remove('capture-mode');
                    btnDownloadImg.disabled = false;
                    btnDownloadPdf.disabled = false;
                    statusMsg.style.display = 'none';
                }, 300);
            });
        }
    });
</script>

<style>
/* Base Variables */
.business-card-container {
    --brand-color: #3b82f6;
    --logo-bg: #3b82f6;
    --accent-bg: #3b82f6;
    --icon-bg: rgba(255,255,255,0.1);
    --icon-color: #3b82f6;
}

/* Theme Dark (Original) */
.theme-dark {
    --brand-color: #3b82f6;
    --logo-bg: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    --accent-bg: #3b82f6;
    --icon-bg: rgba(255,255,255,0.1);
    --icon-color: #3b82f6;
}
.theme-dark .card-face { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
.theme-dark .card-back { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); }

/* Theme Blue */
.theme-blue {
    --brand-color: #60a5fa;
    --logo-bg: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    --accent-bg: #60a5fa;
    --icon-bg: rgba(255,255,255,0.15);
    --icon-color: #93c5fd;
}
.theme-blue .card-face { background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%); }
.theme-blue .card-back { background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); }

/* Theme Gold */
.theme-gold {
    --brand-color: #fbbf24;
    --logo-bg: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    --accent-bg: #fbbf24;
    --icon-bg: rgba(251, 191, 36, 0.15);
    --icon-color: #fcd34d;
}
.theme-gold .card-face { background: linear-gradient(135deg, #27272a 0%, #18181b 100%); border-color: rgba(251, 191, 36, 0.2); }
.theme-gold .card-back { background: linear-gradient(135deg, #18181b 0%, #09090b 100%); }

/* Theme Green */
.theme-green {
    --brand-color: #10b981;
    --logo-bg: linear-gradient(135deg, #10b981 0%, #059669 100%);
    --accent-bg: #10b981;
    --icon-bg: rgba(16, 185, 129, 0.15);
    --icon-color: #34d399;
}
.theme-green .card-face { background: linear-gradient(135deg, #064e3b 0%, #047857 100%); }
.theme-green .card-back { background: linear-gradient(135deg, #047857 0%, #059669 100%); }

/* Theme Red */
.theme-red {
    --brand-color: #ef4444;
    --logo-bg: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
    --accent-bg: #ef4444;
    --icon-bg: rgba(239, 68, 68, 0.15);
    --icon-color: #fca5a5;
}
.theme-red .card-face { background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); }
.theme-red .card-back { background: linear-gradient(135deg, #991b1b 0%, #b91c1c 100%); }

/* Theme Purple */
.theme-purple {
    --brand-color: #a855f7;
    --logo-bg: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%);
    --accent-bg: #a855f7;
    --icon-bg: rgba(168, 85, 247, 0.15);
    --icon-color: #d8b4fe;
}
.theme-purple .card-face { background: linear-gradient(135deg, #4c1d95 0%, #5b21b6 100%); }
.theme-purple .card-back { background: linear-gradient(135deg, #5b21b6 0%, #6d28d9 100%); }

/* Theme Orange */
.theme-orange {
    --brand-color: #f97316;
    --logo-bg: linear-gradient(135deg, #f97316 0%, #c2410c 100%);
    --accent-bg: #f97316;
    --icon-bg: rgba(249, 115, 22, 0.15);
    --icon-color: #fdba74;
}
.theme-orange .card-face { background: linear-gradient(135deg, #7c2d12 0%, #9a3412 100%); }
.theme-orange .card-back { background: linear-gradient(135deg, #9a3412 0%, #c2410c 100%); }

/* Theme Teal */
.theme-teal {
    --brand-color: #14b8a6;
    --logo-bg: linear-gradient(135deg, #14b8a6 0%, #0f766e 100%);
    --accent-bg: #14b8a6;
    --icon-bg: rgba(20, 184, 166, 0.15);
    --icon-color: #5eead4;
}
.theme-teal .card-face { background: linear-gradient(135deg, #134e4a 0%, #115e59 100%); }
.theme-teal .card-back { background: linear-gradient(135deg, #115e59 0%, #0f766e 100%); }

/* Theme Pink */
.theme-pink {
    --brand-color: #ec4899;
    --logo-bg: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
    --accent-bg: #ec4899;
    --icon-bg: rgba(236, 72, 153, 0.15);
    --icon-color: #f9a8d4;
}
.theme-pink .card-face { background: linear-gradient(135deg, #831843 0%, #9d174d 100%); }
.theme-pink .card-back { background: linear-gradient(135deg, #9d174d 0%, #be185d 100%); }

/* Theme Indigo */
.theme-indigo {
    --brand-color: #6366f1;
    --logo-bg: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
    --accent-bg: #6366f1;
    --icon-bg: rgba(99, 102, 241, 0.15);
    --icon-color: #a5b4fc;
}
.theme-indigo .card-face { background: linear-gradient(135deg, #312e81 0%, #3730a3 100%); }
.theme-indigo .card-back { background: linear-gradient(135deg, #3730a3 0%, #4338ca 100%); }

/* Theme Cyan */
.theme-cyan {
    --brand-color: #06b6d4;
    --logo-bg: linear-gradient(135deg, #06b6d4 0%, #0369a1 100%);
    --accent-bg: #06b6d4;
    --icon-bg: rgba(6, 182, 212, 0.15);
    --icon-color: #67e8f9;
}
.theme-cyan .card-face { background: linear-gradient(135deg, #164e63 0%, #155e75 100%); }
.theme-cyan .card-back { background: linear-gradient(135deg, #155e75 0%, #0e7490 100%); }

/* Theme Lime */
.theme-lime {
    --brand-color: #84cc16;
    --logo-bg: linear-gradient(135deg, #84cc16 0%, #4d7c0f 100%);
    --accent-bg: #84cc16;
    --icon-bg: rgba(132, 204, 22, 0.15);
    --icon-color: #d9f99d;
}
.theme-lime .card-face { background: linear-gradient(135deg, #365314 0%, #3f6212 100%); }
.theme-lime .card-back { background: linear-gradient(135deg, #3f6212 0%, #4d7c0f 100%); }

/* Theme Emerald */
.theme-emerald {
    --brand-color: #10b981;
    --logo-bg: linear-gradient(135deg, #10b981 0%, #047857 100%);
    --accent-bg: #10b981;
    --icon-bg: rgba(16, 185, 129, 0.15);
    --icon-color: #6ee7b7;
}
.theme-emerald .card-face { background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); }
.theme-emerald .card-back { background: linear-gradient(135deg, #065f46 0%, #047857 100%); }

/* Theme Rose */
.theme-rose {
    --brand-color: #f43f5e;
    --logo-bg: linear-gradient(135deg, #f43f5e 0%, #be123c 100%);
    --accent-bg: #f43f5e;
    --icon-bg: rgba(244, 63, 94, 0.15);
    --icon-color: #fda4af;
}
.theme-rose .card-face { background: linear-gradient(135deg, #881337 0%, #9f1239 100%); }
.theme-rose .card-back { background: linear-gradient(135deg, #9f1239 0%, #be123c 100%); }

/* Theme Slate */
.theme-slate {
    --brand-color: #64748b;
    --logo-bg: linear-gradient(135deg, #64748b 0%, #334155 100%);
    --accent-bg: #64748b;
    --icon-bg: rgba(100, 116, 139, 0.15);
    --icon-color: #cbd5e1;
}
.theme-slate .card-face { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
.theme-slate .card-back { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); }

/* Floating shapes for urembo */
.floating-shape {
    position: absolute;
    border-radius: 50%;
    background: var(--brand-color);
    opacity: 0.15;
    filter: blur(25px);
    z-index: 0;
    pointer-events: none;
    transition: background 0.5s ease;
}
.shape-1 { width: 180px; height: 180px; top: -50px; left: -50px; }
.shape-2 { width: 120px; height: 120px; bottom: -30px; right: -30px; }
.shape-3 { width: 250px; height: 250px; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.05; }

/* Modern Business Card Styles */
.business-card-container {
    perspective: 1000px;
    width: 450px;
    height: 260px;
    margin: 20px auto;
}

.business-card {
    width: 100%;
    height: 100%;
    position: relative;
    transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform-style: preserve-3d;
    cursor: pointer;
}



.card-face {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border: 1px solid rgba(255,255,255,0.1);
    transition: background 0.5s ease;
}

.card-back {
    transform: rotateY(180deg);
}

/* Glassmorphism overlays */
.card-glass-overlay {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        to bottom right,
        rgba(255,255,255,0.1) 0%,
        rgba(255,255,255,0.05) 40%,
        rgba(255,255,255,0) 50%,
        rgba(255,255,255,0) 100%
    );
    transform: rotate(30deg);
    pointer-events: none;
    z-index: 0;
}

/* Front Details */
.logo-wrapper {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    padding: 5px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.shop-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 50%;
}

.logo-placeholder {
    width: 80px;
    height: 80px;
    background: var(--logo-bg);
    border-radius: 50%;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    transition: background 0.5s ease;
}

.accent-line {
    width: 40px;
    height: 3px;
    background: var(--accent-bg);
    border-radius: 3px;
    transition: background 0.5s ease;
}

.tracking-wider {
    letter-spacing: 2px;
}

/* Back Details */
.icon-box {
    width: 28px;
    height: 28px;
    background: var(--icon-bg);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--icon-color);
    transition: all 0.5s ease;
    font-size: 0.9rem;
}

.info-section span {
    font-size: 0.85rem;
}

.qr-section {
    border: 3px solid rgba(255,255,255,0.2);
    background: white;
}

.qr-section svg, .qr-section img {
    border-radius: 4px;
}

/* Print Styles */
@media print {
    body { background: white !important; }
    .wrapper, .sidebar, .top-navbar, .d-print-none { display: none !important; }
    #content { margin: 0; padding: 0; width: 100%; }
    
    .business-card-container {
        margin: 0;
        perspective: none;
        width: 100%;
        height: auto;
    }
    
    .business-card {
        transform: none !important;
        transition: none;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    
    .card-face {
        position: relative;
        transform: none !important;
        page-break-inside: avoid;
        box-shadow: none;
        border: 2px solid #ccc;
        width: 85mm;
        height: 55mm;
        margin: 0 auto;
        border-radius: 5mm;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .card-back {
        transform: rotateY(0deg) !important;
    }
}

/* Capture Mode for Save Image */
.capture-mode.business-card-container {
    height: auto;
    perspective: none;
}
.capture-mode .business-card {
    transform: none !important;
    transition: none;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.capture-mode .card-face {
    position: relative !important;
    transform: none !important;
    box-shadow: none;
    border: 2px solid #e2e8f0;
}
    .business-card-container.flipped .business-card {
        transform: rotateY(180deg);
    }

    /* Scaling for responsiveness */
    @media (max-width: 768px) {
        .business-card-container {
            transform: scale(0.55);
            transform-origin: top center;
            margin-bottom: -150px;
        }
    }
    @media (max-width: 480px) {
        .business-card-container {
            transform: scale(0.48);
            transform-origin: top center;
            margin-bottom: -180px;
        }
    }

    .pulse-animation {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: rotate(180deg) scaleX(-1) translateY(0); }
        50% { transform: rotate(180deg) scaleX(-1) translateY(-10px); }
        100% { transform: rotate(180deg) scaleX(-1) translateY(0); }
    }
</style>
@endsection




