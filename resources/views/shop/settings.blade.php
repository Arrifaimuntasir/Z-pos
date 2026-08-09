@extends('layouts.admin')

@section('title', 'Shop Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Shop Settings</h4>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('shop.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4 text-center">
                        <div class="d-inline-block position-relative mb-3">
                            @if($shop->logo_path)
                                <img src="{{ asset($shop->logo_path) }}" alt="Shop Logo" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="rounded-circle border d-flex justify-content-center align-items-center bg-light text-secondary" style="width: 120px; height: 120px; font-size: 3rem;">
                                    <i class="bi bi-shop"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="logo" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                <i class="bi bi-upload me-1"></i> Upload New Logo
                            </label>
                            <input type="file" id="logo" name="logo" class="d-none" accept="image/*">
                        </div>
                        @error('logo') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Shop Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name', $shop->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
