@extends('layouts.admin')

@section('title', 'Manage Branches')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Branches</h4>
        <span class="text-muted small">Manage your shop's physical locations</span>
    </div>
    @if(Auth::user()->shop->package !== 'starter')
    <div>
        <a href="{{ route('branches.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> Add Branch
        </a>
    </div>
    @endif
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start py-3">Branch Name</th>
                        <th class="fw-medium border-0">Address</th>
                        <th class="fw-medium border-0">Contact</th>
                        <th class="fw-medium border-0">Status</th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($branches as $branch)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-shop fs-5"></i>
                                </div>
                                <div class="fw-bold text-dark">{{ $branch->name }} {!! $branch->name === 'Main Branch' ? '<span class="badge bg-secondary ms-1" style="font-size: 10px;">Default</span>' : '' !!}</div>
                            </div>
                        </td>
                        <td>{{ $branch->address ?? '-' }}</td>
                        <td>
                            @if($branch->phone)
                                <div><i class="bi bi-telephone text-muted me-1"></i> {{ $branch->phone }}</div>
                            @endif
                            @if($branch->email)
                                <div><i class="bi bi-envelope text-muted me-1"></i> {{ $branch->email }}</div>
                            @endif
                            @if(!$branch->phone && !$branch->email)
                                -
                            @endif
                        </td>
                        <td>
                            @if($branch->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 rounded-pill">Active</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 rounded-pill">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-sm btn-light text-primary shadow-sm rounded-3">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-shop fs-1 text-light-secondary mb-3 d-block"></i>
                            <h5>No branches found</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
