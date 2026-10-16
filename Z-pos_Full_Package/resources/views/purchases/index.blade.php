@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Purchases') }}</h4>
        <span class="text-muted small">{{ __('Manage your stock purchases and inventory intake') }}</span>
    </div>
    <div>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> {{ __('Add Purchase') }}
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Desktop / tablet table --}}
<div class="card border-0 shadow-sm d-none d-md-block" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;">{{ __('Date') }}</th>
                        <th class="fw-medium border-0">{{ __('Reference No') }}</th>
                        <th class="fw-medium border-0">{{ __('Supplier') }}</th>
                        <th class="fw-medium border-0">{{ __('Status') }}</th>
                        <th class="fw-medium border-0 text-end">{{ __('Total Amount') }}</th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end">{{ __('Actions') }}</th>
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
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">{{ __('Completed') }}</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">{{ __('Pending') }}</span>
                            @endif
                        </td>
                        <td class="text-end fw-bold">{{ number_format($purchase->total_amount, 2) }}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-light text-primary shadow-sm rounded-3">
                                    <i class="bi bi-eye"></i> {{ __('View') }}
                                </a>
                                <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-sm btn-light text-dark shadow-sm rounded-3">
                                    <i class="bi bi-pencil"></i> {{ __('Edit') }}
                                </a>
                                @if($purchase->status != 'completed')
                                <form action="{{ route('purchases.complete', $purchase->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light text-success shadow-sm rounded-3" onclick="return confirm('Mark this purchase as completed and add its items to stock?')">
                                        <i class="bi bi-check-lg"></i> {{ __('Complete') }}
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to delete this purchase?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-cart-plus fs-1 text-light-secondary"></i></div>
                            <h6 class="fw-bold">{{ __('No purchases found') }}</h6>
                            <p class="small mb-0">{{ __('Start by adding your first stock purchase.') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Mobile card list --}}
<div class="d-md-none">
    @forelse($purchases as $purchase)
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 16px;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge bg-light text-dark border">{{ $purchase->reference_no }}</span>
                @if($purchase->status == 'completed')
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">{{ __('Completed') }}</span>
                @else
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">{{ __('Pending') }}</span>
                @endif
            </div>

            <div class="fw-bold text-dark fs-6">{{ $purchase->supplier->name ?? 'Unknown' }}</div>
            <div class="text-muted small mb-2">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</div>
            <div class="fw-bold text-dark fs-5 mb-3">{{ number_format($purchase->total_amount, 2) }} <span class="text-muted small fw-normal">TSh</span></div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-light text-primary shadow-sm rounded-3 flex-fill">
                    <i class="bi bi-eye"></i> {{ __('View') }}
                </a>
                <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-sm btn-light text-dark shadow-sm rounded-3 flex-fill">
                    <i class="bi bi-pencil"></i> {{ __('Edit') }}
                </a>
                @if($purchase->status != 'completed')
                <form action="{{ route('purchases.complete', $purchase->id) }}" method="POST" class="flex-fill">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light text-success shadow-sm rounded-3 w-100" onclick="return confirm('Mark this purchase as completed and add its items to stock?')">
                        <i class="bi bi-check-lg"></i> {{ __('Complete') }}
                    </button>
                </form>
                @endif
                <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to delete this purchase?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body text-center py-5 text-muted">
            <div class="mb-3"><i class="bi bi-cart-plus fs-1 text-light-secondary"></i></div>
            <h6 class="fw-bold">{{ __('No purchases found') }}</h6>
            <p class="small mb-0">{{ __('Start by adding your first stock purchase.') }}</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
