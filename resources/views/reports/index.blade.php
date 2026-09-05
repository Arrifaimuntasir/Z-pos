@extends('layouts.admin')

@section('title', 'Reports Overview')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark">{{ __('Reports Overview') }}</h3>
        <p class="text-muted small mb-0">{{ __('High-level view of your business') }}</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-box-seam fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-white-50">{{ __('Total Products') }}</h6>
                    </div>
                    <h3 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-success" style="width: 40px; height: 40px;">
                            <i class="bi bi-cart-check fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">Total Sales (All Time)</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalSalesReal) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-danger" style="width: 40px; height: 40px;">
                            <i class="bi bi-graph-down-arrow fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">Total Expenses (All Time)</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalExpenses) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-warning" style="width: 40px; height: 40px;">
                            <i class="bi bi-cash-stack fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">Net Profit (All Time)</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($grossProfit - $totalExpenses) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold text-dark">{{ __('Stock Value') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
                    <span class="text-muted">{{ __('Total Cost Value of Current Stock') }}</span>
                    <span class="fw-bold">{{ number_format($totalStockValue) }} TSh</span>
                </div>
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
                    <span class="text-muted">{{ __('Total Selling Value of Current Stock') }}</span>
                    <span class="fw-bold">{{ number_format($totalSalesValue) }} TSh</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-semibold">{{ __('Potential Profit from Stock') }}</span>
                    <span class="fw-bold text-success">{{ number_format($totalSalesValue - $totalStockValue) }} TSh</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
