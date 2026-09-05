@extends('layouts.admin')

@section('title', 'Business Card')

@php
    $bgImage = 'https://loremflickr.com/600/400/business?lock=1';
    
    if (isset($shop->business_type)) {
        $cat = strtolower($shop->business_type);
        if (str_contains($cat, 'restaurant') || str_contains($cat, 'food')) {
            $bgImage = 'https://loremflickr.com/600/400/restaurant,food?lock=2';
        } elseif (str_contains($cat, 'electronic') || str_contains($cat, 'it')) {
            $bgImage = 'https://loremflickr.com/600/400/electronics,computer?lock=3';
        } elseif (str_contains($cat, 'hardware') || str_contains($cat, 'construction')) {
            $bgImage = 'https://loremflickr.com/600/400/construction,hardware?lock=4';
        } elseif (str_contains($cat, 'pharmacy') || str_contains($cat, 'health')) {
            $bgImage = 'https://loremflickr.com/600/400/pharmacy,medical?lock=5';
        } elseif (str_contains($cat, 'supermarket') || str_contains($cat, 'grocery')) {
            $bgImage = 'https://loremflickr.com/600/400/supermarket,grocery?lock=6';
        } elseif (str_contains($cat, 'clothing') || str_contains($cat, 'boutique')) {
            $bgImage = 'https://loremflickr.com/600/400/clothing,boutique?lock=7';
        } elseif (str_contains($cat, 'services') || str_contains($cat, 'consulting')) {
            $bgImage = 'https://loremflickr.com/600/400/office,meeting?lock=8';
        } elseif (str_contains($cat, 'retail') || str_contains($cat, 'general')) {
            $bgImage = 'https://loremflickr.com/600/400/store,shop?lock=9';
        }
    }
@endphp

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">{{ __('Digital Business Card') }}</h4>
            <span class="text-muted small">{{ __('Design and preview your modern contact card') }}</span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Editor Controls (Left Side) -->
        <div class="col-lg-5 order-2 order-lg-1">
            <div class="card border-0 rounded-3 h-100 d-print-none" style="box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);">
                <div class="card-body p-4 p-lg-5">
                    <h5 class="fw-bold mb-4 text-dark" style="letter-spacing: -0.5px;">{{ __('Card Settings') }}</h5>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">{{ __('1. Color Theme') }}</label>
                        <div class="color-swatches-grid d-flex flex-wrap gap-2 mb-3">
                            @php
                                $themes = [
                                    'theme-dark' => '#1e293b',
                                    'theme-blue' => '#3b82f6',
                                    'theme-gold' => '#fbbf24',
                                    'theme-green' => '#10b981',
                                    'theme-red' => '#ef4444',
                                    'theme-purple' => '#a855f7',
                                    'theme-orange' => '#f97316',
                                    'theme-teal' => '#14b8a6',
                                    'theme-pink' => '#ec4899',
                                    'theme-indigo' => '#6366f1',
                                    'theme-cyan' => '#06b6d4',
                                    'theme-lime' => '#84cc16',
                                    'theme-emerald' => '#10b981',
                                    'theme-rose' => '#f43f5e',
                                    'theme-slate' => '#64748b'
                                ];
                            @endphp
                            @foreach($themes as $themeClass => $colorHex)
                                <button type="button" class="color-swatch rounded-circle p-0 border-0 shadow-sm {{ ($shop->card_theme == $themeClass || (!$shop->card_theme && $themeClass == 'theme-dark')) ? 'active' : '' }}" 
                                    onclick="switchTheme('{{ $themeClass }}', this)" 
                                    title="{{ ucfirst(str_replace('theme-', '', $themeClass)) }}"
                                    style="width: 32px; height: 32px; background-color: {{ $colorHex }}; transition: transform 0.2s, box-shadow 0.2s; position: relative; cursor: pointer;">
                                </button>
                            @endforeach
                        </div>
                        
                        <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 rounded-3 border w-100" style="max-width: 300px;">
                            <span class="small fw-bold text-muted"><i class="bi bi-palette me-1"></i> {{ __('Custom Color:') }}</span>
                            <input type="color" id="custom-color-picker" class="form-control form-control-color border-0 p-0 bg-transparent ms-auto" value="{{ $shop->card_color ?? '#3b82f6' }}" style="width: 30px; height: 30px; cursor: pointer;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">{{ __('2. Phone Number') }}</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-none" id="input-phone" value="{{ $shop->card_phone ?? $shop->phone }}" placeholder="{{ __('Example: 07xx xxx xxx') }}" style="font-size: 0.95rem;">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">{{ __('3. Email Address') }}</label>
                        <input type="email" class="form-control form-control-lg bg-light border-0 shadow-none" id="input-email" value="{{ $shop->card_email ?? auth()->user()->email }}" placeholder="{{ __('Example: info@yourshop.com') }}" style="font-size: 0.95rem;">
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label small fw-bold text-dark">{{ __('4. Bottom Message') }}</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-none" id="input-message" value="{{ $shop->card_message }}" placeholder="{{ __('Example: Scan for Details') }}" style="font-size: 0.95rem;">
                    </div>

                    <!-- Action Buttons -->
                    <button id="btn-save-design" class="btn btn-primary fw-bold py-3 shadow-sm rounded-3 w-100 mb-3 action-btn" style="letter-spacing: 0.5px;">
                        <i class="bi bi-check-circle me-2"></i> {{ __('Save Design') }}
                    </button>
                    
                    <div class="row g-2">
                        <div class="col-6">
                            <button id="btn-download-img" class="btn btn-light fw-bold py-2 border w-100 action-btn text-dark">
                                <i class="bi bi-image me-1"></i> {{ __('PNG') }}
                            </button>
                        </div>
                        <div class="col-6">
                            <button id="btn-download-pdf" class="btn btn-light fw-bold py-2 border w-100 action-btn text-dark">
                                <i class="bi bi-file-pdf me-1"></i> {{ __('PDF') }}
                            </button>
                        </div>
                    </div>
                    
                    <a href="{{ route('shop.download-qr') }}" class="btn btn-outline-primary fw-bold py-3 mt-3 w-100 action-btn" style="border-width: 2px;">
                        <i class="bi bi-qr-code-scan me-2"></i> {{ __('Download Website QR Code (For Poster)') }}
                    </a>
                    
                    <div class="mt-3 text-center" id="download-status" style="display: none;">
                        <span class="text-primary small fw-bold"><span class="spinner-border spinner-border-sm me-2"></span> {{ __('Preparing, please wait...') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Container (Right Side) -->
        <div class="col-lg-7 d-flex flex-column align-items-center justify-content-start order-1 order-lg-2">
            <div class="w-100 rounded-3 d-flex flex-column align-items-center justify-content-start pt-4 pt-lg-5 pb-5 position-relative preview-studio">
                
                <!-- Studio Grid Pattern -->
                <div style="position: absolute; inset: 0; background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; opacity: 0.5; pointer-events: none; border-radius: inherit;"></div>
                
                <div class="mb-4 text-center position-relative z-1" style="animation: float-up-down 3s ease-in-out infinite;">
                    <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill fw-bold" style="letter-spacing: 0.5px;">
                        <i class="bi bi-hand-index-thumb text-primary me-1" style="display: inline-block; transform: rotate(90deg);"></i>
                        {{ __('BONYEZA KADI KUIGEUZA / TAP CARD TO FLIP') }}
                    </span>
                </div>

                <div class="card-responsive-wrapper w-100">
                    <div class="business-card-container {{ $shop->card_theme ?? 'theme-dark' }}" id="business-card" style="{{ $shop->card_color ? '--brand-color:'.$shop->card_color.';--logo-bg:'.$shop->card_color.';--accent-bg:'.$shop->card_color.';--icon-color:'.$shop->card_color.';' : '' }}">
                    <div class="business-card">
                        
                        <!-- Front Side -->
                        <div class="card-face card-front overflow-hidden" id="card-front-capture" style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: right center;">
                            <div class="card-glass-overlay" style="display:none;"></div>
                            
                            <div class="slanted-overlay-front"></div>

                            <div class="card-content d-flex flex-column justify-content-center h-100 position-relative z-1" style="width: 60%; padding-left: 25px;">
                                @if($shop->logo_path)
                                    <div class="logo-wrapper mb-2" style="width: 55px; height: 55px; padding: 4px;">
                                        <img src="{{ asset($shop->logo_path) }}" alt="{{ $shop->name }}" class="shop-logo">
                                    </div>
                                @else
                                    <div class="logo-placeholder mb-2 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background: var(--brand-color);">
                                        <span class="fw-bold fs-3 text-white">{{ substr($shop->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <h3 class="fw-bolder mb-1" style="color: var(--brand-color); letter-spacing: 0.5px; font-size: 1.1rem; line-height: 1.1;">{{ strtoupper($shop->name) }}</h3>
                                <div class="accent-line my-1" style="width: 30px; height: 2px;"></div>
                                <p class="mb-0 text-dark fw-bold" id="display-message" style="font-size: 0.7rem; line-height: 1.2;">{{ $shop->card_message ?: 'Scan for Details' }}</p>
                                
                                <div class="d-flex gap-2 mt-3 text-muted" style="font-size: 0.6rem;">
                                    <div><i class="bi bi-clock me-1 text-dark"></i>FAST</div>
                                    <div><i class="bi bi-shield-check me-1 text-dark"></i>SAFE</div>
                                    <div><i class="bi bi-handshake me-1 text-dark"></i>RELIABLE</div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Side -->
                        <div class="card-face card-back overflow-hidden" id="card-back-capture" style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: right center;">
                            <div class="card-glass-overlay" style="display:none;"></div>
                            
                            <div class="slanted-overlay-back"></div>

                            <div class="card-content d-flex flex-row justify-content-between align-items-center h-100 position-relative z-1 w-100 px-3">
                                
                                <div class="info-section text-white flex-grow-1" style="max-width: 55%;">
                                    <h5 class="fw-bold mb-2 text-truncate" style="font-size: 0.85rem;">
                                        <i class="bi bi-person-fill me-1"></i> {{ strtoupper($shop->name) }}<br>
                                        <span class="fw-normal" style="font-size: 0.55rem; opacity: 0.8;">Head Office</span>
                                    </h5>
                                    
                                    @php
                                        $displayPhone = $shop->card_phone ?? $shop->phone;
                                        $displayEmail = $shop->card_email ?? auth()->user()->email;
                                    @endphp

                                    <div class="d-flex align-items-start mb-1" id="box-phone" style="{{ $displayPhone ? '' : 'display:none;' }}">
                                        <i class="bi bi-telephone-fill me-2 mt-1" style="font-size: 0.65rem;"></i>
                                        <div>
                                            <span class="d-block fw-bold" style="font-size: 0.55rem; opacity: 0.8;">Phone Number</span>
                                            <span class="fw-bold text-truncate" id="display-phone" style="font-size: 0.7rem;">{{ $displayPhone }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-start mb-1" id="box-email" style="{{ $displayEmail ? '' : 'display:none;' }}">
                                        <i class="bi bi-envelope-fill me-2 mt-1" style="font-size: 0.65rem;"></i>
                                        <div>
                                            <span class="d-block fw-bold" style="font-size: 0.55rem; opacity: 0.8;">Email Address</span>
                                            <span class="fw-bold text-truncate" id="display-email" style="font-size: 0.7rem; word-break: break-all;">{{ $displayEmail }}</span>
                                        </div>
                                    </div>

                                    @if($shop->address)
                                    <div class="d-flex align-items-start mt-1">
                                        <i class="bi bi-geo-alt-fill me-2 mt-1" style="font-size: 0.65rem;"></i>
                                        <div>
                                            <span class="d-block fw-bold" style="font-size: 0.55rem; opacity: 0.8;">Address</span>
                                            <span class="fw-bold" style="font-size: 0.65rem; line-height: 1.1;">{{ $shop->address }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="qr-wrapper d-flex flex-column align-items-center justify-content-center bg-white rounded-3 p-1 shadow-lg" style="width: 100px; border: 3px solid #fbbf24; transform: translateX(-5px);">
                                    <img src="{{ $qrCode }}" alt="QR Code" style="width: 100%; height: auto; border-radius: 2px;">
                                    <div class="text-white w-100 text-center mt-1 py-1" style="background-color: var(--brand-color); font-size: 0.5rem; font-weight: 800; border-radius: 0 0 2px 2px;">SCAN TO SCAN</div>
                                </div>
                                
                                <div class="social-icons position-absolute bottom-0 end-0 p-2 pe-3 text-center" style="background: rgba(255,255,255,0.85); border-top-left-radius: 10px;">
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.5rem;">Follow us</div>
                                    <div class="d-flex gap-1 justify-content-center mb-1">
                                        <div style="width: 14px; height: 14px; background: #1877F2; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 7px;"><i class="bi bi-facebook"></i></div>
                                        <div style="width: 14px; height: 14px; background: #E1306C; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 7px;"><i class="bi bi-instagram"></i></div>
                                        <div style="width: 14px; height: 14px; background: #000000; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 7px;"><i class="bi bi-tiktok"></i></div>
                                    </div>
                                    <span class="fw-bold text-dark" style="font-size: 0.5rem;">{{ strtolower(str_replace(' ', '_', $shop->name)) }}</span>
                                </div>

                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-3 d-print-none">
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
        // Flip mechanics
        const cardEl = document.querySelector('.business-card');
        if(cardEl) {
            cardEl.addEventListener('click', function() {
                this.classList.toggle('is-flipped');
            });
        }

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

        // --- HELPER FUNCTION TO CAPTURE A FACE ---
        async function captureFace(faceToCapture) {
            const container = document.getElementById('business-card');
            const innerCard = container.querySelector('.business-card');
            const front = document.getElementById('card-front-capture');
            const back = document.getElementById('card-back-capture');
            
            // Temporarily store original styles
            const origContainerPerspective = container.style.perspective;
            const origContainerTransform = container.style.transform;
            const origInnerTransform = innerCard.style.transform;
            const origInnerTransformStyle = innerCard.style.transformStyle;
            
            const origFrontDisplay = front.style.display;
            const origBackDisplay = back.style.display;
            const origFrontPosition = front.style.position;
            const origBackPosition = back.style.position;
            const origFrontTransform = front.style.transform;
            const origBackTransform = back.style.transform;
            const origFrontBoxShadow = front.style.boxShadow;
            const origBackBoxShadow = back.style.boxShadow;
            
            // Flatten 3D structure for html2canvas to prevent rendering bugs
            container.style.perspective = 'none';
            container.style.transform = 'none'; // Un-scale if scaled
            
            innerCard.style.transform = 'none';
            innerCard.style.transformStyle = 'flat';
            
            if (faceToCapture === 'front') {
                back.style.display = 'none'; // Hide back entirely
                front.style.display = 'block';
                front.style.position = 'relative';
                front.style.transform = 'none';
                front.style.boxShadow = 'none'; // Shadows create empty space in capture
            } else {
                front.style.display = 'none'; // Hide front entirely
                back.style.display = 'block';
                back.style.position = 'relative';
                back.style.transform = 'none';
                back.style.boxShadow = 'none';
            }
            
            // Capture the whole container so ALL theme CSS classes resolve natively!
            const canvas = await html2canvas(container, { 
                scale: 2, 
                useCORS: true, 
                backgroundColor: null,
                width: 450,
                height: 260,
                windowWidth: 1200
            });
            
            // Restore styles
            container.style.perspective = origContainerPerspective;
            container.style.transform = origContainerTransform;
            innerCard.style.transform = origInnerTransform;
            innerCard.style.transformStyle = origInnerTransformStyle;
            
            front.style.display = origFrontDisplay;
            back.style.display = origBackDisplay;
            front.style.position = origFrontPosition;
            back.style.position = origBackPosition;
            front.style.transform = origFrontTransform;
            back.style.transform = origBackTransform;
            front.style.boxShadow = origFrontBoxShadow;
            back.style.boxShadow = origBackBoxShadow;
            
            return canvas;
        }

        // --- DOWNLOAD AS PNG ---
        if(btnDownloadImg) {
            btnDownloadImg.addEventListener('click', async function() {
                btnDownloadImg.disabled = true;
                btnDownloadPdf.disabled = true;
                statusMsg.style.display = 'block';
                
                try {
                    // Capture front and back independently via the container
                    const canvasFront = await captureFace('front');
                    const canvasBack = await captureFace('back');
                    
                    // Create a combined canvas
                    const combined = document.createElement('canvas');
                    const ctx = combined.getContext('2d');
                    
                    combined.width = canvasFront.width;
                    combined.height = canvasFront.height + canvasBack.height + 40; // 40px gap
                    
                    // Draw transparent background
                    ctx.clearRect(0, 0, combined.width, combined.height);
                    
                    // Draw front and back
                    ctx.drawImage(canvasFront, 0, 0);
                    ctx.drawImage(canvasBack, 0, canvasFront.height + 40);
                    
                    forceDownload(combined.toDataURL('image/png', 1.0), 'Business-Card.png');
                } catch (err) {
                    console.error(err);
                    alert('Error generating Image');
                }
                
                
                btnDownloadImg.disabled = false;
                btnDownloadPdf.disabled = false;
                statusMsg.style.display = 'none';
            });
        }

        // --- DOWNLOAD AS PDF ---
        if(btnDownloadPdf) {
            btnDownloadPdf.addEventListener('click', async function() {
                btnDownloadImg.disabled = true;
                btnDownloadPdf.disabled = true;
                statusMsg.style.display = 'block';
                
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF({ orientation: 'landscape', unit: 'px', format: [450, 260] });
                    
                    const canvasFront = await captureFace('front');
                    doc.addImage(canvasFront.toDataURL('image/png'), 'PNG', 0, 0, 450, 260);
                    
                    doc.addPage();
                    
                    const canvasBack = await captureFace('back');
                    doc.addImage(canvasBack.toDataURL('image/png'), 'PNG', 0, 0, 450, 260);
                    
                    doc.save('Business-Card.pdf');
                } catch (error) {
                    console.error(error);
                    alert('Error generating PDF');
                }
                
                btnDownloadImg.disabled = false;
                btnDownloadPdf.disabled = false;
                statusMsg.style.display = 'none';
            });
        }
    });
</script>

<style>
@keyframes pulse {
    0% { opacity: 0.7; transform: scale(0.98); }
    50% { opacity: 1; transform: scale(1.02); }
    100% { opacity: 0.7; transform: scale(0.98); }
}

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

/* Floating shapes removed for slanted design */

/* Slanted Overlay Front */
.slanted-overlay-front {
    position: absolute;
    top: -2px;
    bottom: -2px;
    left: -2px;
    width: 65%;
    /* Create the blue stripe on the left (25px wide) using a linear gradient */
    background: linear-gradient(100deg, var(--brand-color) 0%, var(--brand-color) 30px, #ffffff 30px, #ffffff 100%);
    clip-path: polygon(0 0, 100% 0, 80% 100%, 0 100%);
    -webkit-clip-path: polygon(0 0, 100% 0, 80% 100%, 0 100%);
    z-index: 0;
}

/* Slanted Overlay Back */
.slanted-overlay-back {
    position: absolute;
    top: -2px;
    bottom: -2px;
    left: -2px;
    width: 65%;
    /* Use theme gradient for the background */
    background: var(--logo-bg); 
    clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);
    -webkit-clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);
    z-index: 0;
}
/* For the yellow stripe on the right edge of the back card, use an overlay inside a pseudo element that html2canvas handles better (not a clip-path) */
.slanted-overlay-back::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 15px;
    height: 100%;
    background: #fbbf24;
}

.card-responsive-wrapper {
    position: relative;
    width: 100%;
    height: 260px;
    overflow: hidden;
}

.business-card-container {
    perspective: 1000px;
    -webkit-perspective: 1000px;
    width: 450px;
    height: 260px;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    margin: 0;
}

/* Make it responsive on smaller screens */
@media (max-width: 575px) {
    .card-responsive-wrapper {
        height: 195px; /* 260 * 0.75 */
    }
    .business-card-container {
        transform: translateX(-50%) scale(0.75);
        transform-origin: top center;
    }
}
@media (max-width: 400px) {
    .card-responsive-wrapper {
        height: 169px; /* 260 * 0.65 */
    }
    .business-card-container {
        transform: translateX(-50%) scale(0.65);
        transform-origin: top center;
    }
}

.business-card {
    width: 100%;
    height: 100%;
    position: relative;
    transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform-style: preserve-3d;
    -webkit-transform-style: preserve-3d;
    cursor: pointer;
}

.business-card.is-flipped {
    transform: rotateY(180deg);
    -webkit-transform: rotateY(180deg);
}

.card-face {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    border: none;
    transition: background 0.5s ease;
    /* Safari fix */
    -webkit-transform: translate3d(0,0,0);
}

.card-back {
    transform: rotateY(180deg);
    -webkit-transform: rotateY(180deg);
}

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



/* Custom UI Components */
.color-swatch {
    border: 2px solid transparent !important;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
}
.color-swatch:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1) !important;
}
.color-swatch.active {
    border: 2px solid #ffffff !important;
    outline: 2px solid #cbd5e1 !important;
    transform: scale(1.15);
}
.action-btn {
    transition: all 0.2s ease;
}
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05) !important;
}
@keyframes float-up-down {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
    100% { transform: translateY(0px); }
}

/* Preview Studio Sizing */
.preview-studio {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    min-height: 420px;
    box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.02);
}
@media (max-width: 991px) {
    .preview-studio {
        min-height: 340px;
    }
}
@media (max-width: 575px) {
    .preview-studio {
        min-height: 300px;
    }
}
</style>
@endsection
