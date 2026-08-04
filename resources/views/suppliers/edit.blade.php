@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Edit Supplier</h4>
        <span class="text-muted small">Update distributor/supplier details</span>
    </div>
    <div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-light px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Company / Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $supplier->contact_person) }}">
                        @error('contact_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-dark">Phone Number</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $supplier->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-dark">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $supplier->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Address / Location</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $supplier->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius: 8px;">Update Supplier</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
