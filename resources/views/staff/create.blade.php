@extends('layouts.admin')

@section('title', 'Add Staff')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Add New Staff</h4>
        <span class="text-muted small">Create an account for your shop cashier or manager</span>
    </div>
    <div>
        <a href="{{ route('staff.index') }}" class="btn btn-light px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. arrifai muntasir">
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address (Used for Login)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="staff@example.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control bg-light border-start-0 border-end-0 @error('password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                            <span class="input-group-text bg-light @error('password') border-danger @enderror" style="cursor: pointer;" onclick="togglePassword('password', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-start-0 border-end-0" required placeholder="Type password again">
                            <span class="input-group-text bg-light" style="cursor: pointer;" onclick="togglePassword('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 bg-primary bg-opacity-10 d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                        <div>
                            This user will automatically be assigned the <strong>Cashier</strong> role and will only be able to see POS and Sales related features.
                        </div>
                    </div>

                    <div class="mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold w-100" style="border-radius: 8px;">
                            <i class="bi bi-check2-circle me-2"></i> Create Staff Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 16px;">
            <div class="card-body p-5">
                <h4 class="fw-bold mb-4"><i class="bi bi-shield-lock me-2"></i> Security First</h4>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-cash-coin fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Cashier Role</h6>
                        <p class="text-white-50 small mb-0">Cashiers can only make sales and view their daily transactions. They cannot delete sales or view overall profit reports.</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">User Limits</h6>
                        <p class="text-white-50 small mb-0">The Starter plan allows a maximum of 2 users (You + 1 Staff). Upgrade your plan to add more staff.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, iconElement) {
        const input = document.getElementById(inputId);
        const icon = iconElement.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
