@extends('layouts.admin')

@section('title', 'Add Branch')

@section('content')
<div class="mb-4">
    <a href="{{ route('branches.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Back to Branches
    </a>
    <h4 class="fw-bold mb-0 text-dark">Add New Branch</h4>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('branches.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Mlimani City Branch">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="address" class="form-control bg-light @error('address') is-invalid @enderror" rows="2" placeholder="Physical location of the branch">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control bg-light @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="e.g. +255 700 000 000">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="branch@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-grid mt-2">
                        <button type="submit" class="btn btn-primary py-2 fw-bold rounded-3">
                            <i class="bi bi-save me-1"></i> Save Branch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
