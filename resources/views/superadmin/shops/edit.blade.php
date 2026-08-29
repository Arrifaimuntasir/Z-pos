@extends('layouts.admin')

@section('title', 'Edit Shop')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">Edit Shop: {{ $shop->name }}</h4>
    <a href="{{ route('superadmin.shops.index') }}" class="btn btn-light border bg-white shadow-sm rounded-pill px-4" style="font-weight: 500; font-size: 14px;"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('superadmin.shops.update', $shop) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Shop Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $shop->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Valid Until (Expiry Date)</label>
                        <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', $shop->valid_until ? \Carbon\Carbon::parse($shop->valid_until)->format('Y-m-d') : '') }}">
                        @error('valid_until') <small class="text-danger">{{ $message }}</small> @enderror
                        <small class="text-muted d-block mt-1">Leave empty for unlimited access.</small>
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $shop->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-muted" for="is_active">Shop is Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
