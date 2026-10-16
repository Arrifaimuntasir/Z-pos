@extends('layouts.admin')
@section('title', __('Edit Godown'))
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">✏️ {{ __('Edit Godown') }}</h4>
    <a href="{{ route('warehouses.index') }}" class="btn btn-light shadow-sm" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-2"></i>{{ __('Back') }}
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body p-4">
                <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Godown Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $warehouse->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Location / Address') }}</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $warehouse->location) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Description') }}</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $warehouse->description) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ $warehouse->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isActive">{{ __('Active') }}</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius:10px;">
                        <i class="bi bi-save me-2"></i>{{ __('Save Changes') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
