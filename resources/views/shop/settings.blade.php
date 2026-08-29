@extends('layouts.admin')

@section('title', 'Shop Settings')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">Shop Settings</h4>
</div>

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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Shop Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name', $shop->name) }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-lg bg-light border-0" value="{{ old('phone', $shop->phone) }}" placeholder="e.g. 0700 000 000">
                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">TIN Number</label>
                            <input type="text" name="tin_number" class="form-control form-control-lg bg-light border-0" value="{{ old('tin_number', $shop->tin_number) }}" placeholder="e.g. 100-200-300">
                            @error('tin_number') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Address / Location</label>
                            <input type="text" name="address" class="form-control form-control-lg bg-light border-0" value="{{ old('address', $shop->address) }}" placeholder="e.g. Kariakoo, DSM">
                            @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Receipt Footer Message</label>
                        <textarea name="receipt_message" class="form-control bg-light border-0" rows="2" placeholder="e.g. Thank you for your business! Karibu tena.">{{ old('receipt_message', $shop->receipt_message) }}</textarea>
                        @error('receipt_message') <small class="text-danger">{{ $message }}</small> @enderror
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
