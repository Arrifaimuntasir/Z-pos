@extends('layouts.admin')

@section('title', __('Edit Warrant'))

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
</style>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div class="d-flex align-items-center">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
            <i class="bi bi-pencil"></i>
        </div>
        <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">{{ __('Edit Warrant') }}: {{ $warranty->warranty_number }}</h4>
    </div>
    <a href="{{ route('warranties.index') }}" class="btn btn-light border bg-white shadow-sm rounded-pill px-4" style="font-weight: 500; font-size: 14px;">
        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
    </a>
</div>

<form action="{{ route('warranties.update', $warranty->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="warrant-card mb-4">
        
        <!-- Customer Section -->
        <div class="section-title">
            <i class="bi bi-person-badge"></i> {{ __('Customer Details') }}
        </div>
        
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('Customer Phone') }} *</label>
                <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $warranty->customer_phone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Customer Name') }} *</label>
                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $warranty->customer_name) }}">
            </div>
        </div>
        
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('Region') }}</label>
                <select name="region" class="form-select">
                    <option value="">{{ __('Select Region') }}</option>
                    @php
                        $regions = ['Arusha', 'Dar es Salaam', 'Dodoma', 'Geita', 'Iringa', 'Kagera', 'Katavi', 'Kigoma', 'Kilimanjaro', 'Lindi', 'Manyara', 'Mara', 'Mbeya', 'Morogoro', 'Mtwara', 'Mwanza', 'Njombe', 'Pemba Kaskazini', 'Pemba Kusini', 'Pwani', 'Rukwa', 'Ruvuma', 'Shinyanga', 'Simiyu', 'Singida', 'Songwe', 'Tabora', 'Tanga', 'Unguja Kaskazini', 'Unguja Kusini', 'Unguja Mjini Magharibi'];
                    @endphp
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ old('region', $warranty->region) == $region ? 'selected' : '' }}>{{ $region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Gender') }} *</label>
                <select name="gender" class="form-select">
                    <option value="">{{ __('Select gender') }}</option>
                    <option value="Male" {{ old('gender', $warranty->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $warranty->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>

        <hr class="border-light my-4">

        <!-- Product Section -->
        <div class="section-title">
            <i class="bi bi-box-seam"></i> {{ __('Product Details') }}
        </div>
        
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('Product Name') }} *</label>
                <select name="product_name" class="form-select product-select" required style="width: 100%;">
                    <option value="">{{ __('Select or Search Product') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->name }}" {{ old('product_name', $warranty->product_name) == $product->name ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('IMEI / Serial No.') }}</label>
                <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $warranty->serial_number) }}">
            </div>
        </div>

        <hr class="border-light my-4">

        <!-- Warranty Period -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar3"></i> {{ __('Warranty Period') }} *</label>
                <select name="duration" id="durationSelect" class="form-select" required onchange="calculateExpiry()">
                    <option value="7 Days" {{ old('duration', $warranty->duration) == '7 Days' ? 'selected' : '' }}>7 Days</option>
                    <option value="14 Days" {{ old('duration', $warranty->duration) == '14 Days' ? 'selected' : '' }}>14 Days</option>
                    <option value="1 month" {{ old('duration', $warranty->duration) == '1 month' ? 'selected' : '' }}>1 month</option>
                    <option value="3 months" {{ old('duration', $warranty->duration) == '3 months' ? 'selected' : '' }}>3 months</option>
                    <option value="6 months" {{ old('duration', $warranty->duration) == '6 months' ? 'selected' : '' }}>6 months</option>
                    <option value="12 months" {{ old('duration', $warranty->duration) == '12 months' ? 'selected' : '' }}>12 months</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar-event"></i> {{ __('Expiry Date') }}</label>
                <input type="date" name="end_date" id="endDateInput" class="form-control bg-light" value="{{ old('end_date', $warranty->end_date->format('Y-m-d')) }}" required readonly>
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

                // For editing, recalculate from the start date if they change duration
                const startDateStr = "{{ $warranty->start_date->format('Y-m-d') }}";
                const date = new Date(startDateStr);
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
            <textarea id="summernote" name="conditions">{!! old('conditions', $warranty->conditions) !!}</textarea>
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
                    <input class="form-check-input me-2 ms-1 theme-radio flex-shrink-0" type="radio" name="design_theme" id="theme{{ $id }}" value="{{ $id }}" {{ old('design_theme', $warranty->design_theme) == $id ? 'checked' : '' }}>
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
                {{ __('Update Warrant') }}
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

        $('.product-select').select2({
            placeholder: "{{ __('Select or Search Product') }}",
            allowClear: true
        });
    });
</script>
@endsection
