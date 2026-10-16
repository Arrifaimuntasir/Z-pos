@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div class="d-flex align-items-center mb-4">
    
    <h4 class="fw-bold mb-0">{{ __('Add New Product') }}</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Product Name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('SKU (Barcode/ID)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', 'PRD-' . rand(100000, 999999)) }}" required>
                    <small class="text-muted">{{ __('You can leave this auto-generated SKU or scan a barcode.') }}</small>
                    @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Category') }} <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">{{ __('Select Category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Brand') }} (Optional)</label>
                    <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                        <option value="">{{ __('Select Brand') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                @if(empty(auth()->user()->shop->package) || strtolower(auth()->user()->shop->package) === 'starter')
                    @if(isset($branches) && $branches->count() > 0)
                        <input type="hidden" name="branch_id" value="{{ $activeBranchId ?? $branches->first()->id }}">
                    @endif
                @else
                    @if(isset($branches) && $branches->count() > 1)
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Branch') }} <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select Branch') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (old('branch_id', $activeBranchId ?? '') == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @else
                        @if(isset($branches) && $branches->count() > 0)
                            <input type="hidden" name="branch_id" value="{{ $activeBranchId ?? $branches->first()->id }}">
                        @endif
                    @endif
                @endif
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Model') }}</label>
                    <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}">
                    @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Unit') }} (Optional)</label>
                    <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror">
                        <option value="">{{ __('Select Unit') }}</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
                        @endforeach
                    </select>
                    @error('unit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Stock Quantity') }} (Optional)</label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" id="stock_input">
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Buying Price') }} (Optional)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">TSh</span>
                        <input type="number" step="0.01" name="cost_price" class="form-control border-start-0 @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}" min="0">
                    </div>
                    @error('cost_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Selling Price') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">TSh</span>
                        <input type="number" step="0.01" name="selling_price" class="form-control border-start-0 @error('selling_price') is-invalid @enderror" value="{{ old('selling_price') }}" min="0" required>
                    </div>
                    @error('selling_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                
                @php
                    $shopCategory = Auth::user()->shop->business_type;
                    $showExpiry = in_array($shopCategory, ['Pharmacy / Health', 'Supermarket / Grocery', 'Restaurant / Food']);
                    $showImei = in_array($shopCategory, ['Electronics / IT']);
                    $isMandatoryStock = in_array($shopCategory, ['Electronics / IT', 'Pharmacy / Health']);
                @endphp
                
                @if($isMandatoryStock ?? false)
                    <input type="hidden" id="track_stock" name="track_stock" value="1">
                @else
                    <div class="col-md-12 mt-4">
                        <hr>
                        @if(auth()->check() && auth()->user()->shop && auth()->user()->shop->business_type == 'Restaurant / Food')
                            <h6 class="fw-bold mb-3">{{ __('Inventory & Recipe Settings') }}</h6>
                        @else
                            <h6 class="fw-bold mb-3">{{ __('Inventory Settings') }}</h6>
                        @endif
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" role="switch" id="track_stock" name="track_stock" value="1" {{ old('track_stock', true) ? 'checked' : '' }} onchange="toggleRecipeSection()">
                            <label class="form-check-label ms-2" for="track_stock">
                                <span class="fw-semibold">Track Stock (Advanced Stock Mode)</span><br>
                                @if(auth()->check() && auth()->user()->shop && auth()->user()->shop->business_type == 'Restaurant / Food')
                                    <small class="text-muted">Turn off for Simple Mode (e.g. cooked meals with no stock tracking). If ON, you can also add a recipe below.</small>
                                @else
                                    <small class="text-muted">Turn off if you do not want to track stock for this product.</small>
                                @endif
                            </label>
                        </div>
                    </div>
                @endif

                @if(auth()->check() && auth()->user()->shop && auth()->user()->shop->business_type == 'Restaurant / Food')
                <div class="col-md-12" id="recipe_section" style="display: {{ old('track_stock', true) ? 'block' : 'none' }};">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Recipe / Ingredients (Optional)</h6>
                            <p class="text-muted small mb-3">If this product is made from other products (like Mchele, Nyama), add them here. When you sell this product, the stock of these ingredients will be deducted automatically.</p>
                            
                            <div id="ingredients_container">
                                <!-- Ingredients will be appended here -->
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addIngredient()">
                                <i class="bi bi-plus-circle"></i> Add Ingredient
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($showExpiry || $showImei)
                <div class="col-md-12 mt-4">
                    <hr>
                    <h6 class="fw-bold mb-3">{{ __('Additional Business Settings') }}</h6>
                </div>
                @endif
                
                @if($showExpiry)
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Expiry Date') }} @if($shopCategory == 'Pharmacy / Health') <span class="text-danger">*</span> @endif</label>
                    <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}" @if($shopCategory == 'Pharmacy / Health') required @endif>
                    @if($shopCategory != 'Pharmacy / Health')
                    <small class="text-muted">{{ __('Leave blank if product does not expire.') }}</small>
                    @endif
                    @error('expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endif
                
                @if($showImei)
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="requires_imei" name="requires_imei" value="1" {{ old('requires_imei') ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="requires_imei">
                            <span class="fw-semibold">Requires IMEI / Serial Number</span><br>
                            <small class="text-muted">For Electronics/Mobile shops. Cashier will be asked to scan IMEI during sale.</small>
                        </label>
                    </div>
                </div>
                @endif
            </div>

            <div class="d-flex justify-content-end pt-3 border-top">
                <a href="{{ route('products.index') }}" class="btn btn-light me-2">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary px-4">{{ __('Save Product') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    let ingredientIndex = 0;
    function addIngredient() {
        let container = document.getElementById('ingredients_container');
        let html = `
            <div class="row g-2 mb-2 ingredient-row" id="ingredient_row_${ingredientIndex}">
                <div class="col-md-7">
                    <select name="ingredients[${ingredientIndex}][id]" class="form-select form-select-sm" required>
                        <option value="">Select Ingredient...</option>
                        @foreach($allProducts as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.001" name="ingredients[${ingredientIndex}][quantity]" class="form-control form-control-sm" placeholder="Quantity (e.g. 0.25)" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="document.getElementById('ingredient_row_${ingredientIndex}').remove()">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        ingredientIndex++;
    }

    function toggleRecipeSection() {
        let tsEl = document.getElementById('track_stock');
        let trackStock = tsEl ? (tsEl.type === 'checkbox' ? tsEl.checked : (tsEl.value == 1)) : true;
        
        let rs = document.getElementById('recipe_section');
        if (rs) rs.style.display = trackStock ? 'block' : 'none';
        
        let stockInput = document.getElementById('stock_input');
        if(stockInput) {
            if(!trackStock) {
                stockInput.value = 0;
                stockInput.setAttribute('readonly', 'readonly');
                stockInput.style.backgroundColor = '#e9ecef';
            } else {
                stockInput.removeAttribute('readonly');
                stockInput.style.backgroundColor = '';
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        toggleRecipeSection();
    });
</script>
@endsection

