@extends('layouts.admin')

@section('title', 'Edit Branch')

@section('content')
<div class="mb-4">
    <a href="{{ route('branches.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Branches') }}
    </a>
    <h4 class="fw-bold mb-0 text-dark">Edit Branch: {{ $branch->name }}</h4>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Branch Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name', $branch->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Address') }}</label>
                        <textarea name="address" class="form-control bg-light @error('address') is-invalid @enderror" rows="2">{{ old('address', $branch->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" class="form-control bg-light @error('phone') is-invalid @enderror" value="{{ old('phone', $branch->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">{{ __('Email Address') }}</label>
                            <input type="email" name="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email', $branch->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="isActive" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isActive">{{ __('Branch is Active') }}</label>
                        </div>
                    </div>
                    
                    <div class="d-grid mt-2">
                        <button type="submit" class="btn btn-primary py-2 fw-bold rounded-3">
                            <i class="bi bi-save me-1"></i> {{ __('Update Branch') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
