@extends('layouts.admin')

@section('title', 'Sales Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">Sales Report</h3>
        <p class="text-muted small mb-0">Detailed sales history</p>
    </div>
    <div>
        <div class="card bg-success text-white px-4 py-2 border-0 shadow-sm rounded-pill">
            <span class="small fw-semibold">Total Sales: {{ number_format($totalSales) }} TSh</span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('reports.sales') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-funnel me-2"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start">Date</th>
                        <th class="border-0 fw-semibold">Reference</th>
                        <th class="border-0 fw-semibold">Customer</th>
                        <th class="border-0 fw-semibold text-end">Amount</th>
                        <th class="pe-4 border-0 fw-semibold text-center rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="ps-4">{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</td>
                            <td><span class="fw-medium">{{ $sale->reference_no }}</span></td>
                            <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                            <td class="text-end fw-bold">{{ number_format($sale->total_amount) }} TSh</td>
                            <td class="pe-4 text-center">
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;">
                                    <i class="bi bi-receipt"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 mb-3 d-block"></i>
                                No sales found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
