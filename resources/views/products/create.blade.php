@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-light rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Add New Product</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase">SKU (Barcode/ID) <span class="text-danger">*</span></label>
                    <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" required>
                    @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Brand</label>
                    <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Model</label>
                    <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}">
                    @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Unit <span class="text-danger">*</span></label>
                    <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
                        @endforeach
                    </select>
                    @error('unit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Stock Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Buying Price (Bei uliyonunulia) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">TSh</span>
                        <input type="number" step="0.01" name="cost_price" class="form-control border-start-0 @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}" min="0" required>
                    </div>
                    @error('cost_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Selling Price (Bei ya kuuza) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">TSh</span>
                        <input type="number" step="0.01" name="selling_price" class="form-control border-start-0 @error('selling_price') is-invalid @enderror" value="{{ old('selling_price') }}" min="0" required>
                    </div>
                    @error('selling_price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-end pt-3 border-top">
                <a href="{{ route('products.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Product</button>
            </div>
        </form>
    </div>
</div>
@endsection
