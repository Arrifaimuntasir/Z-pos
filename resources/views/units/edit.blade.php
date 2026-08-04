@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Edit Unit</h4>
        <span class="text-muted small">Update measurement unit details</span>
    </div>
    <div>
        <a href="{{ route('units.index') }}" class="btn btn-light px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('units.update', $unit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $unit->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Short Name <span class="text-danger">*</span></label>
                        <input type="text" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name', $unit->short_name) }}" required>
                        @error('short_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="allow_decimal" id="allow_decimal" {{ old('allow_decimal', $unit->allow_decimal) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium text-dark" for="allow_decimal">
                            Allow Decimals
                            <div class="text-muted small fw-normal mt-1">Check this if the unit can be sold in fractions (e.g. 1.5 Kg). Leave unchecked for whole items like phones (e.g. 1 Pc).</div>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius: 8px;">Update Unit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
