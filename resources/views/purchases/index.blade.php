@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Purchases</h4>
        <span class="text-muted small">Manage your stock purchases and inventory intake</span>
    </div>
    <div>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> Add Purchase
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;">Date</th>
                        <th class="fw-medium border-0">Reference No</th>
                        <th class="fw-medium border-0">Supplier</th>
                        <th class="fw-medium border-0">Status</th>
                        <th class="fw-medium border-0 text-end">Total Amount</th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($purchases as $purchase)
                    <tr>
                        <td class="ps-4 py-3">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $purchase->reference_no }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $purchase->supplier->name ?? 'Unknown' }}</div>
                        </td>
                        <td>
                            @if($purchase->status == 'completed')
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Completed</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Pending</span>
                            @endif
                        </td>
                        <td class="text-end fw-bold">{{ number_format($purchase->total_amount, 2) }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-light text-primary me-2 shadow-sm rounded-3">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to delete this purchase?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-cart-plus fs-1 text-light-secondary"></i></div>
                            <h6 class="fw-bold">No purchases found</h6>
                            <p class="small mb-0">Start by adding your first stock purchase.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
