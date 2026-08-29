@extends('layouts.admin')

@section('title', 'Staff & Users')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Staff & Users</h4>
        <span class="text-muted small">Manage people who have access to your shop</span>
    </div>
    <div>
        <a href="{{ route('staff.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-person-plus me-2"></i> Add Staff
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start py-3">Name</th>
                        <th class="fw-medium border-0">Email</th>
                        <th class="fw-medium border-0">Role</th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <!-- Owner / Admin Row -->
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-circle fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ Auth::user()->name }} (You)</div>
                                    <div class="text-muted small">Shop Owner</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ Auth::user()->email }}</td>
                        <td><span class="badge bg-primary rounded-pill px-3">Admin</span></td>
                        <td class="text-end pe-4">
                            <!-- Cannot delete self -->
                        </td>
                    </tr>
                    
                    @forelse($staff as $user)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->hasRole('Cashier'))
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Cashier</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Staff</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('staff.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to remove this user from your shop? They will no longer be able to log in.')">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 text-light-secondary mb-3 d-block"></i>
                            <h5>No other staff added yet</h5>
                            <p class="mb-0">You can add staff like Cashiers to help you manage sales.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
