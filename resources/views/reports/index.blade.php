@extends('layouts.admin')

@section('title', 'Reports Overview')

@php
    // Businesses that don't take customer returns (food is consumed, boutique/services
    // sales are final here) don't need the Defective Items / Return Invoices workflow.
    $showsReturns = !Auth::user()->shop || !in_array(Auth::user()->shop->business_type, ['Restaurant / Food', 'Services / Consulting', 'Clothing / Boutique']);
@endphp

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark">{{ __('Reports Overview') }}</h3>
        <p class="text-muted small mb-0">{{ __('High-level view of your business') }}</p>
    </div>
    <a href="{{ route('reports.index.pdf') }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px;">
        <i class="bi bi-download me-2"></i> {{ __('Download PDF') }}
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
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

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-success" style="width: 40px; height: 40px;">
                            <i class="bi bi-cart-check fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">{{ __('Total Sales (All Time)') }}</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalSalesReal) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-danger" style="width: 40px; height: 40px;">
                            <i class="bi bi-graph-down-arrow fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">{{ __('Total Expenses (All Time)') }}</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalExpenses) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-warning" style="width: 40px; height: 40px;">
                            <i class="bi bi-cash-stack fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">{{ __('Net Profit (All Time)') }}</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($grossProfit - $totalExpenses) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-primary" style="width: 40px; height: 40px;">
                            <i class="bi bi-truck fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">{{ __('Total Purchases (All Time)') }}</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalPurchases) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-info" style="width: 40px; height: 40px;">
                            <i class="bi bi-arrow-return-left fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">{{ __('Total Refunds (All Time)') }}</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalRefunds) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-danger" style="width: 40px; height: 40px;">
                            <i class="bi bi-exclamation-triangle fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-muted">{{ __('Damaged Stock Items') }}</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($damagedStockQty) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #dc3545, #b02a37); color: white;">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-piggy-bank fs-5"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-white-50">{{ __('Total Loss (Defective & Damaged)') }}</h6>
                    </div>
                    <h4 class="fw-bold mb-0">-{{ number_format($totalDefectiveLoss) }} TSh</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
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

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold text-dark mb-0">{{ __('Defective & Damaged Stock') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
                    <span class="text-muted">{{ __('Loss from Damaged Stock (Write-offs)') }}</span>
                    <span class="fw-bold text-danger">-{{ number_format($damagedStockLoss) }} TSh</span>
                </div>
                @if($showsReturns)
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
                    <span class="text-muted">{{ __('Loss from Defective Returns (Unrepaired)') }}</span>
                    <span class="fw-bold text-danger">-{{ number_format($defectiveLoss) }} TSh</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-semibold">{{ __('Total Loss') }}</span>
                    <span class="fw-bold text-danger">-{{ number_format($totalDefectiveLoss) }} TSh</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('defective-stock.index') }}" class="btn btn-sm btn-light shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-box-seam me-1"></i> {{ __('View Damaged Stock') }}
                    </a>
                    @if($showsReturns)
                    <a href="{{ route('returns.defective') }}" class="btn btn-sm btn-light shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-arrow-return-left me-1"></i> {{ __('View Defective Returns') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold text-dark">{{ __('Quick Reports') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('reports.sales') }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-cart-check me-2"></i> {{ __('Sales Report') }}
                    </a>
                    <a href="{{ route('reports.profit_loss') }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-graph-up-arrow me-2"></i> {{ __('Profit & Loss') }}
                    </a>
                    <a href="{{ route('reports.expenses') }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-graph-down-arrow me-2"></i> {{ __('Expenses Report') }}
                    </a>
                    <a href="{{ route('reports.stock_movement') }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-arrow-left-right me-2"></i> {{ __('Stock Movement') }}
                    </a>
                    <a href="{{ route('defective-stock.index') }}" class="btn btn-outline-danger shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-exclamation-triangle me-2"></i> {{ __('Damaged Stock') }}
                    </a>
                    @if($showsReturns)
                    <a href="{{ route('returns.defective') }}" class="btn btn-outline-danger shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-arrow-return-left me-2"></i> {{ __('Defective Items') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
