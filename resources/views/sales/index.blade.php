@extends('layouts.admin')

@section('title', 'Sales History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Sales History</h4>
        <span class="text-muted small">Manage and view all your sales records</span>
    </div>
    <div>
        <a href="{{ route('sales.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> New Sale (POS)
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start">Date</th>
                        <th class="border-0 fw-semibold">Reference No.</th>
                        <th class="border-0 fw-semibold">Customer</th>
                        <th class="border-0 fw-semibold">Status</th>
                        <th class="border-0 fw-semibold text-end">Total Amount</th>
                        <th class="pe-4 border-0 fw-semibold text-center rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="ps-4">
                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}
                            </td>
                            <td><span class="fw-medium">{{ $sale->reference_no }}</span></td>
                            <td>
                                @if($sale->customer)
                                    {{ $sale->customer->name }}
                                @else
                                    <span class="text-muted fst-italic">Walk-in Customer</span>
                                @endif
                            </td>
                            <td>
                                @if($sale->payment_status == 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">Paid</span>
                                @elseif($sale->payment_status == 'partial')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill">Partial</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">Unpaid</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">
                                {{ number_format($sale->total_amount) }} TSh
                            </td>
                            <td class="pe-4 text-center">
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;">
                                    <i class="bi bi-receipt"></i> View Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 text-light-secondary mb-3 d-block"></i>
                                <h5>No sales recorded yet</h5>
                                <p class="mb-4">Start selling products by going to the POS.</p>
                                <a href="{{ route('sales.create') }}" class="btn btn-primary px-4">Go to POS</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
