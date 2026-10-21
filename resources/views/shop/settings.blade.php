@extends('layouts.admin')

@section('title', __('Shop Settings'))
@section('hide_back_btn', true)

@section('content')
<div class="container-fluid px-0 px-md-2 py-2">

    {{-- Header & Back Button --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="mb-2">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary shadow-xs rounded-3 px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2" style="font-size: 13.5px; text-decoration: none;">
                    <i class="bi bi-arrow-left"></i>
                    <span>{{ __('Back') }}</span>
                </a>
            </div>
            <h3 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-gear-wide-connected text-primary"></i> {{ __('Shop Settings') }}
            </h3>
            <p class="text-muted small mb-0">{{ __('Manage your store profile, logo, business category, and receipt layout') }}</p>
        </div>
        <div>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 fw-semibold">
                <i class="bi bi-shield-check me-1"></i> {{ $shop->business_type ?? __('Verified Store') }}
            </span>
        </div>
    </div>

    <form action="{{ route('shop.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ===== Left Column: Store Logo ===== --}}
            <div class="col-lg-5 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-image text-primary"></i> {{ __('Store Logo') }}
                        </h6>
                    </div>
                    <div class="card-body p-4 text-center d-flex flex-column justify-content-between">
                        <div>
                            {{-- Logo Showcase Canvas --}}
                            <div class="logo-canvas mb-3 position-relative" id="logoDropzone">
                                @if($shop->logo_path)
                                    <img src="{{ asset($shop->logo_path) }}" alt="{{ $shop->name }}" id="logoPreview" class="logo-preview-img">
                                    <div id="logoPlaceholder" class="text-center" style="display:none;">
                                        <i class="bi bi-shop text-muted fs-1 d-block mb-1"></i>
                                        <span class="text-muted small fw-semibold">{{ __('Upload Store Logo') }}</span>
                                    </div>
                                @else
                                    <img src="" alt="" id="logoPreview" class="logo-preview-img" style="display:none;">
                                    <div id="logoPlaceholder" class="text-center py-4">
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-2" style="width:60px;height:60px;">
                                            <i class="bi bi-shop text-primary fs-3"></i>
                                        </div>
                                        <div class="fw-semibold text-dark mb-1">{{ __('No Logo Uploaded') }}</div>
                                        <div class="text-muted small">{{ __('PNG, JPG, or WebP') }}</div>
                                    </div>
                                @endif
                            </div>

                            {{-- Selected File Notification --}}
                            <div id="selectedFileInfo" class="badge bg-light text-primary border px-3 py-2 mb-3 rounded-pill fw-semibold" style="display:none; font-size:12px;"></div>

                            {{-- Upload Input --}}
                            <input type="file" id="logoInput" name="logo" class="d-none" accept="image/png, image/jpeg, image/jpg, image/webp">
                            <label for="logoInput" class="btn btn-outline-primary rounded-3 w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-xs mb-3" style="cursor:pointer; transition:all 0.2s;">
                                <i class="bi bi-cloud-arrow-up-fill fs-5"></i>
                                <span>{{ $shop->logo_path ? __('Change Store Logo') : __('Upload Store Logo') }}</span>
                            </label>
                        </div>

                        <div class="text-muted text-start pt-3 border-top" style="font-size:12px; line-height:1.5;">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            {{ __('Logo will appear on your customer receipts, invoices, and sidebar.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Right Column: Settings Details ===== --}}
            <div class="col-lg-7 col-xl-8">

                {{-- General Store Details Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-building text-primary"></i> {{ __('Store Profile & Business Details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            {{-- Store Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small mb-1">
                                    {{ __('Shop Name') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0" value="{{ old('name', $shop->name) }}" required placeholder="{{ __('e.g. Zamar Store') }}">
                                </div>
                                @error('name') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>

                            {{-- Phone Number --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small mb-1">
                                    {{ __('Phone Number') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" class="form-control border-start-0" value="{{ old('phone', $shop->phone) }}" placeholder="{{ __('e.g. 0712 345 678') }}">
                                </div>
                                @error('phone') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>

                            {{-- Business Category --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-dark small mb-1">
                                    {{ __('Business Category') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-grid"></i></span>
                                    <select id="business_type" name="business_type" class="form-select border-start-0" required style="cursor: pointer;">
                                        <option value="Retail / General" {{ old('business_type', $shop->business_type) == 'Retail / General' ? 'selected' : '' }}>🛍️ {{ __('Retail / General Store') }}</option>
                                        <option value="Electronics / IT" {{ old('business_type', $shop->business_type) == 'Electronics / IT' ? 'selected' : '' }}>📱 {{ __('Electronics & IT (Phones, Computers, Accessories)') }}</option>
                                        <option value="Pharmacy / Health" {{ old('business_type', $shop->business_type) == 'Pharmacy / Health' ? 'selected' : '' }}>💊 {{ __('Pharmacy / Health & Beauty') }}</option>
                                        <option value="Supermarket / Grocery" {{ old('business_type', $shop->business_type) == 'Supermarket / Grocery' ? 'selected' : '' }}>🛒 {{ __('Supermarket / Grocery & Food Mart') }}</option>
                                        <option value="Restaurant / Food" {{ old('business_type', $shop->business_type) == 'Restaurant / Food' ? 'selected' : '' }}>🍽️ {{ __('Restaurant / Cafe / Food & Drinks') }}</option>
                                        <option value="Hardware / Construction" {{ old('business_type', $shop->business_type) == 'Hardware / Construction' ? 'selected' : '' }}>🔨 {{ __('Hardware & Construction Materials') }}</option>
                                        <option value="Clothing / Boutique" {{ old('business_type', $shop->business_type) == 'Clothing / Boutique' ? 'selected' : '' }}>👗 {{ __('Clothing / Boutique & Shoes') }}</option>
                                        <option value="Services / Consulting" {{ old('business_type', $shop->business_type) == 'Services / Consulting' ? 'selected' : '' }}>💼 {{ __('Services & Consulting') }}</option>
                                        <option value="Petroleum Station / Fuel" {{ old('business_type', $shop->business_type) == 'Petroleum Station / Fuel' ? 'selected' : '' }}>⛽ {{ __('Petroleum Station / Fuel') }}</option>
                                    </select>
                                </div>
                                @error('business_type') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>

                            {{-- Physical Location / Address --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-dark small mb-1">
                                    {{ __('Physical Address / Location') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" name="address" class="form-control border-start-0" value="{{ old('address', $shop->address) }}" placeholder="{{ __('e.g. Msimbazi St, Kariakoo, Dar es Salaam') }}">
                                </div>
                                @error('address') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Receipt & Thermal Printer Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-printer text-primary"></i> {{ __('Receipt & Thermal Printer Settings') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            {{-- Thermal Paper Width --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark small mb-2">
                                    {{ __('Thermal Receipt Paper Width') }}
                                </label>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="paper-width-card d-flex align-items-center gap-3 p-3 rounded-3 border {{ old('receipt_paper_width', $shop->receipt_paper_width ?? 80) == 80 ? 'active' : '' }}" for="width80" style="cursor:pointer; transition:all 0.2s;">
                                            <input type="radio" name="receipt_paper_width" id="width80" value="80" class="d-none" {{ old('receipt_paper_width', $shop->receipt_paper_width ?? 80) == 80 ? 'checked' : '' }}>
                                            <div class="paper-icon bg-light rounded-2 p-2 text-primary fs-4">
                                                <i class="bi bi-receipt"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ __('80 mm (Standard POS)') }}</div>
                                                <div class="text-muted" style="font-size:12px;">{{ __('Common desktop thermal printers (Epson, Xprinter)') }}</div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="paper-width-card d-flex align-items-center gap-3 p-3 rounded-3 border {{ old('receipt_paper_width', $shop->receipt_paper_width ?? 80) == 58 ? 'active' : '' }}" for="width58" style="cursor:pointer; transition:all 0.2s;">
                                            <input type="radio" name="receipt_paper_width" id="width58" value="58" class="d-none" {{ old('receipt_paper_width', $shop->receipt_paper_width ?? 80) == 58 ? 'checked' : '' }}>
                                            <div class="paper-icon bg-light rounded-2 p-2 text-primary fs-4">
                                                <i class="bi bi-phone"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ __('58 mm (Compact / Mobile)') }}</div>
                                                <div class="text-muted" style="font-size:12px;">{{ __('Portable handheld Bluetooth thermal printers') }}</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Receipt Footer Note --}}
                            <div class="col-12 mt-3">
                                <label class="form-label fw-semibold text-dark small mb-1">
                                    {{ __('Receipt Footer Thank You Message') }}
                                </label>
                                <textarea name="receipt_message" class="form-control" rows="3" placeholder="{{ __('e.g. Asante sana kwa kufanya biashara nasi! Karibu tena.') }}">{{ old('receipt_message', $shop->receipt_message) }}</textarea>
                                <div class="text-muted small mt-1" style="font-size:12px;">
                                    {{ __('This message will be printed at the bottom of every customer receipt.') }}
                                </div>
                                @error('receipt_message') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Action Bar --}}
                <div class="d-flex justify-content-end gap-2 pt-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2.5 rounded-3 fw-bold shadow-xs" style="text-decoration: none;">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-dark px-5 py-2.5 rounded-3 fw-bold shadow d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ __('Save Changes') }}</span>
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>

<style>
    .logo-canvas {
        width: 100%;
        min-height: 180px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        transition: all 0.2s ease;
    }
    .logo-canvas:hover {
        border-color: #3b82f6;
        background: #f0f7ff;
    }
    .logo-preview-img {
        max-height: 150px;
        max-width: 95%;
        width: auto;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,0.06));
    }
    .paper-width-card {
        background: #ffffff;
        border-color: #e2e8f0;
    }
    .paper-width-card:hover {
        border-color: #93c5fd !important;
        background: #f8fafc;
    }
    .paper-width-card.active {
        border-color: #2563eb !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 2px #2563eb;
    }
    .paper-width-card.active .paper-icon {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    /* Dark Mode Settings Cards */
    [data-theme="dark"] .paper-width-card,
    body.dark-mode .paper-width-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .paper-width-card:hover,
    body.dark-mode .paper-width-card:hover {
        background: #273549 !important;
        border-color: #475569 !important;
    }
    [data-theme="dark"] .paper-width-card.active,
    body.dark-mode .paper-width-card.active {
        background: rgba(37, 99, 235, 0.25) !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px #3b82f6 !important;
    }
    [data-theme="dark"] .paper-width-card .paper-icon,
    body.dark-mode .paper-width-card .paper-icon {
        background: #0f172a !important;
        color: #38bdf8 !important;
        border: 1px solid #334155 !important;
    }
    [data-theme="dark"] .paper-width-card.active .paper-icon,
    body.dark-mode .paper-width-card.active .paper-icon {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #3b82f6 !important;
    }
    [data-theme="dark"] .paper-width-card .fw-bold,
    body.dark-mode .paper-width-card .fw-bold {
        color: #ffffff !important;
    }
    [data-theme="dark"] .paper-width-card .text-muted,
    body.dark-mode .paper-width-card .text-muted {
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .logo-canvas,
    body.dark-mode .logo-canvas {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    [data-theme="dark"] .logo-canvas:hover,
    body.dark-mode .logo-canvas:hover {
        border-color: #3b82f6 !important;
        background: #273549 !important;
    }
    .input-group-text {
        font-size: 0.95rem;
    }
    .form-control, .form-select {
        padding: 0.65rem 0.85rem;
        font-size: 0.925rem;
        border-color: #cbd5e1;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Live image preview
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const sidebarLogoPreview = document.getElementById('sidebarLogoPreview');
    const sidebarLogoTextPreview = document.getElementById('sidebarLogoTextPreview');
    const logoPlaceholder = document.getElementById('logoPlaceholder');
    const selectedFileInfo = document.getElementById('selectedFileInfo');

    logoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                logoPreview.src = event.target.result;
                logoPreview.style.display = 'block';

                if (sidebarLogoPreview) {
                    sidebarLogoPreview.src = event.target.result;
                    sidebarLogoPreview.style.display = 'block';
                }
                if (sidebarLogoTextPreview) {
                    sidebarLogoTextPreview.style.display = 'none';
                }
                if (logoPlaceholder) {
                    logoPlaceholder.style.display = 'none';
                }

                if (selectedFileInfo) {
                    selectedFileInfo.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
                    selectedFileInfo.style.display = 'inline-block';
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // 2. Paper width cards selector
    const paperCards = document.querySelectorAll('.paper-width-card');
    paperCards.forEach(card => {
        card.addEventListener('click', function() {
            paperCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        });
    });
});
</script>
@endsection
