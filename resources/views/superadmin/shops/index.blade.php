@extends('layouts.admin')

@section('title', 'Manage Shops')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">System Admin: Manage Shops</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-top-start ps-4 py-3">Shop Name</th>
                        <th class="border-0 py-3">Owner Email</th>
                        <th class="border-0 py-3">Registered At</th>
                        <th class="border-0 py-3">Valid Until</th>
                        <th class="border-0 py-3">Status</th>
                        <th class="border-0 rounded-top-end text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($shops as $shop)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($shop->logo_path)
                                    <img src="{{ asset($shop->logo_path) }}" class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-3 border text-secondary" style="width: 40px; height: 40px;">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                @endif
                                <span class="fw-bold text-dark">{{ $shop->name }}</span>
                            </div>
                        </td>
                        <td>
                            @foreach($shop->users as $user)
                                <div class="small">{{ $user->email }}</div>
                            @endforeach
                        </td>
                        <td>{{ $shop->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($shop->valid_until)
                                <span class="badge {{ $shop->valid_until < now() ? 'bg-danger' : 'bg-success' }}">
                                    {{ \Carbon\Carbon::parse($shop->valid_until)->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($shop->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Active</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Suspended</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('superadmin.shops.edit', $shop) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('superadmin.shops.toggle-status', $shop) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $shop->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" onclick="return confirm('Are you sure you want to change the status of this shop?');">
                                    {{ $shop->is_active ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
