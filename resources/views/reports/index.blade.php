@extends('layouts.admin')

@section('title', 'Reports Overview')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Business Reports</h4>
        <p class="text-muted small mb-0">Overview of your inventory and financials</p>
    </div>
    <button class="btn btn-light rounded-pill px-4 shadow-sm border" onclick="window.print()">
        <i class="bi bi-printer me-2"></i> Print Report
    </button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <span class="text-muted fw-semibold">Total Products</span>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                        <i class="bi bi-cart-check fs-4"></i>
                    </div>
                    <span class="text-muted fw-semibold">Stock Value (Cost)</span>
                </div>
                <h4 class="fw-bold mb-0">{{ number_format($totalStockValue) }} <small class="text-muted fs-6">TSh</small></h4>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                    <span class="text-muted fw-semibold">Stock Value (Sales)</span>
                </div>
                <h4 class="fw-bold mb-0">{{ number_format($totalSalesValue) }} <small class="text-muted fs-6">TSh</small></h4>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 me-3">
                        <i class="bi bi-graph-down-arrow fs-4"></i>
                    </div>
                    <span class="text-muted fw-semibold">Total Expenses</span>
                </div>
                <h4 class="fw-bold mb-0 text-danger">{{ number_format($totalExpenses) }} <small class="text-muted fs-6">TSh</small></h4>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
    <div class="card-body p-5 text-center">
        <h5 class="fw-semibold opacity-75 mb-3">Estimated Gross Profit Potential</h5>
        <h1 class="fw-bold mb-0 display-5 text-success">
            + {{ number_format($potentialProfit) }} <span class="fs-4 opacity-75 fw-normal">TSh</span>
        </h1>
        <p class="mt-3 mb-0 opacity-50 small">This represents the theoretical profit if all current stock is sold at the specified selling price, minus total recorded expenses.</p>
    </div>
</div>
@endsection
