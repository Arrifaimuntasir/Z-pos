@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div class="d-flex align-items-center mb-4">
    
    <h4 class="fw-bold mb-0">{{ __('Add New Product') }}</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-semibold text-muted small text-uppercase mb-0">{{ __('Category') }} <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold small text-primary" id="toggle_category_btn" onclick="toggleFieldMode('category')">
                            <span id="category_btn_text"><i class="bi bi-plus-circle me-1"></i>+ {{ __('New Category') }}</span>
                        </button>
                    </div>
                    <div id="category_select_wrap">
                        <select name="category_id" id="category_select" class="form-select @error('category_id') is-invalid @enderror" onchange="checkNewSelectOption(this, 'category')">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                            <option value="__new__" class="fw-bold text-primary">+ {{ __('Add New Category...') }}</option>
                        </select>
                    </div>
                    <div id="category_input_wrap" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="bi bi-tag"></i></span>
                            <input type="text" name="new_category" id="new_category_input" class="form-control" placeholder="{{ __('Type new category name...') }}" value="{{ old('new_category') }}">
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldMode('category', false)" title="{{ __('Cancel & Select Existing') }}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <small class="text-success" style="font-size:11.5px;"><i class="bi bi-check2-circle me-1"></i>{{ __('Will automatically be saved to categories.') }}</small>
                    </div>
                    @error('category_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-semibold text-muted small text-uppercase mb-0">{{ __('Brand') }} (Optional)</label>
                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold small text-primary" id="toggle_brand_btn" onclick="toggleFieldMode('brand')">
                            <span id="brand_btn_text"><i class="bi bi-plus-circle me-1"></i>+ {{ __('New Brand') }}</span>
                        </button>
                    </div>
                    <div id="brand_select_wrap">
                        <select name="brand_id" id="brand_select" class="form-select @error('brand_id') is-invalid @enderror" onchange="checkNewSelectOption(this, 'brand')">
                            <option value="">{{ __('Select Brand') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                            <option value="__new__" class="fw-bold text-primary">+ {{ __('Add New Brand...') }}</option>
                        </select>
                    </div>
                    <div id="brand_input_wrap" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="bi bi-patch-check"></i></span>
                            <input type="text" name="new_brand" id="new_brand_input" class="form-control" placeholder="{{ __('Type new brand name...') }}" value="{{ old('new_brand') }}">
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldMode('brand', false)" title="{{ __('Cancel & Select Existing') }}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <small class="text-success" style="font-size:11.5px;"><i class="bi bi-check2-circle me-1"></i>{{ __('Will automatically be saved to brands.') }}</small>
                    </div>
                    @error('brand_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-semibold text-muted small text-uppercase mb-0">{{ __('Unit') }} (Optional)</label>
                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold small text-primary" id="toggle_unit_btn" onclick="toggleFieldMode('unit')">
                            <span id="unit_btn_text"><i class="bi bi-plus-circle me-1"></i>+ {{ __('New Unit') }}</span>
                        </button>
                    </div>
                    <div id="unit_select_wrap">
                        <select name="unit_id" id="unit_select" class="form-select @error('unit_id') is-invalid @enderror" onchange="checkNewSelectOption(this, 'unit')">
                            <option value="">{{ __('Select Unit') }}</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
                            @endforeach
                            <option value="__new__" class="fw-bold text-primary">+ {{ __('Add New Unit...') }}</option>
                        </select>
                    </div>
                    <div id="unit_input_wrap" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="bi bi-rulers"></i></span>
                            <input type="text" name="new_unit" id="new_unit_input" class="form-control" placeholder="{{ __('e.g. Kg, Pcs, Box, Bottle...') }}" value="{{ old('new_unit') }}">
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldMode('unit', false)" title="{{ __('Cancel & Select Existing') }}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <small class="text-success" style="font-size:11.5px;"><i class="bi bi-check2-circle me-1"></i>{{ __('Will automatically be saved to units.') }}</small>
                    </div>
                    @error('unit_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">{{ __('Stock Quantity') }} (Optional)</label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" id="stock_input">
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                @if((auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin')) && isset($warehouses) && $warehouses->count() > 0)
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">
                        <i class="bi bi-building me-1 text-primary"></i>{{ __('Stock Destination') }}
                    </label>
                    <select name="stock_destination" class="form-select border-primary" style="background-color: #f8fafc;">
                        <option value="shop" {{ old('stock_destination', ($selectedWarehouseId ? 'warehouse_' . $selectedWarehouseId : 'shop')) == 'shop' ? 'selected' : '' }}>
                            🏪 {{ __('Shop Floor Stock') }}
                        </option>
                        <optgroup label="{{ __('Warehouses') }}">
                            @foreach($warehouses as $wh)
                                <option value="warehouse_{{ $wh->id }}" {{ old('stock_destination', ($selectedWarehouseId ? 'warehouse_' . $selectedWarehouseId : '')) == 'warehouse_' . $wh->id ? 'selected' : '' }}>
                                    🏢 {{ $wh->name }} {{ $wh->location ? '('.$wh->location.')' : '' }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    <small class="text-muted d-block mt-1" style="font-size:11.5px;">
                        {{ __('Choose whether this stock is stored on the shop floor or directly in a warehouse.') }}
                    </small>
                </div>
                @endif

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

                {{-- Product Image Upload --}}
                <div class="col-md-12 mt-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">
                        <i class="bi bi-image text-primary me-1"></i>{{ __('Product Image / Photo') }} (Optional)
                    </label>
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                        <div id="product_image_preview_box" style="width: 72px; height: 72px; border-radius: 8px; border: 1.5px dashed #cbd5e1; background: #ffffff; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                            <i class="bi bi-camera text-muted fs-3" id="product_image_icon"></i>
                            <img id="product_image_preview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        </div>
                        <div class="flex-grow-1">
                            <input type="file" name="image" id="product_image_input" class="form-control form-control-sm @error('image') is-invalid @enderror" accept="image/*" onchange="previewProductImage(this)">
                            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
                                {{ __('Upload a clear photo of the product (JPG, PNG, WebP). This photo will appear on the Supermarket POS when scanned or searched.') }}
                            </small>
                            @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
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
                                <span class="fw-semibold">{{ __('Track Stock (Advanced Stock Mode)') }}</span><br>
                                @if(auth()->check() && auth()->user()->shop && auth()->user()->shop->business_type == 'Restaurant / Food')
                                    <small class="text-muted">{{ __('Turn off for Simple Mode (e.g. cooked meals with no stock tracking). If ON, you can also add a recipe below.') }}</small>
                                @else
                                    <small class="text-muted">{{ __('Turn off if you do not want to track stock for this product.') }}</small>
                                @endif
                            </label>
                        </div>
                    </div>
                @endif

                @if(auth()->check() && auth()->user()->shop && auth()->user()->shop->business_type == 'Restaurant / Food')
                <div class="col-md-12" id="recipe_section" style="display: {{ old('track_stock', true) ? 'block' : 'none' }};">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">{{ __('Recipe / Ingredients (Optional)') }}</h6>
                            <p class="text-muted small mb-3">{{ __('If this product is made from other products (like Mchele, Nyama), add them here. When you sell this product, the stock of these ingredients will be deducted automatically.') }}</p>
                            
                            <div id="ingredients_container">
                                <!-- Ingredients will be appended here -->
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addIngredient()">
                                <i class="bi bi-plus-circle"></i> {{ __('Add Ingredient') }}
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
                            <span class="fw-semibold">{{ __('Requires IMEI / Serial Number') }}</span><br>
                            <small class="text-muted">{{ __('For Electronics/Mobile shops. Cashier will be asked to scan IMEI during sale.') }}</small>
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
                        <option value="">{{ __('Select Ingredient...') }}</option>
                        @foreach($allProducts as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.001" name="ingredients[${ingredientIndex}][quantity]" class="form-control form-control-sm" placeholder="{{ __('Quantity (e.g. 0.25)') }}" required>
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
    
    function toggleFieldMode(field, forceNew = null) {
        const selectWrap = document.getElementById(field + '_select_wrap');
        const inputWrap  = document.getElementById(field + '_input_wrap');
        const selectEl   = document.getElementById(field + '_select');
        const inputEl    = document.getElementById('new_' + field + '_input');
        const toggleText = document.getElementById(field + '_btn_text');

        if (!selectWrap || !inputWrap) return;

        const isCurrentlyInput = inputWrap.style.display !== 'none';
        const showInput = (forceNew !== null) ? forceNew : !isCurrentlyInput;

        if (showInput) {
            selectWrap.style.display = 'none';
            inputWrap.style.display = 'block';
            if (selectEl) selectEl.value = '__new__';
            if (inputEl) {
                setTimeout(() => inputEl.focus(), 50);
            }
            if (toggleText) {
                toggleText.innerHTML = '<i class="bi bi-list-ul me-1"></i>{{ __("Select Existing") }}';
            }
        } else {
            inputWrap.style.display = 'none';
            selectWrap.style.display = 'block';
            if (inputEl) inputEl.value = '';
            if (selectEl && selectEl.value === '__new__') {
                selectEl.value = '';
            }
            if (toggleText) {
                toggleText.innerHTML = '<i class="bi bi-plus-circle me-1"></i>+ {{ __("New") }}';
            }
        }
    }

    function checkNewSelectOption(selectEl, field) {
        if (selectEl && selectEl.value === '__new__') {
            toggleFieldMode(field, true);
        }
    }

    function previewProductImage(input) {
        const preview = document.getElementById('product_image_preview');
        const icon = document.getElementById('product_image_icon');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (icon) icon.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
            if (icon) icon.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleRecipeSection();

        // Restore new input mode if user previously typed or error occurred
        ['category', 'brand', 'unit'].forEach(function(f) {
            const inp = document.getElementById('new_' + f + '_input');
            if (inp && inp.value && inp.value.trim() !== '') {
                toggleFieldMode(f, true);
            }
        });
    });
</script>
@endsection

