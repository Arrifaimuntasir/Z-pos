@extends('layouts.admin')
@section('title', __('Add New Godown'))
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">🏭 {{ __('Add New Godown') }}</h4>
        <span class="text-muted small">{{ __('Create a new warehouse or storage location') }}</span>
    </div>
    <a href="{{ route('warehouses.index') }}" class="btn btn-light shadow-sm" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-2"></i>{{ __('Back') }}
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body p-4">
                <form action="{{ route('warehouses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Godown Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Godown Kuu, Warehouse Dar" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Location / Address') }}</label>
                        <input type="text" name="location" class="form-control"
                            placeholder="e.g. Kariakoo, Dar es Salaam" value="{{ old('location') }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('Description') }}</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Maelezo mafupi kuhusu godown hii...">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius:10px;">
                        <i class="bi bi-plus-circle me-2"></i>{{ __('Add Godown') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
