@extends('layouts.admin')

@section('title', 'Profit and Loss Report')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark">{{ __('Profit & Loss') }}</h3>
        <p class="text-muted small mb-0">{{ __('Financial performance report') }}</p>
    </div>
    <a href="{{ route('reports.profit_loss.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px;">
        <i class="bi bi-download me-2"></i> {{ __('Download PDF') }}
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('reports.profit_loss') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">{{ __('Start Date') }}</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('End Date') }}</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-funnel me-2"></i> {{ __('Filter') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-success" style="width: 40px; height: 40px;">
                        <i class="bi bi-graph-up-arrow fs-5"></i>
                    </div>
                    <h6 class="mb-0 fw-semibold text-muted">{{ __('Gross Profit') }}</h6>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ number_format($grossProfit) }} TSh</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-danger" style="width: 40px; height: 40px;">
                        <i class="bi bi-graph-down-arrow fs-5"></i>
                    </div>
                    <h6 class="mb-0 fw-semibold text-muted">{{ __('Total Expenses') }}</h6>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalExpenses) }} TSh</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 {{ $netProfit >= 0 ? 'bg-primary text-white' : 'bg-danger text-white' }}">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                    <h6 class="mb-0 fw-semibold {{ $netProfit >= 0 ? 'text-white-50' : 'text-white-50' }}">Net Profit / Loss</h6>
                </div>
                <h3 class="fw-bold mb-0">{{ number_format($netProfit) }} TSh</h3>
            </div>
        </div>
    </div>

    @if($defectiveLoss > 0)
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-danger" style="width: 40px; height: 40px;">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                    <h6 class="mb-0 fw-semibold text-muted">{{ __('Defective Stock Loss (Unrepaired)') }}</h6>
                </div>
                <h3 class="fw-bold mb-0 text-danger">-{{ number_format($defectiveLoss) }} TSh</h3>
                <p class="text-muted small mb-0 mt-2">{{ __('Cost of returned defective items not yet marked as repaired. Already included in Gross Profit above — mark items as repaired to recover this.') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($damagedStockLoss > 0)
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 text-danger" style="width: 40px; height: 40px;">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                    <h6 class="mb-0 fw-semibold text-muted">{{ __('Damaged Stock Loss (Write-offs)') }}</h6>
                </div>
                <h3 class="fw-bold mb-0 text-danger">-{{ number_format($damagedStockLoss) }} TSh</h3>
                <p class="text-muted small mb-0 mt-2">{{ __('Cost of stock written off as defective/damaged in this period. Already included in Gross Profit above.') }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
