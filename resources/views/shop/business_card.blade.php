@php
    $cat = strtolower($shop->business_type ?? '');
    $userEmail = auth()->user()->email;
@endphp
@extends('layouts.admin')

@section('title', 'Business Card Designer')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@400;600;700;800&family=Outfit:wght@400;600;700;800&family=Playfair+Display:wght@400;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
    :root {
        --bc-primary: #3b82f6;
        --bc-bg: #f1f5f9;
        --bc-border: #e2e8f0;
        --card-radius: 16px;
    }

    * { box-sizing: border-box; }
    body { background-color: var(--bc-bg); font-family: 'Inter', sans-serif; }

    /* PAGE HEADER */
    .bc-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
        position: relative;
        overflow: hidden;
    }
    .bc-header::before {
        content: '';
        position: absolute;
        right: -5%;
        top: -60%;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle, rgba(59,130,246,0.25) 0%, transparent 70%);
        border-radius: 50%;
    }
    .bc-header::after {
        content: '';
        position: absolute;
        left: 30%;
        bottom: -80%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(139,92,246,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* GALLERY */
    .gallery-wrapper {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--bc-border);
        margin-bottom: 2rem;
    }
    .gallery-container {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .gallery-container::-webkit-scrollbar { height: 6px; }
    .gallery-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    .template-card {
        min-width: 180px;
        max-width: 180px;
        border-radius: 12px;
        border: 2.5px solid transparent;
        background: #f8fafc;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        position: relative;
        flex-shrink: 0;
    }
    .template-card:hover { transform: translateY(-3px); box-shadow: 0 8px 16px rgba(0,0,0,0.1); }
    .template-card.active { border-color: var(--bc-primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.2); background: white; }
    
    .template-thumb { height: 110px; background-size: cover; background-position: center; position: relative; }
    .template-info { padding: 0.65rem 0.75rem; background: white; }
    .template-title { font-weight: 700; font-size: 0.85rem; color: #1e293b; margin-bottom: 0.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .template-cat { font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .fav-btn {
        position: absolute;
        top: 6px; right: 6px;
        background: white;
        border-radius: 50%;
        width: 26px; height: 26px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        border: none;
        color: #94a3b8;
        z-index: 2;
        transition: all 0.2s;
        font-size: 12px;
    }
    .fav-btn:hover { transform: scale(1.15); }
    .fav-btn.active { color: #ef4444; }

    /* CATEGORY TABS */
    .cat-tabs {
        display: flex;
        gap: 0.4rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        scrollbar-width: none;
    }
    .cat-tabs::-webkit-scrollbar { display: none; }
    .cat-tab {
        padding: 0.4rem 0.9rem;
        border-radius: 30px;
        background: #f1f5f9;
        border: 1.5px solid transparent;
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .cat-tab:hover { background: #e2e8f0; color: #475569; }
    .cat-tab.active { background: var(--bc-primary); color: white; border-color: var(--bc-primary); }
    .cat-tab.recommended { border-color: #fbbf24; background: #fffbeb; color: #92400e; }
    .cat-tab.recommended.active { background: #f59e0b; color: white; border-color: #f59e0b; }

    /* MAIN EDITOR LAYOUT */
    .editor-layout {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
    }
    .editor-sidebar {
        width: 340px;
        flex-shrink: 0;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid var(--bc-border);
    }
    .preview-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: sticky;
        top: 1rem;
    }

    /* CARD PREVIEW CONTAINER */
    .card-scene {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.5rem;
        background: white;
        border-radius: 16px;
        border: 1px solid var(--bc-border);
        overflow: hidden;
    }
    .card-perspective {
        perspective: 1500px;
        width: 450px;
        height: 260px;
        flex-shrink: 0;
    }
    .card-3d-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
        transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
        border-radius: var(--card-radius);
        box-shadow: 0 20px 40px rgba(0,0,0,0.18);
    }
    .card-3d-wrapper.flipped { transform: rotateY(180deg); }
    
    .card-face {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        border-radius: var(--card-radius);
        overflow: hidden;
        background: white;
        display: flex;
    }
    .card-back { transform: rotateY(180deg); }

    /* ACCORDION */
    .accordion-button { font-weight: 600; color: #334155; padding: 0.9rem 1.25rem; font-size: 0.9rem; }
    .accordion-button:not(.collapsed) { background-color: #eff6ff; color: #1d4ed8; box-shadow: none; }
    .accordion-button:focus { box-shadow: none; }
    .accordion-item { border: none; border-bottom: 1px solid #e2e8f0; }
    .accordion-item:last-child { border-bottom: none; }
    .form-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 0.35rem; }
    .form-control, .form-select { border-radius: 8px; border: 1px solid #cbd5e1; padding: 0.55rem 0.9rem; font-size: 0.9rem; }
    .form-control:focus, .form-select:focus { border-color: var(--bc-primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

    /* COLOR PICKERS ROW */
    .color-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .color-picker-item { display: flex; flex-direction: column; align-items: center; gap: 0.3rem; }
    .color-picker-item label { font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
    .color-swatch {
        width: 40px; height: 40px; border-radius: 50%;
        overflow: hidden; border: 3px solid #e2e8f0; cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .color-swatch:hover { transform: scale(1.1); border-color: #94a3b8; }
    .color-swatch input { width: 200%; height: 200%; transform: translate(-25%, -25%); cursor: pointer; border: none; }

    /* BUTTONS */
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.7rem 1.5rem; border-radius: 10px; font-weight: 700; font-size: 0.9rem;
        transition: all 0.2s; border: none; cursor: pointer;
    }
    .btn-save { background: #10b981; color: white; box-shadow: 0 4px 6px -1px rgba(16,185,129,0.4); }
    .btn-save:hover { background: #059669; transform: translateY(-2px); }
    .btn-flip { background: #1e293b; color: white; }
    .btn-flip:hover { background: #0f172a; }
    .btn-download { background: var(--bc-primary); color: white; box-shadow: 0 4px 6px -1px rgba(59,130,246,0.4); }
    .btn-download:hover { background: #2563eb; transform: translateY(-2px); }

    /* FONTS */
    .font-inter * { font-family: 'Inter', sans-serif !important; }
    .font-roboto * { font-family: 'Roboto', sans-serif !important; }
    .font-outfit * { font-family: 'Outfit', sans-serif !important; }
    .font-playfair * { font-family: 'Playfair Display', serif !important; }
    .font-montserrat * { font-family: 'Montserrat', sans-serif !important; }

    /* RESPONSIVE - TABLET */
    @media (max-width: 1100px) {
        .card-perspective { width: 400px; height: 231px; }
    }

    /* RESPONSIVE - MOBILE */
    @media (max-width: 991px) {
        .editor-layout { flex-direction: column; }
        .editor-sidebar { width: 100%; }
        .preview-area { width: 100%; position: static; }
        .card-scene { padding: 1rem; }
    }
    @media (max-width: 600px) {
        .card-perspective { width: 100%; max-width: 360px; height: auto; aspect-ratio: 450/260; }
        .card-3d-wrapper { border-radius: 12px; }
        .card-face { border-radius: 12px; }
    }
    @media (max-width: 400px) {
        .card-perspective { max-width: 320px; }
        .bc-header h2 { font-size: 1.3rem; }
    }

    /* EXPORT/CAPTURE MODE */
    #capture-wrapper {
        display: none;
        position: fixed;
        left: -9999px;
        top: 0;
    }
    #capture-front, #capture-back {
        width: 450px;
        height: 260px;
        border-radius: 16px;
        overflow: hidden;
        background: white;
        display: flex;
    }
    .export-layout {
        display: flex;
        flex-direction: column;
        gap: 30px;
        background: #000000;
        padding: 40px;
        border-radius: 24px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid pb-5">
    
    <!-- Header -->
    <div class="bc-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div style="position: relative; z-index: 2;">
            <h2 class="fw-bold mb-1" style="letter-spacing: -0.5px;">🎨 Business Card Studio</h2>
            <p class="mb-0" style="color: rgba(255,255,255,0.6); font-size: 0.9rem;">Professional templates tailored to your business category.</p>
        </div>
        <div class="d-flex gap-2" style="position: relative; z-index: 2;">
            <button class="action-btn" id="btn-favs" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                <i class="bi bi-heart-fill text-danger"></i> Favorites
            </button>
        </div>
    </div>

    <!-- GALLERY SECTION -->
    <div class="gallery-wrapper">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-grid-fill me-2 text-primary"></i>Choose a Template</h6>
            <div class="input-group" style="width: 220px;">
                <span class="input-group-text bg-white border-end-0 pe-1"><i class="bi bi-search text-muted" style="font-size: 0.85rem;"></i></span>
                <input type="text" id="search-templates" class="form-control border-start-0 ps-1" placeholder="Search..." style="padding: 0.45rem;">
            </div>
        </div>

        <div class="cat-tabs" id="category-tabs"></div>
        <div class="gallery-container" id="template-gallery"></div>
        
        <div id="empty-gallery" class="text-center py-4 d-none">
            <i class="bi bi-search" style="font-size: 2rem; color: #cbd5e1;"></i>
            <p class="mt-2 mb-0 text-muted small fw-bold">No templates found</p>
        </div>
    </div>

    <!-- EDITOR + PREVIEW LAYOUT -->
    <div class="editor-layout">
        
        <!-- SIDEBAR -->
        <div class="editor-sidebar">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: #f8fafc; border-radius: 16px 16px 0 0;">
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><i class="bi bi-sliders me-2 text-primary"></i>Customize</h6>
                <span class="badge bg-primary rounded-pill" id="template-badge" style="font-size: 0.7rem;">Template</span>
            </div>

            <div class="accordion" id="editorAccordion">
                <!-- INFORMATION -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#acc-info">
                            <i class="bi bi-person-lines-fill me-2"></i> Information
                        </button>
                    </h2>
                    <div id="acc-info" class="accordion-collapse collapse show" data-bs-parent="#editorAccordion">
                        <div class="accordion-body">
                            <div class="mb-2">
                                <label class="form-label">{{ __('Business Name') }}</label>
                                <input type="text" class="form-control" id="inp-name" value="{{ $shop->name }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">{{ __('Tagline') }}</label>
                                <input type="text" class="form-control" id="inp-tagline" value="{{ $shop->card_message ?? 'Scan for Details' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">{{ __('Phone') }}</label>
                                <input type="text" class="form-control" id="inp-phone" value="{{ $shop->card_phone ?? $shop->phone }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input type="email" class="form-control" id="inp-email" value="{{ $shop->card_email ?? $userEmail }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">{{ __('Address') }}</label>
                                <input type="text" class="form-control" id="inp-address" value="{{ $shop->address ?? 'Main Street, City' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">{{ __('Social Username') }}</label>
                                <input type="text" class="form-control" id="inp-social" value="{{ strtolower(str_replace(' ', '_', $shop->name)) }}">
                            </div>
                            <div class="mb-2 mt-3 p-2 border rounded" style="background:#f8fafc">
                                <label class="form-label text-primary" style="font-size:0.85rem;"><i class="bi bi-image me-1"></i>{{ __('Custom Logo (Optional)') }}</label>
                                <input type="file" class="form-control form-control-sm" id="inp-logo-upload" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BRANDING -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc-brand">
                            <i class="bi bi-palette-fill me-2"></i> {{ __('Colors & Font') }}
                        </button>
                    </h2>
                    <div id="acc-brand" class="accordion-collapse collapse" data-bs-parent="#editorAccordion">
                        <div class="accordion-body">
                            
                            <label class="form-label mb-2">{{ __('Card Colors') }}</label>
                            <div class="color-row">
                                <div class="color-picker-item">
                                    <div class="color-swatch"><input type="color" id="inp-color-primary" value="{{ $shop->card_color ?? '#3b82f6' }}"></div>
                                    <label>{{ __('Primary') }}</label>
                                </div>
                                <div class="color-picker-item">
                                    <div class="color-swatch"><input type="color" id="inp-color-bg" value="#ffffff"></div>
                                    <label>{{ __('Background') }}</label>
                                </div>
                                <div class="color-picker-item">
                                    <div class="color-swatch"><input type="color" id="inp-color-text" value="#1e293b"></div>
                                    <label>{{ __('Text') }}</label>
                                </div>
                                <div class="color-picker-item">
                                    <div class="color-swatch"><input type="color" id="inp-color-accent" value="#f59e0b"></div>
                                    <label>{{ __('Accent') }}</label>
                                </div>
                            </div>

                            <label class="form-label mt-2">{{ __('Typography') }}</label>
                            <select class="form-select" id="inp-font">
                                <option value="font-inter">Inter (Modern)</option>
                                <option value="font-roboto">Roboto (Clean)</option>
                                <option value="font-outfit">Outfit (Geometric)</option>
                                <option value="font-montserrat">Montserrat (Bold)</option>
                                <option value="font-playfair">Playfair (Elegant)</option>
                            </select>

                            <div class="mt-3 p-2 rounded d-flex align-items-center gap-2" style="background: #eff6ff; font-size: 0.78rem; color: #1d4ed8;">
                                <i class="bi bi-info-circle-fill"></i>
                                <span>{{ __('Logo loads from your Shop Settings automatically, or use the custom upload above.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 border-top" style="background: #f8fafc; border-radius: 0 0 16px 16px;">
                <button class="action-btn btn-save w-100" id="btn-save">
                    <i class="bi bi-cloud-check-fill"></i> {{ __('Save Design') }}
                </button>
            </div>
        </div>

        <!-- PREVIEW -->
        <div class="preview-area">
            <div class="card-scene w-100">
                <div class="d-flex gap-2 mb-4 justify-content-center flex-wrap w-100">
                    <button class="action-btn btn-flip" id="btn-flip">
                        <i class="bi bi-arrow-repeat"></i> {{ __('Flip Card') }}
                    </button>
                    <button class="action-btn btn-download" id="btn-dl-png">
                        <i class="bi bi-download"></i> {{ __('Download Image') }}
                    </button>
                </div>

                <!-- LIVE PREVIEW CARD -->
                <div class="card-perspective" id="live-perspective">
                    <div class="card-3d-wrapper font-inter" id="live-card">
                        <div class="card-face" id="live-front"></div>
                        <div class="card-face card-back" id="live-back"></div>
                    </div>
                </div>

                <div id="export-status" class="mt-3 text-primary fw-bold d-none" style="font-size: 0.85rem;">
                    <span class="spinner-border spinner-border-sm me-2"></span> {{ __('Generating download...') }}
                </div>

                <p class="mt-3 mb-0 text-center text-muted" style="font-size: 0.78rem; max-width: 340px;">
                    <i class="bi bi-qr-code text-primary me-1"></i>
                    {{ __('QR code on the back lets customers instantly save your contact to their phone.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- HIDDEN EXPORT CONTAINER (off-screen, used for html2canvas capture) -->
<div id="capture-wrapper">
    <div class="export-layout" id="capture-layout">
        <div id="capture-front" style="width:450px;height:260px;border-radius:16px;overflow:hidden;display:flex;"></div>
        <div id="capture-back" style="width:450px;height:260px;border-radius:16px;overflow:hidden;display:flex;"></div>
    </div>
</div>

<input type="hidden" id="sys-logo" value="{{ $shop->card_logo ? asset('storage/' . $shop->card_logo) : ($shop->logo ? asset('storage/' . $shop->logo) : '') }}">
<input type="hidden" id="sys-qr" value="{{ $qrCode }}">
<input type="hidden" id="sys-cat" value="{{ $cat }}">
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
'use strict';
document.addEventListener('DOMContentLoaded', () => {

    /* ==========================================
     * DATA
     * ========================================== */
    const imgs = {
        elec1: 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=600&q=80',
        elec2: 'https://images.unsplash.com/photo-1519389953888-9d31c4fcc025?auto=format&fit=crop&w=600&q=80',
        elec3: 'https://images.unsplash.com/photo-1531297172868-9441504a32e8?auto=format&fit=crop&w=600&q=80',
        food1: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80',
        food2: 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=600&q=80',
        food3: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=600&q=80',
        pharm1: 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&w=600&q=80',
        pharm2: 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80',
        pharm3: 'https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=600&q=80',
        fash1: 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=600&q=80',
        fash2: 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=600&q=80',
        fash3: 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=600&q=80',
        const1: 'https://images.unsplash.com/photo-1581166397057-235af2b3c6dd?auto=format&fit=crop&w=600&q=80',
        const2: 'https://images.unsplash.com/photo-1504307651254-35680f356f12?auto=format&fit=crop&w=600&q=80',
        const3: 'https://images.unsplash.com/photo-1541889811995-17364b4c73ee?auto=format&fit=crop&w=600&q=80',
        super1: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80',
        super2: 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?auto=format&fit=crop&w=600&q=80',
        super3: 'https://images.unsplash.com/photo-1550989460-0adf9ea622e2?auto=format&fit=crop&w=600&q=80',
        serv1: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=600&q=80',
        serv2: 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=600&q=80',
        serv3: 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=600&q=80',
        gen1: 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80',
        gen2: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80',
        gen3: 'https://images.unsplash.com/photo-1507676184212-d0330a15233c?auto=format&fit=crop&w=600&q=80'
    };

    // 10 distinct layout designs, reused across every category so each category
    // gets its own full set of 10 templates without needing 80 hand-built layouts.
    const LAYOUTS = [
        { key: 'zamar',    name: 'Pro Split' },
        { key: 'modern',   name: 'Modern Dark' },
        { key: 'minimal',  name: 'Minimal Clean' },
        { key: 'wave',     name: 'Wave Curve' },
        { key: 'luxury',   name: 'Luxury Noir' },
        { key: 'stripe',   name: 'Dynamic Stripe' },
        { key: 'badge',    name: 'Seal Badge' },
        { key: 'gradient', name: 'Vivid Gradient' },
        { key: 'frame',    name: 'Classic Frame' },
        { key: 'split',    name: 'Bold Split' },
    ];

    // Category metadata: matches the real business_type options in Shop Settings.
    const CATEGORY_INFO = {
        general:      { label: 'General',      imgs: [imgs.gen1, imgs.gen2, imgs.gen3],
            colors: ['#3b82f6','#334155','#8b5cf6','#0ea5e9','#78716c','#ef4444','#6366f1','#2563eb','#0f766e','#be123c'] },
        electronics:  { label: 'Electronics',  imgs: [imgs.elec1, imgs.elec2, imgs.elec3],
            colors: ['#3b82f6','#0f172a','#0ea5e9','#0891b2','#1d4ed8','#4f46e5','#7c3aed','#2563eb','#155e75','#1e40af'] },
        pharmacy:     { label: 'Pharmacy',     imgs: [imgs.pharm1, imgs.pharm2, imgs.pharm3],
            colors: ['#10b981','#059669','#06b6d4','#0d9488','#065f46','#16a34a','#0891b2','#14b8a6','#047857','#0e7490'] },
        supermarket:  { label: 'Supermarket',  imgs: [imgs.super1, imgs.super2, imgs.super3],
            colors: ['#16a34a','#15803d','#84cc16','#22c55e','#4d7c0f','#ea580c','#65a30d','#059669','#365314','#ca8a04'] },
        restaurant:   { label: 'Restaurant',   imgs: [imgs.food1, imgs.food2, imgs.food3],
            colors: ['#ef4444','#b91c1c','#f59e0b','#dc2626','#7c2d12','#ea580c','#c2410c','#f97316','#991b1b','#be123c'] },
        construction: { label: 'Construction', imgs: [imgs.const1, imgs.const2, imgs.const3],
            colors: ['#f59e0b','#451a03','#d97706','#b45309','#78350f','#ea580c','#a16207','#f97316','#92400e','#c2410c'] },
        fashion:      { label: 'Fashion',      imgs: [imgs.fash1, imgs.fash2, imgs.fash3],
            colors: ['#ec4899','#18181b','#f43f5e','#db2777','#4c0519','#be185d','#a21caf','#e11d48','#701a75','#9d174d'] },
        services:     { label: 'Services',     imgs: [imgs.serv1, imgs.serv2, imgs.serv3],
            colors: ['#7c3aed','#1e1b4b','#6366f1','#4f46e5','#312e81','#5b21b6','#4338ca','#6d28d9','#1e3a8a','#3730a3'] },
    };

    // Build all 80 templates (8 categories x 10 layouts) from the data above.
    const templates = [];
    Object.keys(CATEGORY_INFO).forEach(cat => {
        const info = CATEGORY_INFO[cat];
        LAYOUTS.forEach((layout, i) => {
            templates.push({
                id: `${cat}_${layout.key}`,
                cat,
                name: layout.name,
                img: info.imgs[i % info.imgs.length],
                color: info.colors[i % info.colors.length],
                layout: layout.key,
            });
        });
    });

    const CATS = ['All', 'General', 'Electronics', 'Pharmacy', 'Supermarket', 'Restaurant', 'Construction', 'Fashion', 'Services'];

    /* ==========================================
     * SMART DETECT
     * ========================================== */
    const sysCat = (document.getElementById('sys-cat').value || '').toLowerCase();
    let recCat = 'General';
    if (sysCat.match(/electronic|phone|computer|gadget|\bit\b/)) recCat = 'Electronics';
    else if (sysCat.match(/pharmac|medic|health|clinic|hospital/)) recCat = 'Pharmacy';
    else if (sysCat.match(/supermarket|grocery/)) recCat = 'Supermarket';
    else if (sysCat.match(/restaurant|food|cafe|hotel/)) recCat = 'Restaurant';
    else if (sysCat.match(/hardware|construct|tool|civil/)) recCat = 'Construction';
    else if (sysCat.match(/boutique|fashion|cloth|wear/)) recCat = 'Fashion';
    else if (sysCat.match(/service|consult/)) recCat = 'Services';

    let activeCat = recCat;
    let activeTpl = templates.find(t => t.cat === recCat.toLowerCase()) || templates[0];
    let favs = JSON.parse(localStorage.getItem('zpos_bc_favs') || '[]');
    let showFavs = false;

    /* ==========================================
     * ELEMENT REFS
     * ========================================== */
    const $gallery = document.getElementById('template-gallery');
    const $tabs = document.getElementById('category-tabs');
    const $empty = document.getElementById('empty-gallery');
    const $search = document.getElementById('search-templates');
    const $badge = document.getElementById('template-badge');
    const $liveCard = document.getElementById('live-card');
    const $liveFront = document.getElementById('live-front');
    const $liveBack = document.getElementById('live-back');
    const $capFront = document.getElementById('capture-front');
    const $capBack = document.getElementById('capture-back');

    const inp = {
        name:    document.getElementById('inp-name'),
        tagline: document.getElementById('inp-tagline'),
        phone:   document.getElementById('inp-phone'),
        email:   document.getElementById('inp-email'),
        address: document.getElementById('inp-address'),
        social:  document.getElementById('inp-social'),
        cPrim:   document.getElementById('inp-color-primary'),
        cBg:     document.getElementById('inp-color-bg'),
        cText:   document.getElementById('inp-color-text'),
        cAccent: document.getElementById('inp-color-accent'),
        font:    document.getElementById('inp-font'),
        logoUpload: document.getElementById('inp-logo-upload')
    };

    let sysLogo = document.getElementById('sys-logo').value;
    const sysQr   = document.getElementById('sys-qr').value;
    let customLogoBase64 = '';

    if (inp.logoUpload) {
        inp.logoUpload.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    customLogoBase64 = evt.target.result;
                    sysLogo = customLogoBase64;
                    document.getElementById('sys-logo').value = sysLogo;
                    renderGallery(); // Re-render gallery if visible
                    if(activeTpl) updateAll(); // Re-render live card
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* ==========================================
     * GALLERY RENDERING (does NOT touch the card)
     * ========================================== */
    function renderTabs() {
        $tabs.innerHTML = '';
        CATS.forEach(cat => {
            const b = document.createElement('button');
            const isRec = cat === recCat;
            b.className = 'cat-tab' + (isRec ? ' recommended' : '') + (cat === activeCat ? ' active' : '');
            b.textContent = isRec ? '✨ ' + cat : cat;
            b.onclick = () => { activeCat = cat; renderTabs(); renderGallery(); };
            $tabs.appendChild(b);
        });
    }

    window.toggleFav = (id, e) => {
        e.stopPropagation();
        favs = favs.includes(id) ? favs.filter(f => f !== id) : [...favs, id];
        localStorage.setItem('zpos_bc_favs', JSON.stringify(favs));
        renderGallery();
    };

    function pickTemplate(tpl) {
        activeTpl = tpl;
        inp.cPrim.value = tpl.color;
        $badge.textContent = tpl.name;
        renderGallery();
        renderCard($liveFront, $liveBack);
    }

    function buildThumb(tpl) {
        const isFav = favs.includes(tpl.id);
        const isActive = activeTpl.id === tpl.id;
        const el = document.createElement('div');
        el.className = 'template-card' + (isActive ? ' active' : '');
        el.onclick = () => pickTemplate(tpl);

        let thumbOverlay = '';
        if (tpl.layout === 'zamar') {
            thumbOverlay = `<div style="position:absolute;left:0;top:0;bottom:0;width:44%;background:rgba(255,255,255,0.92);clip-path:polygon(0 0,100% 0,75% 100%,0 100%);"></div><div style="position:absolute;left:0;top:0;bottom:0;width:6px;background:${tpl.color};"></div>`;
        } else if (tpl.layout === 'modern') {
            thumbOverlay = `<div style="position:absolute;inset:0;background:rgba(0,0,0,0.55);"></div>`;
        } else if (tpl.layout === 'minimal') {
            thumbOverlay = `<div style="position:absolute;left:0;top:0;bottom:0;width:35%;background:rgba(0,0,0,0.35);"></div><div style="position:absolute;right:0;top:0;bottom:0;width:65%;background:rgba(255,255,255,0.9);"></div>`;
        } else if (tpl.layout === 'wave') {
            thumbOverlay = `<div style="position:absolute;inset:0;background:${tpl.color};clip-path:ellipse(62% 75% at 22% 50%);opacity:0.92;"></div>`;
        } else if (tpl.layout === 'luxury') {
            thumbOverlay = `<div style="position:absolute;inset:0;background:rgba(0,0,0,0.78);"></div><div style="position:absolute;inset:8px;border:1px solid ${tpl.color};"></div>`;
        } else if (tpl.layout === 'stripe') {
            thumbOverlay = `<div style="position:absolute;inset:0;clip-path:polygon(0 0,48% 0,38% 100%,0 100%);background:${tpl.color};"></div>`;
        } else if (tpl.layout === 'badge') {
            thumbOverlay = `<div style="position:absolute;inset:0;background:${tpl.color};opacity:0.85;"></div><div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:30px;height:30px;border-radius:50%;background:white;border:2px solid rgba(255,255,255,0.8);"></div>`;
        } else if (tpl.layout === 'gradient') {
            thumbOverlay = `<div style="position:absolute;inset:0;background:linear-gradient(135deg,${tpl.color} 0%,rgba(255,255,255,0.25) 100%);"></div>`;
        } else if (tpl.layout === 'frame') {
            thumbOverlay = `<div style="position:absolute;inset:0;background:rgba(0,0,0,0.15);"></div><div style="position:absolute;inset:6px;border:2px solid white;"></div>`;
        } else if (tpl.layout === 'split') {
            thumbOverlay = `<div style="position:absolute;left:0;top:0;bottom:0;width:45%;background:${tpl.color};"></div>`;
        }

        el.innerHTML = `
            <button class="fav-btn${isFav ? ' active' : ''}" onclick="window.toggleFav('${tpl.id}',event)">
                <i class="bi bi-heart${isFav ? '-fill' : ''}"></i>
            </button>
            <div class="template-thumb" style="background-image:url('${tpl.img}');">${thumbOverlay}</div>
            <div class="template-info">
                <div class="template-title">${tpl.name}</div>
                <div class="template-cat">${tpl.cat}</div>
            </div>`;
        return el;
    }

    function renderGallery(q = '') {
        $gallery.innerHTML = '';
        let list = templates;
        if (showFavs) {
            list = templates.filter(t => favs.includes(t.id));
        } else if (q) {
            list = templates.filter(t => t.name.toLowerCase().includes(q) || t.cat.toLowerCase().includes(q));
        } else if (activeCat !== 'All') {
            list = templates.filter(t => t.cat === activeCat.toLowerCase());
        }

        if (list.length === 0) {
            $empty.classList.remove('d-none');
        } else {
            $empty.classList.add('d-none');
            list.forEach(t => $gallery.appendChild(buildThumb(t)));
        }
    }

    /* ==========================================
     * CARD RENDERING
     * ========================================== */
    function getVals() {
        return {
            name:    inp.name.value    || 'Business Name',
            tag:     inp.tagline.value || 'Your Tagline',
            phone:   inp.phone.value   || 'Phone Number',
            email:   inp.email.value   || 'Email Address',
            addr:    inp.address.value || 'Address',
            social:  inp.social.value  || 'username',
            prim:    inp.cPrim.value,
            bg:      inp.cBg.value,
            text:    inp.cText.value,
            accent:  inp.cAccent.value,
        };
    }

    // Truncation helper for card (prevents text overflow)
    const trunc = (str, n) => str.length > n ? str.slice(0, n) + '…' : str;

    function renderFrontZamar(el, v, tpl) {
        el.innerHTML = `
            <div style="width:62%;background:${v.bg};padding:1.5rem 1.5rem 1.5rem 1.75rem;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden;">
                <div style="position:absolute;left:0;top:0;bottom:0;width:16px;background:${v.prim};clip-path:polygon(0 0,100% 0,60% 100%,0 100%);"></div>
                <img src="${sysLogo}" style="height:55px;object-fit:contain;margin-bottom:10px;align-self:flex-start;max-width:120px;" onerror="this.style.display='none'">
                <h3 style="font-weight:800;color:${v.prim};margin:0 0 4px;font-size:1.25rem;line-height:1.1;letter-spacing:0.3px;">${trunc(v.name.toUpperCase(), 22)}</h3>
                <p style="font-weight:600;color:${v.text};margin:0 0 14px;font-size:0.8rem;opacity:0.75;">${trunc(v.tag, 30)}</p>
                <div style="display:flex;gap:14px;font-size:0.65rem;font-weight:700;color:#64748b;align-items:center;">
                    <span style="display:flex;align-items:center;gap:3px;"><i class="bi bi-clock" style="color:${v.prim};"></i> FAST</span>
                    <span style="display:flex;align-items:center;gap:3px;"><i class="bi bi-shield-check" style="color:${v.prim};"></i> SAFE</span>
                    <span>RELIABLE</span>
                </div>
            </div>
            <div style="width:38%;background-image:url('${tpl.img}');background-size:cover;background-position:center;"></div>`;
    }

    function renderBackZamar(el, v, tpl) {
        el.innerHTML = `
            <div style="width:60%;background:${v.prim};padding:1.5rem;color:white;display:flex;flex-direction:column;justify-content:center;">
                <div style="display:flex;align-items:center;margin-bottom:2px;">
                    <i class="bi bi-person-circle" style="font-size:1.2rem;margin-right:8px;"></i>
                    <h4 style="font-weight:800;margin:0;font-size:1rem;letter-spacing:0.5px;">${trunc(v.name.toUpperCase(), 20)}</h4>
                </div>
                <span style="font-size:0.7rem;opacity:0.8;display:block;margin-bottom:1.25rem;margin-left:1.7rem;">Head Office</span>
                <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;">
                    <div style="min-width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;margin-top:2px;"><i class="bi bi-telephone-fill" style="font-size:10px;"></i></div>
                    <div><span style="display:block;font-size:0.6rem;opacity:0.8;margin-bottom:2px;">Phone Number</span><div style="font-size:0.8rem;font-weight:700;">${trunc(v.phone, 18)}</div></div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;">
                    <div style="min-width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;margin-top:2px;"><i class="bi bi-envelope-fill" style="font-size:10px;"></i></div>
                    <div><span style="display:block;font-size:0.6rem;opacity:0.8;margin-bottom:2px;">Email Address</span><div style="font-size:0.75rem;font-weight:700;word-break:break-all;">${trunc(v.email, 24)}</div></div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="min-width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;margin-top:2px;"><i class="bi bi-geo-alt-fill" style="font-size:10px;"></i></div>
                    <div><span style="display:block;font-size:0.6rem;opacity:0.8;margin-bottom:2px;">Address</span><div style="font-size:0.8rem;font-weight:700;line-height:1.2;">${trunc(v.addr, 22)}</div></div>
                </div>
            </div>
            <div style="width:3%;background:${v.accent};"></div>
            <div style="width:37%;background-image:url('${tpl.img}');background-size:cover;background-position:center;position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.15);"></div>
                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-65%);background:white;padding:4px;border-radius:8px;border:3px solid ${v.accent};box-shadow:0 6px 12px rgba(0,0,0,0.25);z-index:5;text-align:center;width:95px;">
                    <img src="${sysQr}" style="width:100%;height:auto;border-radius:4px;display:block;">
                    <div style="background:${v.prim};color:white;margin-top:3px;padding:2px 0;border-radius:3px;font-size:0.55rem;font-weight:800;letter-spacing:0.5px;">SCAN TO SCAN</div>
                </div>
                <div style="position:absolute;bottom:0;right:0;background:white;padding:6px 10px;border-top-left-radius:10px;z-index:5;text-align:center;">
                    <div style="font-weight:700;color:#1e293b;font-size:0.55rem;margin-bottom:3px;">Follow us</div>
                    <div style="display:flex;gap:3px;justify-content:center;margin-bottom:3px;">
                        <div style="width:16px;height:16px;background:#1877F2;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;font-size:9px;"><i class="bi bi-facebook"></i></div>
                        <div style="width:16px;height:16px;background:#E1306C;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;font-size:9px;"><i class="bi bi-instagram"></i></div>
                        <div style="width:16px;height:16px;background:#000;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;font-size:9px;"><i class="bi bi-tiktok"></i></div>
                    </div>
                    <div style="font-weight:700;color:#0f172a;font-size:0.55rem;">${trunc(v.social, 14)}</div>
                </div>
            </div>`;
    }

    function renderFrontModern(el, v, tpl) {
        el.innerHTML = `
            <div style="width:100%;background-image:url('${tpl.img}');background-size:cover;background-position:center;position:relative;color:white;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding:2rem;">
                <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(15,23,42,0.95) 0%,rgba(15,23,42,0.55) 100%);border-radius:16px;"></div>
                <div style="position:relative;z-index:2;">
                    <div style="background:white;width:65px;height:65px;border-radius:50%;padding:4px;margin:0 auto 12px;box-shadow:0 4px 10px rgba(0,0,0,0.3);">
                        <img src="${sysLogo}" style="width:100%;height:100%;object-fit:contain;border-radius:50%;" onerror="this.style.display='none'">
                    </div>
                    <h3 style="font-weight:800;margin:0;font-size:1.4rem;letter-spacing:1px;">${trunc(v.name.toUpperCase(), 20)}</h3>
                    <div style="width:35px;height:3px;background:${v.prim};margin:10px auto 8px;"></div>
                    <p style="font-weight:500;font-size:0.85rem;opacity:0.85;margin:0;">${trunc(v.tag, 35)}</p>
                </div>
            </div>`;
    }

    function renderBackModern(el, v) {
        el.innerHTML = `
            <div style="width:100%;background:#0f172a;color:white;position:relative;display:flex;overflow:hidden;border-radius:16px;">
                <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:${v.prim};opacity:0.12;filter:blur(35px);border-radius:50%;"></div>
                <div style="width:63%;padding:1.75rem;display:flex;flex-direction:column;justify-content:center;z-index:2;">
                    <h4 style="font-weight:800;margin-bottom:1.25rem;font-size:1rem;color:white;">${trunc(v.name.toUpperCase(), 20)}</h4>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;"><i class="bi bi-telephone-fill" style="color:${v.prim};font-size:13px;min-width:14px;"></i><div style="font-size:0.8rem;opacity:0.9;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${trunc(v.phone, 18)}</div></div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;"><i class="bi bi-envelope-fill" style="color:${v.prim};font-size:13px;min-width:14px;"></i><div style="font-size:0.75rem;opacity:0.9;word-break:break-all;">${trunc(v.email, 22)}</div></div>
                    <div style="display:flex;align-items:flex-start;gap:12px;"><i class="bi bi-geo-alt-fill" style="color:${v.prim};font-size:13px;min-width:14px;margin-top:2px;"></i><div style="font-size:0.8rem;opacity:0.9;line-height:1.3;">${trunc(v.addr, 25)}</div></div>
                </div>
                <div style="width:37%;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:2;border-left:1px solid rgba(255,255,255,0.07);">
                    <div style="background:rgba(255,255,255,0.07);padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);">
                        <img src="${sysQr}" style="width:85px;height:85px;mix-blend-mode:screen;">
                    </div>
                    <div style="margin-top:10px;font-size:0.6rem;font-weight:700;color:${v.prim};letter-spacing:2px;">SCAN ME</div>
                </div>
            </div>`;
    }

    function renderFrontMinimal(el, v, tpl) {
        el.innerHTML = `
            <div style="width:32%;background-image:url('${tpl.img}');background-size:cover;background-position:center;"></div>
            <div style="width:68%;background:${v.bg};padding:1.5rem 1.75rem;display:flex;flex-direction:column;justify-content:center;border-left:4px solid ${v.prim};">
                <img src="${sysLogo}" style="height:48px;object-fit:contain;margin-bottom:12px;align-self:flex-start;max-width:110px;" onerror="this.style.display='none'">
                <h3 style="font-weight:700;color:${v.text};margin:0 0 5px;font-size:1.25rem;line-height:1.15;">${trunc(v.name, 20)}</h3>
                <p style="color:${v.prim};font-weight:600;font-size:0.85rem;margin:0;">${trunc(v.tag, 28)}</p>
            </div>`;
    }

    function renderBackMinimal(el, v) {
        el.innerHTML = `
            <div style="width:100%;background:${v.bg};position:relative;display:flex;overflow:hidden;border-radius:16px;">
                <div style="position:absolute;bottom:0;left:0;right:0;height:5px;background:${v.prim};"></div>
                <div style="width:63%;padding:1.75rem;display:flex;flex-direction:column;justify-content:center;">
                    <h4 style="font-weight:800;margin-bottom:1.25rem;font-size:0.9rem;color:${v.text};text-transform:uppercase;letter-spacing:1px;">Contact Us</h4>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <div style="width:26px;height:26px;border-radius:6px;background:${v.prim}20;color:${v.prim};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-telephone-fill" style="font-size:11px;"></i></div>
                        <div style="font-size:0.8rem;font-weight:600;color:${v.text};overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${trunc(v.phone, 18)}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <div style="width:26px;height:26px;border-radius:6px;background:${v.prim}20;color:${v.prim};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-envelope-fill" style="font-size:11px;"></i></div>
                        <div style="font-size:0.75rem;font-weight:600;color:${v.text};word-break:break-all;">${trunc(v.email, 22)}</div>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <div style="width:26px;height:26px;border-radius:6px;background:${v.prim}20;color:${v.prim};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-geo-alt-fill" style="font-size:11px;"></i></div>
                        <div style="font-size:0.8rem;font-weight:600;color:${v.text};line-height:1.3;">${trunc(v.addr, 22)}</div>
                    </div>
                </div>
                <div style="width:37%;background:#f8fafc;display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:0 16px 16px 0;border-left:1px solid #e2e8f0;">
                    <div style="background:white;padding:8px;border-radius:8px;box-shadow:0 4px 6px rgba(0,0,0,0.06);">
                        <img src="${sysQr}" style="width:90px;height:90px;">
                    </div>
                    <div style="margin-top:8px;font-size:0.6rem;font-weight:700;color:${v.prim};letter-spacing:1px;">SCAN QR</div>
                </div>
            </div>`;
    }

    function renderFrontWave(el, v, tpl) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background-image:url('${tpl.img}');background-size:cover;background-position:center;overflow:hidden;">
                <div style="position:absolute;inset:0;clip-path:ellipse(62% 75% at 22% 50%);background:${v.bg};"></div>
                <div style="position:relative;z-index:2;width:60%;height:100%;padding:1.5rem 1.25rem 1.5rem 1.75rem;display:flex;flex-direction:column;justify-content:center;">
                    <img src="${sysLogo}" style="height:50px;object-fit:contain;margin-bottom:10px;align-self:flex-start;max-width:110px;" onerror="this.style.display='none'">
                    <h3 style="font-weight:800;color:${v.prim};margin:0 0 4px;font-size:1.2rem;line-height:1.15;">${trunc(v.name, 22)}</h3>
                    <p style="font-weight:600;color:${v.text};margin:0;font-size:0.8rem;opacity:0.75;">${trunc(v.tag, 30)}</p>
                    <div style="width:38px;height:4px;border-radius:2px;background:${v.prim};margin-top:12px;"></div>
                </div>
            </div>`;
    }

    function renderBackWave(el, v) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background:${v.bg};overflow:hidden;">
                <div style="position:absolute;inset:0;clip-path:ellipse(58% 78% at 18% 50%);background:${v.prim};"></div>
                <div style="position:relative;z-index:2;width:54%;padding:1.5rem 1.25rem 1.5rem 1.75rem;display:flex;flex-direction:column;justify-content:center;color:white;">
                    <h4 style="font-weight:800;margin:0 0 14px;font-size:0.95rem;letter-spacing:0.3px;">${trunc(v.name.toUpperCase(), 18)}</h4>
                    <div style="display:flex;align-items:center;gap:9px;margin-bottom:9px;font-size:0.78rem;font-weight:600;"><i class="bi bi-telephone-fill"></i><span>${trunc(v.phone, 16)}</span></div>
                    <div style="display:flex;align-items:center;gap:9px;margin-bottom:9px;font-size:0.7rem;font-weight:600;word-break:break-all;"><i class="bi bi-envelope-fill"></i><span>${trunc(v.email, 20)}</span></div>
                    <div style="display:flex;align-items:flex-start;gap:9px;font-size:0.74rem;font-weight:600;"><i class="bi bi-geo-alt-fill" style="margin-top:2px;"></i><span>${trunc(v.addr, 20)}</span></div>
                </div>
                <div style="position:absolute;right:8%;top:50%;transform:translateY(-50%);z-index:2;background:white;padding:7px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.12);border:2px solid ${v.accent};text-align:center;">
                    <img src="${sysQr}" style="width:76px;height:76px;display:block;">
                    <div style="margin-top:4px;font-size:0.5rem;font-weight:800;color:${v.prim};letter-spacing:1px;">SCAN ME</div>
                </div>
            </div>`;
    }

    function renderFrontLuxury(el, v, tpl) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background:#0a0a0a;background-image:url('${tpl.img}');background-size:cover;background-position:center;">
                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.78);"></div>
                <div style="position:absolute;inset:10px;border:1px solid ${v.accent};"></div>
                <div style="position:relative;z-index:2;width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:1.5rem;">
                    <div style="width:44px;height:44px;border-radius:50%;border:1px solid ${v.accent};display:flex;align-items:center;justify-content:center;margin-bottom:10px;overflow:hidden;background:white;">
                        <img src="${sysLogo}" style="width:100%;height:100%;object-fit:contain;" onerror="this.style.display='none'">
                    </div>
                    <h3 style="font-weight:700;color:white;margin:0;font-size:1.15rem;letter-spacing:3px;text-transform:uppercase;">${trunc(v.name, 20)}</h3>
                    <div style="width:26px;height:1px;background:${v.accent};margin:10px auto;"></div>
                    <p style="color:${v.accent};font-weight:500;font-size:0.72rem;letter-spacing:2px;text-transform:uppercase;margin:0;">${trunc(v.tag, 28)}</p>
                </div>
            </div>`;
    }

    function renderBackLuxury(el, v) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background:#0a0a0a;">
                <div style="position:absolute;inset:10px;border:1px solid ${v.accent};"></div>
                <div style="position:relative;z-index:2;width:100%;height:100%;display:flex;align-items:center;padding:0 1.75rem;">
                    <div style="flex:1;color:white;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:11px;"><i class="bi bi-telephone-fill" style="color:${v.accent};font-size:12px;"></i><span style="font-size:0.78rem;font-weight:500;letter-spacing:0.5px;">${trunc(v.phone, 17)}</span></div>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:11px;"><i class="bi bi-envelope-fill" style="color:${v.accent};font-size:12px;"></i><span style="font-size:0.72rem;font-weight:500;word-break:break-all;">${trunc(v.email, 22)}</span></div>
                        <div style="display:flex;align-items:flex-start;gap:10px;"><i class="bi bi-geo-alt-fill" style="color:${v.accent};font-size:12px;margin-top:2px;"></i><span style="font-size:0.76rem;font-weight:500;line-height:1.3;">${trunc(v.addr, 24)}</span></div>
                    </div>
                    <div style="background:white;padding:7px;border-radius:4px;">
                        <img src="${sysQr}" style="width:74px;height:74px;display:block;">
                    </div>
                </div>
            </div>`;
    }

    function renderFrontStripe(el, v, tpl) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;background-image:url('${tpl.img}');background-size:cover;background-position:center;"></div>
                <div style="position:absolute;inset:0;clip-path:polygon(0 0,48% 0,38% 100%,0 100%);background:${v.prim};"></div>
                <div style="position:absolute;inset:0;clip-path:polygon(48% 0,54% 0,44% 100%,38% 100%);background:${v.accent};"></div>
                <div style="position:relative;z-index:2;width:40%;height:100%;padding:1.5rem 0.5rem 1.5rem 1.5rem;display:flex;flex-direction:column;justify-content:center;color:white;">
                    <img src="${sysLogo}" style="height:42px;object-fit:contain;margin-bottom:8px;max-width:90px;" onerror="this.style.display='none'">
                    <h3 style="font-weight:800;margin:0 0 4px;font-size:1.05rem;line-height:1.15;">${trunc(v.name, 18)}</h3>
                    <p style="font-weight:600;font-size:0.72rem;opacity:0.85;margin:0;">${trunc(v.tag, 24)}</p>
                </div>
            </div>`;
    }

    function renderBackStripe(el, v) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background:${v.bg};overflow:hidden;">
                <div style="position:absolute;inset:0;clip-path:polygon(0 0,30% 0,20% 100%,0 100%);background:${v.prim};"></div>
                <div style="position:relative;z-index:2;width:100%;height:100%;display:flex;align-items:center;padding-left:2rem;">
                    <div style="flex:1;">
                        <h4 style="font-weight:800;margin-bottom:12px;font-size:0.9rem;color:${v.text};">${trunc(v.name.toUpperCase(), 20)}</h4>
                        <div style="display:flex;align-items:center;gap:9px;margin-bottom:9px;"><span style="width:22px;height:22px;border-radius:6px;background:${v.prim};color:white;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-telephone-fill" style="font-size:10px;"></i></span><span style="font-size:0.78rem;font-weight:600;color:${v.text};">${trunc(v.phone, 17)}</span></div>
                        <div style="display:flex;align-items:center;gap:9px;margin-bottom:9px;"><span style="width:22px;height:22px;border-radius:6px;background:${v.prim};color:white;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-envelope-fill" style="font-size:10px;"></i></span><span style="font-size:0.72rem;font-weight:600;color:${v.text};word-break:break-all;">${trunc(v.email, 20)}</span></div>
                        <div style="display:flex;align-items:flex-start;gap:9px;"><span style="width:22px;height:22px;border-radius:6px;background:${v.prim};color:white;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;"><i class="bi bi-geo-alt-fill" style="font-size:10px;"></i></span><span style="font-size:0.76rem;font-weight:600;color:${v.text};line-height:1.3;">${trunc(v.addr, 22)}</span></div>
                    </div>
                    <div style="padding-right:1.5rem;">
                        <div style="background:white;padding:7px;border-radius:8px;box-shadow:0 4px 8px rgba(0,0,0,0.08);border:2px solid ${v.prim};">
                            <img src="${sysQr}" style="width:72px;height:72px;display:block;">
                        </div>
                    </div>
                </div>
            </div>`;
    }

    function renderFrontBadge(el, v, tpl) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background-image:url('${tpl.img}');background-size:cover;background-position:center;display:flex;align-items:center;justify-content:center;text-align:center;">
                <div style="position:absolute;inset:0;background:${v.prim};opacity:0.88;"></div>
                <div style="position:relative;z-index:2;">
                    <div style="width:70px;height:70px;border-radius:50%;background:white;border:4px solid ${v.accent};display:flex;align-items:center;justify-content:center;margin:0 auto 10px;box-shadow:0 6px 14px rgba(0,0,0,0.25);">
                        <img src="${sysLogo}" style="width:78%;height:78%;object-fit:contain;" onerror="this.style.display='none'">
                    </div>
                    <h3 style="font-weight:800;color:white;margin:0;font-size:1.1rem;letter-spacing:0.5px;">${trunc(v.name, 20)}</h3>
                    <p style="color:white;opacity:0.85;font-weight:600;font-size:0.75rem;margin:4px 0 0;">${trunc(v.tag, 28)}</p>
                </div>
            </div>`;
    }

    function renderBackBadge(el, v) {
        el.innerHTML = `
            <div style="width:100%;height:100%;background:${v.bg};display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:1.25rem;">
                <div style="background:white;padding:7px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);border:2px solid ${v.prim};margin-bottom:10px;">
                    <img src="${sysQr}" style="width:76px;height:76px;display:block;">
                </div>
                <div style="font-size:0.78rem;font-weight:700;color:${v.text};margin-bottom:3px;">${trunc(v.phone, 20)}</div>
                <div style="font-size:0.7rem;font-weight:600;color:${v.text};opacity:0.8;margin-bottom:3px;word-break:break-all;">${trunc(v.email, 26)}</div>
                <div style="font-size:0.72rem;font-weight:600;color:${v.text};opacity:0.8;">${trunc(v.addr, 28)}</div>
            </div>`;
    }

    function renderFrontGradient(el, v) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background:linear-gradient(135deg,${v.prim} 0%,${v.accent} 100%);overflow:hidden;display:flex;flex-direction:column;justify-content:center;padding:1.5rem 1.75rem;">
                <div style="position:absolute;top:-50px;right:-50px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,0.12);"></div>
                <div style="position:absolute;bottom:-60px;left:-30px;width:130px;height:130px;border-radius:50%;background:rgba(255,255,255,0.08);"></div>
                <div style="position:relative;z-index:2;">
                    <div style="background:white;display:inline-block;padding:6px 10px;border-radius:8px;margin-bottom:12px;">
                        <img src="${sysLogo}" style="height:32px;object-fit:contain;max-width:90px;display:block;" onerror="this.parentElement.style.display='none'">
                    </div>
                    <h3 style="font-weight:800;color:white;margin:0 0 4px;font-size:1.3rem;line-height:1.15;">${trunc(v.name, 20)}</h3>
                    <p style="color:white;opacity:0.9;font-weight:600;font-size:0.82rem;margin:0;">${trunc(v.tag, 28)}</p>
                </div>
            </div>`;
    }

    function renderBackGradient(el, v) {
        el.innerHTML = `
            <div style="width:100%;height:100%;background:linear-gradient(135deg,${v.prim} 0%,${v.accent} 100%);position:relative;overflow:hidden;display:flex;align-items:center;">
                <div style="position:absolute;top:-40px;left:-40px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,0.1);"></div>
                <div style="flex:1;padding:0 1.75rem;color:white;position:relative;z-index:2;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:11px;"><i class="bi bi-telephone-fill" style="font-size:12px;"></i><span style="font-size:0.8rem;font-weight:600;">${trunc(v.phone, 17)}</span></div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:11px;"><i class="bi bi-envelope-fill" style="font-size:12px;"></i><span style="font-size:0.74rem;font-weight:600;word-break:break-all;">${trunc(v.email, 22)}</span></div>
                    <div style="display:flex;align-items:flex-start;gap:10px;"><i class="bi bi-geo-alt-fill" style="font-size:12px;margin-top:2px;"></i><span style="font-size:0.78rem;font-weight:600;line-height:1.3;">${trunc(v.addr, 24)}</span></div>
                </div>
                <div style="padding-right:1.5rem;position:relative;z-index:2;">
                    <div style="background:white;padding:7px;border-radius:10px;">
                        <img src="${sysQr}" style="width:76px;height:76px;display:block;">
                    </div>
                </div>
            </div>`;
    }

    function renderFrontFrame(el, v, tpl) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background:${v.bg};padding:14px;">
                <div style="width:100%;height:100%;border:1.5px solid ${v.prim};position:relative;padding:1.1rem 1.25rem;display:flex;flex-direction:column;justify-content:center;">
                    <div style="position:absolute;inset:4px;border:1px solid ${v.prim};opacity:0.4;"></div>
                    <div style="position:absolute;top:12px;right:12px;width:64px;height:64px;border-radius:6px;overflow:hidden;border:2px solid ${v.prim};">
                        <img src="${tpl.img}" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <img src="${sysLogo}" style="height:40px;object-fit:contain;margin-bottom:10px;max-width:90px;" onerror="this.style.display='none'">
                    <h3 style="font-weight:700;color:${v.text};margin:0 0 4px;font-size:1.1rem;line-height:1.15;">${trunc(v.name, 22)}</h3>
                    <p style="color:${v.prim};font-weight:600;font-size:0.78rem;margin:0;">${trunc(v.tag, 26)}</p>
                </div>
            </div>`;
    }

    function renderBackFrame(el, v) {
        el.innerHTML = `
            <div style="width:100%;height:100%;position:relative;background:${v.bg};padding:14px;">
                <div style="width:100%;height:100%;border:1.5px solid ${v.prim};position:relative;display:flex;align-items:center;padding:0 1.25rem;">
                    <div style="position:absolute;inset:4px;border:1px solid ${v.prim};opacity:0.4;"></div>
                    <div style="flex:1;position:relative;z-index:2;">
                        <h4 style="font-weight:700;margin-bottom:11px;font-size:0.85rem;color:${v.text};text-transform:uppercase;letter-spacing:1px;">${trunc(v.name, 20)}</h4>
                        <div style="display:flex;align-items:center;gap:9px;margin-bottom:8px;"><i class="bi bi-telephone-fill" style="color:${v.prim};font-size:11px;"></i><span style="font-size:0.76rem;font-weight:600;color:${v.text};">${trunc(v.phone, 17)}</span></div>
                        <div style="display:flex;align-items:center;gap:9px;margin-bottom:8px;"><i class="bi bi-envelope-fill" style="color:${v.prim};font-size:11px;"></i><span style="font-size:0.7rem;font-weight:600;color:${v.text};word-break:break-all;">${trunc(v.email, 20)}</span></div>
                        <div style="display:flex;align-items:flex-start;gap:9px;"><i class="bi bi-geo-alt-fill" style="color:${v.prim};font-size:11px;margin-top:2px;"></i><span style="font-size:0.74rem;font-weight:600;color:${v.text};line-height:1.3;">${trunc(v.addr, 22)}</span></div>
                    </div>
                    <div style="position:relative;z-index:2;">
                        <img src="${sysQr}" style="width:66px;height:66px;display:block;">
                    </div>
                </div>
            </div>`;
    }

    function renderFrontSplit(el, v, tpl) {
        const initial = (v.name || 'B').trim().charAt(0).toUpperCase();
        el.innerHTML = `
            <div style="width:45%;height:100%;background:${v.prim};display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative;">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.8rem;color:white;margin-bottom:10px;">${initial}</div>
                <p style="color:white;opacity:0.85;font-weight:700;font-size:0.7rem;text-align:center;padding:0 0.75rem;letter-spacing:0.5px;">${trunc(v.tag, 26)}</p>
            </div>
            <div style="width:55%;height:100%;position:relative;background-image:url('${tpl.img}');background-size:cover;background-position:center;">
                <div style="position:absolute;left:0;right:0;bottom:0;padding:1rem;background:linear-gradient(0deg,rgba(0,0,0,0.75) 0%,transparent 100%);">
                    <h3 style="font-weight:800;color:white;margin:0;font-size:1.05rem;line-height:1.15;">${trunc(v.name, 20)}</h3>
                </div>
            </div>`;
    }

    function renderBackSplit(el, v) {
        el.innerHTML = `
            <div style="width:100%;height:100%;background:${v.bg};display:flex;">
                <div style="width:8px;background:${v.prim};"></div>
                <div style="flex:1;padding:1.5rem;display:flex;flex-direction:column;justify-content:center;">
                    <h4 style="font-weight:800;margin-bottom:12px;font-size:0.9rem;color:${v.text};">${trunc(v.name.toUpperCase(), 22)}</h4>
                    <div style="display:flex;align-items:center;gap:9px;margin-bottom:9px;"><i class="bi bi-telephone-fill" style="color:${v.prim};font-size:12px;"></i><span style="font-size:0.78rem;font-weight:600;color:${v.text};">${trunc(v.phone, 18)}</span></div>
                    <div style="display:flex;align-items:center;gap:9px;margin-bottom:9px;"><i class="bi bi-envelope-fill" style="color:${v.prim};font-size:12px;"></i><span style="font-size:0.72rem;font-weight:600;color:${v.text};word-break:break-all;">${trunc(v.email, 22)}</span></div>
                    <div style="display:flex;align-items:flex-start;gap:9px;"><i class="bi bi-geo-alt-fill" style="color:${v.prim};font-size:12px;margin-top:2px;"></i><span style="font-size:0.76rem;font-weight:600;color:${v.text};line-height:1.3;">${trunc(v.addr, 24)}</span></div>
                </div>
                <div style="display:flex;align-items:center;padding-right:1.25rem;">
                    <div style="background:white;padding:6px;border-radius:8px;box-shadow:0 4px 8px rgba(0,0,0,0.08);">
                        <img src="${sysQr}" style="width:68px;height:68px;display:block;">
                    </div>
                </div>
            </div>`;
    }

    const LAYOUT_RENDERERS = {
        zamar:    [renderFrontZamar, renderBackZamar],
        modern:   [renderFrontModern, renderBackModern],
        minimal:  [renderFrontMinimal, renderBackMinimal],
        wave:     [renderFrontWave, renderBackWave],
        luxury:   [renderFrontLuxury, renderBackLuxury],
        stripe:   [renderFrontStripe, renderBackStripe],
        badge:    [renderFrontBadge, renderBackBadge],
        gradient: [renderFrontGradient, renderBackGradient],
        frame:    [renderFrontFrame, renderBackFrame],
        split:    [renderFrontSplit, renderBackSplit],
    };

    function renderCard(frontEl, backEl) {
        const v = getVals();
        const tpl = activeTpl;
        const fontClass = inp.font.value;

        // Apply font to live card wrapper
        if (frontEl === $liveFront) {
            $liveCard.className = `card-3d-wrapper ${fontClass}`;
        }

        const [renderFront, renderBack] = LAYOUT_RENDERERS[tpl.layout] || LAYOUT_RENDERERS.minimal;
        renderFront(frontEl, v, tpl);
        renderBack(backEl, v, tpl);
    }

    /* ==========================================
     * EVENT LISTENERS
     * ========================================== */
    // Live update on any input change
    Object.values(inp).forEach(el => { if (el) el.addEventListener('input', () => renderCard($liveFront, $liveBack)); });

    // Flip button
    document.getElementById('btn-flip').addEventListener('click', () => {
        $liveCard.classList.toggle('flipped');
    });

    // Favorites button
    document.getElementById('btn-favs').addEventListener('click', function() {
        showFavs = !showFavs;
        this.innerHTML = showFavs
            ? '<i class="bi bi-heart-fill text-danger"></i> All Templates'
            : '<i class="bi bi-heart-fill text-danger"></i> Favorites';
        renderGallery();
    });

    // Search
    $search.addEventListener('input', e => renderGallery(e.target.value.toLowerCase()));

    /* ==========================================
     * SAVE
     * ========================================== */
    document.getElementById('btn-save').addEventListener('click', function() {
        const orig = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("Saving...") }}';
        this.disabled = true;

        fetch('{{ route("shop.business-card.save") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                _token: '{{ csrf_token() }}',
                card_theme: activeTpl.id,
                card_color: inp.cPrim.value,
                card_phone: inp.phone.value,
                card_email: inp.email.value,
                card_message: inp.tagline.value,
                card_logo_data: customLogoBase64
            })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                this.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>{{ __("Saved!") }}';
                this.style.background = '#059669';
                setTimeout(() => { this.innerHTML = orig; this.style.background = ''; this.disabled = false; }, 2500);
            } else {
                alert(d.message || '{{ __("Error saving.") }}'); this.innerHTML = orig; this.disabled = false;
            }
        })
        .catch(() => { alert('Network error.'); this.innerHTML = orig; this.disabled = false; });
    });

    /* ==========================================
     * DOWNLOAD PNG (stacked front+back on black bg)
     * ========================================== */
    document.getElementById('btn-dl-png').addEventListener('click', () => {
        const statusEl = document.getElementById('export-status');
        statusEl.classList.remove('d-none');

        // Render both sides into the offscreen capture containers
        renderCard($capFront, $capBack);
        document.getElementById('capture-wrapper').style.display = 'block';

        setTimeout(() => {
            html2canvas(document.getElementById('capture-layout'), {
                scale: 3,
                useCORS: true,
                backgroundColor: '#000000',
                allowTaint: true,
                logging: false
            }).then(canvas => {
                document.getElementById('capture-wrapper').style.display = 'none';
                statusEl.classList.add('d-none');
                const a = document.createElement('a');
                a.download = 'ZPOS-BusinessCard.png';
                a.href = canvas.toDataURL('image/png', 1.0);
                a.click();
            }).catch(() => {
                document.getElementById('capture-wrapper').style.display = 'none';
                statusEl.classList.add('d-none');
                alert('Download failed. Please try again.');
            });
        }, 600);
    });

    /* ==========================================
     * INITIALISE
     * ========================================== */
    // Restore saved template
    const savedTheme = '{{ $shop->card_theme }}';
    if (savedTheme) {
        const t = templates.find(x => x.id === savedTheme);
        if (t) { activeTpl = t; activeCat = t.cat.charAt(0).toUpperCase() + t.cat.slice(1); $badge.textContent = t.name; }
    }

    renderTabs();
    renderGallery();
    renderCard($liveFront, $liveBack);
});
</script>
@endpush
