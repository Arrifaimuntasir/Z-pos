<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Pump Number') }} <span class="text-danger">*</span></label>
        <input type="number" min="1" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number', $pump->number ?? '') }}" required>
        @error('number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Pump Name') }}</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $pump->name ?? '') }}" placeholder="{{ __('e.g. Front pump') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Location') }}</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $pump->location ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Status') }}</label>
        <select name="status" class="form-select">
            @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'maintenance' => 'Maintenance'] as $v => $label)
                <option value="{{ $v }}" {{ old('status', $pump->status ?? 'active') === $v ? 'selected' : '' }}>{{ __($label) }}</option>
            @endforeach
        </select>
    </div>
</div>
