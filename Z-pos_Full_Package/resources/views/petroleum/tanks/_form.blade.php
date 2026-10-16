<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-medium">{{ __('Tank Name / Number') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $tank->name ?? '') }}" required placeholder="{{ __('e.g. Tank 1') }}">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">{{ __('Fuel Type') }} <span class="text-danger">*</span></label>
        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
            <option value="">{{ __('Select fuel product...') }}</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" {{ (int) old('product_id', $tank->product_id ?? 0) === $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">{{ __('Create fuel products (Petrol, Diesel...) in Products with unit Litre.') }}</small>
        @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Capacity (Litres)') }} <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="1" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $tank->capacity ?? '') }}" required>
        @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @if(!isset($tank))
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Opening Stock (Litres)') }}</label>
        <input type="number" step="0.01" min="0" name="opening_stock" class="form-control @error('opening_stock') is-invalid @enderror" value="{{ old('opening_stock', 0) }}">
        @error('opening_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Minimum Level (Litres)') }}</label>
        <input type="number" step="0.01" min="0" name="min_level" class="form-control" value="{{ old('min_level', $tank->min_level ?? 0) }}">
        <small class="text-muted">{{ __('Alert when stock falls below this') }}</small>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Maximum Level (Litres)') }}</label>
        <input type="number" step="0.01" min="0" name="max_level" class="form-control @error('max_level') is-invalid @enderror" value="{{ old('max_level', $tank->max_level ?? '') }}">
        @error('max_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Status') }}</label>
        <select name="status" class="form-select">
            @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'maintenance' => 'Maintenance'] as $v => $label)
                <option value="{{ $v }}" {{ old('status', $tank->status ?? 'active') === $v ? 'selected' : '' }}>{{ __($label) }}</option>
            @endforeach
        </select>
    </div>
</div>
