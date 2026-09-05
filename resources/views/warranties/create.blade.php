@extends('layouts.admin')

@section('title', __('Create Warrant'))

@section('content')
<!-- Include Summernote CSS/JS for Rich Text Editor -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<!-- Select2 CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    body { background-color: #f4f7fe; }
    .warrant-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: none;
        padding: 30px;
    }
    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    .section-title i { margin-right: 8px; color: #475569; }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 10px 15px;
        font-size: 14px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.1);
    }
    .form-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 4px;
    }
    
    .type-btn {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 500;
        font-size: 13px;
        padding: 6px 16px;
    }
    .type-btn.active {
        background: #ffffff;
        border-color: #cbd5e1;
        color: #0f172a;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .type-btn:first-child { border-top-left-radius: 6px; border-bottom-left-radius: 6px; }
    .type-btn:last-child { border-top-right-radius: 6px; border-bottom-right-radius: 6px; }
    
    .product-row {
        background: #ffffff;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .btn-add-product {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #0f172a;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
    }
    .btn-add-product:hover { background: #f8fafc; }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1; transition: .4s; border-radius: 24px;
    }
    .slider:before {
        position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
        background-color: white; transition: .4s; border-radius: 50%;
    }
    input:checked + .slider { background-color: #3b82f6; }
    input:checked + .slider:before { transform: translateX(20px); }
</style>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div class="d-flex align-items-center">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
            <i class="bi bi-plus"></i>
        </div>
        <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">{{ __('Create Warrant') }}</h4>
    </div>
    <a href="{{ route('warranties.index') }}" class="btn btn-light border bg-white shadow-sm rounded-pill px-4" style="font-weight: 500; font-size: 14px;">
        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
    </a>
</div>

<form action="{{ route('warranties.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <input type="hidden" name="start_date" value="{{ date('Y-m-d') }}">

    <div class="warrant-card mb-4">
        
        <!-- Customer Section -->
        <div class="section-title">
            <i class="bi bi-person-badge"></i> {{ __('Customer Type') }} *
        </div>
        
        <div class="mb-4">
            <button type="button" class="btn type-btn active px-4 rounded-pill" style="pointer-events: none;"><i class="bi bi-person me-1"></i> {{ __('Individual') }}</button>
        </div>
        
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('Customer Phone') }} *</label>
                <input type="text" name="customer_phone" class="form-control" placeholder="0712345678" value="{{ old('customer_phone') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Customer Name') }} *</label>
                <input type="text" name="customer_name" class="form-control" value="{{ isset($sale) && $sale->customer ? $sale->customer->name : old('customer_name') }}">
            </div>
        </div>
        
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('Region') }}</label>
                <select name="region" class="form-select">
                    <option value="">{{ __('Select Region') }}</option>
                    <option value="Arusha">{{ __('Arusha') }}</option>
                    <option value="Dar es Salaam">{{ __('Dar es Salaam') }}</option>
                    <option value="Dodoma">{{ __('Dodoma') }}</option>
                    <option value="Geita">{{ __('Geita') }}</option>
                    <option value="Iringa">{{ __('Iringa') }}</option>
                    <option value="Kagera">{{ __('Kagera') }}</option>
                    <option value="Katavi">{{ __('Katavi') }}</option>
                    <option value="Kigoma">{{ __('Kigoma') }}</option>
                    <option value="Kilimanjaro">{{ __('Kilimanjaro') }}</option>
                    <option value="Lindi">{{ __('Lindi') }}</option>
                    <option value="Manyara">{{ __('Manyara') }}</option>
                    <option value="Mara">{{ __('Mara') }}</option>
                    <option value="Mbeya">{{ __('Mbeya') }}</option>
                    <option value="Morogoro">{{ __('Morogoro') }}</option>
                    <option value="Mtwara">{{ __('Mtwara') }}</option>
                    <option value="Mwanza">{{ __('Mwanza') }}</option>
                    <option value="Njombe">{{ __('Njombe') }}</option>
                    <option value="Pemba Kaskazini">{{ __('Pemba Kaskazini') }}</option>
                    <option value="Pemba Kusini">{{ __('Pemba Kusini') }}</option>
                    <option value="Pwani">{{ __('Pwani') }}</option>
                    <option value="Rukwa">{{ __('Rukwa') }}</option>
                    <option value="Ruvuma">{{ __('Ruvuma') }}</option>
                    <option value="Shinyanga">{{ __('Shinyanga') }}</option>
                    <option value="Simiyu">{{ __('Simiyu') }}</option>
                    <option value="Singida">{{ __('Singida') }}</option>
                    <option value="Songwe">{{ __('Songwe') }}</option>
                    <option value="Tabora">{{ __('Tabora') }}</option>
                    <option value="Tanga">{{ __('Tanga') }}</option>
                    <option value="Unguja Kaskazini">{{ __('Unguja Kaskazini') }}</option>
                    <option value="Unguja Kusini">{{ __('Unguja Kusini') }}</option>
                    <option value="Unguja Mjini Magharibi">{{ __('Unguja Mjini Magharibi') }}</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Gender') }} *</label>
                <select name="gender" class="form-select">
                    <option value="">{{ __('Select gender') }}</option>
                    <option value="Male">{{ __('Male') }}</option>
                    <option value="Female">{{ __('Female') }}</option>
                </select>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="form-label">{{ __('Note') }}</label>
            <textarea class="form-control" rows="2"></textarea>
        </div>

        <hr class="border-light my-4">

        <!-- Products Section -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
            <div class="section-title mb-0">
                <i class="bi bi-box-seam"></i> {{ __('Products') }} *
            </div>
            <button type="button" class="btn btn-add-product px-3 py-1" onclick="addProductRow()">
                <i class="bi bi-plus"></i> {{ __('Add Product') }}
            </button>
        </div>
        
        <div class="row mb-2 px-1 d-none d-md-flex">
            <div class="col-md-5"><label class="form-label fw-bold text-dark">{{ __('Product') }}</label></div>
            <div class="col-md-3"><label class="form-label fw-bold text-dark">{{ __('Unit Price (TZS)') }}</label></div>
            <div class="col-md-4"><label class="form-label fw-bold text-dark">{{ __('IMEI / Serial No.') }}</label></div>
        </div>
        
        <div id="products-container">
            <!-- Single Product Row -->
            <div class="row g-2 product-row align-items-center" id="row-0">
                <div class="col-md-5">
                    <select name="products[0][name]" class="form-select product-select" required style="width: 100%;" onchange="updatePrice(this)">
                        <option value="">{{ __('Select or Search Product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->name }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="products[0][price]" class="form-control price-input" placeholder="0">
                </div>
                <div class="col-md-3">
                    <input type="text" name="products[0][serial]" class="form-control" placeholder="IMEI / Serial No.">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-light border text-danger p-2" onclick="removeProductRow(0)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="small text-muted mt-2 mb-4">
            Each product gets its own warranty certificate, public link, and SMS notification. IMEI/Serial numbers must be digits only, up to 15 digits.
        </div>

        <hr class="border-light my-4">

        <!-- Warranty Period -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar3"></i> {{ __('Warranty Period') }} *</label>
                <select name="duration" id="durationSelect" class="form-select" required onchange="calculateExpiry()">
                    <option value="7 Days">{{ __('7 Days') }}</option>
                    <option value="14 Days">{{ __('14 Days') }}</option>
                    <option value="1 month">{{ __('1 month') }}</option>
                    <option value="3 months">{{ __('3 months') }}</option>
                    <option value="6 months">{{ __('6 months') }}</option>
                    <option value="12 months">{{ __('12 months') }}</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar-event"></i> {{ __('Expiry Date') }}</label>
                <input type="date" name="end_date" id="endDateInput" class="form-control bg-light" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required readonly>
                <div class="small text-muted mt-1">{{ __('Expiry date is set automatically') }}</div>
            </div>
        </div>

        <script>
            function calculateExpiry() {
                const duration = document.getElementById('durationSelect').value;
                let days = 7;
                
                if (duration === '14 Days') days = 14;
                else if (duration === '1 month') days = 30;
                else if (duration === '3 months') days = 90;
                else if (duration === '6 months') days = 180;
                else if (duration === '12 months') days = 365;

                const date = new Date();
                date.setDate(date.getDate() + days);
                
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                
                document.getElementById('endDateInput').value = `${year}-${month}-${day}`;
            }
        </script>

        <hr class="border-light my-4">

        <!-- Terms & Conditions -->
        <div class="section-title">
            <i class="bi bi-file-text"></i> {{ __('Terms & Conditions') }}
        </div>
        
        <div class="mb-4">
            @php
                $defaultTerms = "";
            @endphp
            <textarea id="summernote" name="conditions">{!! old('conditions', $defaultTerms) !!}</textarea>
        </div>

        <hr class="border-light my-4">

        <!-- Logo Upload -->
        <div class="mb-4">
            <div class="section-title">
                <i class="bi bi-image"></i> {{ __('Business Logo (Optional)') }}
            </div>
            <input type="file" name="shop_logo" class="form-control" accept="image/*">
            <div class="small text-muted mt-1">{{ __('Upload your shop logo to appear on the warranty certificate.') }}</div>
        </div>

        <hr class="border-light my-4">

        <!-- Theme Selection -->
        <div class="section-title">
            <i class="bi bi-palette"></i> {{ __('Choose Design Theme') }} *
        </div>
        
        <div class="row g-3 mb-4">
            @php
                $themes = [
                    1 => ['name' => 'Blue Professional', 'bg' => 'linear-gradient(135deg, #eff6ff, #bfdbfe)'],
                    2 => ['name' => 'Gold Premium', 'bg' => 'linear-gradient(135deg, #fef3c7, #fde68a)'],
                    3 => ['name' => 'Green Eco', 'bg' => 'linear-gradient(135deg, #dcfce7, #86efac)'],
                    4 => ['name' => 'Dark Modern', 'bg' => 'linear-gradient(135deg, #1e293b, #475569)'],
                    5 => ['name' => 'Purple Royal', 'bg' => 'linear-gradient(135deg, #f3e8ff, #d8b4fe)'],
                    6 => ['name' => 'Classic Certificate', 'bg' => '#fffdf5'],
                    7 => ['name' => 'Red Alert', 'bg' => 'linear-gradient(135deg, #fee2e2, #fca5a5)'],
                    8 => ['name' => 'Minimalist Light', 'bg' => '#f8fafc'],
                    9 => ['name' => 'Orange Energetic', 'bg' => 'linear-gradient(135deg, #ffedd5, #fdba74)'],
                    10 => ['name' => 'Tech Cyan', 'bg' => 'linear-gradient(135deg, #cffafe, #67e8f9)']
                ];
            @endphp
            
            @foreach($themes as $id => $theme)
            <div class="col-md-3 col-sm-6">
                <div class="form-check theme-card border rounded-3 p-2 d-flex align-items-center h-100" style="cursor: pointer;" onclick="document.getElementById('theme{{ $id }}').checked = true; updateThemeSelection();">
                    <input class="form-check-input me-2 ms-1 theme-radio flex-shrink-0" type="radio" name="design_theme" id="theme{{ $id }}" value="{{ $id }}" {{ old('design_theme', '1') == $id ? 'checked' : '' }}>
                    <div style="width: 24px; height: 24px; border-radius: 50%; background: {{ $theme['bg'] }}; margin-right: 10px; border: 1px solid #cbd5e1; flex-shrink-0;"></div>
                    <label class="form-check-label flex-grow-1" for="theme{{ $id }}" style="cursor: pointer; font-size: 13px; line-height: 1.2;">
                        {{ $theme['name'] }}
                    </label>
                </div>
            </div>
            @endforeach
        </div>
        
        <style>
            .theme-card { transition: all 0.2s; background: #ffffff; }
            .theme-card:hover { border-color: #3b82f6 !important; background: #f8fafc; }
            .theme-card.selected { border-color: #3b82f6 !important; background-color: #eff6ff; box-shadow: 0 0 0 1px #3b82f6; }
        </style>

        <script>
            function updateThemeSelection() {
                document.querySelectorAll('.theme-card').forEach(card => card.classList.remove('selected'));
                document.querySelectorAll('.theme-radio:checked').forEach(radio => {
                    radio.closest('.theme-card').classList.add('selected');
                });
            }
            
            document.addEventListener('DOMContentLoaded', updateThemeSelection);
        </script>

        <div class="text-end mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary fw-bold px-5 py-2" style="border-radius: 8px;">
                {{ __('Save Warrant') }}
            </button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 120,
            minHeight: 100,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });

        // Initialize Select2 on the first row
        $('.product-select').select2({
            placeholder: "{{ __('Select or Search Product') }}",
            allowClear: true
        });
    });

    let productCount = 1;
    function addProductRow() {
        // Create options from PHP products array
        let optionsHtml = '<option value="">{{ __('Select or Search Product') }}</option>';
        @foreach($products as $product)
            optionsHtml += `<option value="{{ addslashes($product->name) }}" data-price="{{ $product->price }}">{{ addslashes($product->name) }}</option>`;
        @endforeach

        const html = `
        <div class="row g-2 product-row align-items-center mt-2" id="row-${productCount}">
            <div class="col-md-5">
                <select name="products[${productCount}][name]" class="form-select product-select" required style="width: 100%;" onchange="updatePrice(this)">
                    ${optionsHtml}
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="products[${productCount}][price]" class="form-control price-input" placeholder="0">
            </div>
            <div class="col-md-3">
                <input type="text" name="products[${productCount}][serial]" class="form-control" placeholder="IMEI / Serial No.">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-light border text-danger p-2" onclick="removeProductRow(${productCount})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
        $('#products-container').append(html);
        
        // Initialize Select2 on the newly added select element
        $(`#row-${productCount} .product-select`).select2({
            placeholder: "{{ __('Select or Search Product') }}",
            allowClear: true
        });

        productCount++;
    }

    function removeProductRow(id) {
        if ($('.product-row').length > 1) {
            // Destroy select2 instance before removing to prevent memory leaks
            $(`#row-${id} .product-select`).select2('destroy');
            $(`#row-${id}`).remove();
        } else {
            alert('You must have at least one product.');
        }
    }

    function updatePrice(selectElement) {
        let selectedOption = $(selectElement).find(':selected');
        let price = selectedOption.data('price');
        if (price !== undefined && price !== "") {
            $(selectElement).closest('.product-row').find('.price-input').val(price);
        }
    }
</script>
@endsection
