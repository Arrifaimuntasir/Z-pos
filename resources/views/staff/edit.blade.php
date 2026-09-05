@extends('layouts.admin')

@section('title', 'Add Staff')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Add New Staff') }}</h4>
        <span class="text-muted small">{{ __('Create an account for your shop cashier or manager') }}</span>
    </div>
    <div>
        
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('staff.update', $staff->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('Full Name') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $staff->name) }}" required placeholder="{{ __('e.g. arrifai muntasir') }}">
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address (Used for Login)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', $staff->email) }}" required placeholder="staff@example.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('New Password') }} <span class="text-muted fw-normal">(Leave blank to keep current)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control bg-light border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="{{ __('Minimum 8 characters') }}">
                            <span class="input-group-text bg-light @error('password') border-danger @enderror" style="cursor: pointer;" onclick="togglePassword('password', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('Confirm New Password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-start-0 border-end-0" placeholder="{{ __('Type new password again') }}">
                            <span class="input-group-text bg-light" style="cursor: pointer;" onclick="togglePassword('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>

                    @if(empty($shop->package) || strtolower($shop->package) === 'starter')
                        @if($branches->count() > 0)
                            <input type="hidden" name="branch_id" value="{{ $branches->first()->id }}">
                        @endif
                    @else
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('Assign to Branch') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                            <select name="branch_id" class="form-select bg-light border-start-0 @error('branch_id') is-invalid @enderror" required>
                                <option value="">{{ __('Select Branch') }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ (old('branch_id', $staff->branch_id) == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('branch_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold w-100" style="border-radius: 8px;">
                            <i class="bi bi-check2-circle me-2"></i> {{ __('Update Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 16px;">
            <div class="card-body p-5">
                <h4 class="fw-bold mb-4"><i class="bi bi-shield-lock me-2"></i> {{ __('Security First') }}</h4>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-cash-coin fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">{{ __('Cashier Role') }}</h6>
                        <p class="text-white-50 small mb-0">{{ __('Cashiers can only make sales and view their daily transactions. They cannot delete sales or view overall profit reports.') }}</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">User Limits ({{ ucfirst($shop->package ?? 'Starter') }} Plan)</h6>
                        @if(($shop->package ?? 'starter') === 'starter')
                        <p class="text-white-50 small mb-0">The Starter plan allows a maximum of 2 users (You + 1 Staff). Upgrade your plan to add more staff.</p>
                        @else
                        <p class="text-white-50 small mb-0">Your {{ ucfirst($shop->package) }} plan allows unlimited users! Feel free to add as many staff members as you need.</p>
                        @endif
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
