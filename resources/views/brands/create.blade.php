@extends('layouts.admin')

@section('title', 'Add Brand')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h2 class="fw-bold mb-0">Add Brand</h2>
    <a href="{{ route('brands.index') }}" class="btn btn-light border bg-white shadow-sm rounded-pill px-4" style="font-weight: 500; font-size: 14px;"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="admin-card">
    <div class="card-body p-4">
        <form action="{{ route('brands.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Save Brand
            </button>
        </form>
    </div>
</div>
@endsection
