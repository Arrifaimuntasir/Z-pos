@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Invoices</h4>
        <span class="text-muted small">Manage and view all your invoices</span>
    </div>
    <div>
        <a href="{{ route('sales.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> New Sale (POS)
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0 text-dark fw-bold">Recent Invoices</h5>
        <form action="{{ route('invoices.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search by Ref or Customer..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start">Date</th>
                        <th class="border-0 fw-semibold">Reference No.</th>
                        <th class="border-0 fw-semibold">Customer</th>
                        <th class="border-0 fw-semibold">Status</th>
                        <th class="border-0 fw-semibold text-end">Total Amount</th>
                        <th class="border-0 fw-semibold text-center">View</th>
                        <th class="pe-4 border-0 fw-semibold text-center rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $sale)
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
                            <td class="text-center">
                                <a href="{{ route('invoices.show', $sale->id) }}" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;" title="View Invoice">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            <td class="pe-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('warranties.create', ['sale_id' => $sale->id]) }}" class="btn btn-sm btn-light text-success shadow-sm" style="border-radius: 6px;" title="Generate Warranty">
                                        <i class="bi bi-shield-check"></i>
                                    </a>
                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice? This will restore the sold items back to stock and reduce your total sales and profit.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 text-light-secondary mb-3 d-block"></i>
                                <h5>No invoices recorded yet</h5>
                                <p class="mb-4">Start selling products by going to the POS.</p>
                                <a href="{{ route('sales.create') }}" class="btn btn-primary px-4">Go to POS</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
