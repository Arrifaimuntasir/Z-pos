@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Edit Unit') }}</h4>
        <span class="text-muted small">{{ __('Update measurement unit details') }}</span>
    </div>
    <div>
        
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
                        <label class="form-label fw-medium text-dark">{{ __('Unit Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $unit->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">{{ __('Short Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name', $unit->short_name) }}" required>
                        @error('short_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="allow_decimal" id="allow_decimal" {{ old('allow_decimal', $unit->allow_decimal) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium text-dark" for="allow_decimal">
                            {{ __('Allow Decimals') }}
                            <div class="text-muted small fw-normal mt-1">Check this if the unit can be sold in fractions (e.g. 1.5 Kg). Leave unchecked for whole items like phones (e.g. 1 Pc).</div>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius: 8px;">{{ __('Update Unit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
