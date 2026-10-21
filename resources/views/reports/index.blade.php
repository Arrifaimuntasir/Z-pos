@extends('layouts.admin')

@section('title', 'Reports Overview')

@php
    // Businesses that don't take customer returns (food is consumed, boutique/services
    // sales are final here) don't need the Defective Items / Return Invoices workflow.
    $showsReturns = !Auth::user()->shop || !in_array(Auth::user()->shop->business_type, ['Restaurant / Food', 'Services / Consulting', 'Clothing / Boutique']);
@endphp

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3 bg-white p-3 p-md-4 rounded-4 shadow-sm border">
    <div class="d-flex align-items-center gap-3">
        @if(Auth::check() && Auth::user()->shop && Auth::user()->shop->logo_path && file_exists(public_path(Auth::user()->shop->logo_path)))
            <div style="width: 58px; height: 58px; border-radius: 12px; overflow: hidden; background: #ffffff; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 4px;">
                <img src="{{ asset(Auth::user()->shop->logo_path) }}" alt="{{ Auth::user()->shop->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
        @endif
        <div>
            <div class="d-flex align-items-center gap-2">
                <h3 class="fw-bold mb-0 text-dark">{{ __('Reports Overview') }}</h3>
                @if(Auth::check() && Auth::user()->shop)
                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">{{ Auth::user()->shop->name }}</span>
                @endif
            </div>
            <p class="text-muted small mb-0">{{ __('High-level view of your business, category performance, and inventory health') }}</p>
        </div>
    </div>
    <a href="{{ route('reports.index.pdf') }}" class="btn btn-primary shadow-sm px-3 py-2 fw-semibold" style="border-radius: 10px;">
        <i class="bi bi-file-earmark-pdf-fill me-2"></i> {{ __('Download PDF') }}
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
                        <h6 class="mb-0 fw-semibold text-muted">{{ __('Damaged & Defective Items') }}</h6>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalDefectiveQty) }}</h4>
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
{{-- BUSINESS CATEGORY INSIGHTS --}}
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pb-3 border-bottom mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="fw-bold mb-0 text-dark">{{ __('Business Type Insights') }}</h5>
                        <span class="badge bg-primary px-3 py-1 rounded-pill" style="font-size: 0.75rem;">{{ $businessType }}</span>
                    </div>
                    <small class="text-muted">{{ __('Category performance and inventory health adapted to your business model') }}</small>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if(in_array($businessType, ['Supermarket / Grocery', 'Mini-Market', 'Pharmacy / Chemist', 'Cosmetics / Beauty']))
                    @if($expiringProductsCount > 0)
                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning px-3 py-2 rounded-3 d-inline-flex align-items-center">
                            <i class="bi bi-clock-history text-warning me-1"></i>
                            <strong>{{ $expiringProductsCount }}</strong>&nbsp;{{ __('Expiring in 30 Days') }}
                        </span>
                    @endif
                    @if($expiredProductsCount > 0)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-3 d-inline-flex align-items-center">
                            <i class="bi bi-exclamation-octagon text-danger me-1"></i>
                            <strong>{{ $expiredProductsCount }}</strong>&nbsp;{{ __('Expired Products') }}
                        </span>
                    @endif
                @endif
                @if($imeiTrackedCount > 0)
                    <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2 rounded-3 d-inline-flex align-items-center">
                        <i class="bi bi-upc-scan text-info me-1"></i>
                        <strong>{{ $imeiTrackedCount }}</strong>&nbsp;{{ __('IMEI / Serial Tracked Items') }}
                    </span>
                @endif
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-3 bg-light border">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">{{ __('Best Performing Category') }}</div>
                    <div class="fw-bold text-dark fs-6 text-truncate">
                        <i class="bi bi-trophy-fill text-warning me-1"></i>
                        {{ $topCategory ? $topCategory->name : __('No data') }}
                    </div>
                    <div class="small text-success fw-semibold mt-1">
                        {{ $topCategory ? number_format($topCategory->revenue) . ' TSh (' . $topCategory->revenue_share . '%)' : '0 TSh' }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3 bg-light border">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">{{ __('Highest Margin Category') }}</div>
                    <div class="fw-bold text-dark fs-6 text-truncate">
                        <i class="bi bi-graph-up-arrow text-success me-1"></i>
                        {{ $highestMarginCategory ? $highestMarginCategory->name : __('No data') }}
                    </div>
                    <div class="small text-primary fw-semibold mt-1">
                        {{ $highestMarginCategory ? $highestMarginCategory->margin . '% ' . __('Margin') : '0%' }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3 bg-light border">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">{{ __('Highest Stock Category') }}</div>
                    <div class="fw-bold text-dark fs-6 text-truncate">
                        <i class="bi bi-boxes text-primary me-1"></i>
                        {{ $highestStockCategory ? $highestStockCategory->name : __('No data') }}
                    </div>
                    <div class="small text-muted fw-semibold mt-1">
                        {{ $highestStockCategory ? number_format($highestStockCategory->stock_cost) . ' TSh (' . number_format($highestStockCategory->total_stock) . ' ' . __('Total Stock') . ')' : '0 TSh' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CATEGORY PERFORMANCE BREAKDOWN TABLE --}}
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-pie-chart-fill text-primary me-2"></i>{{ __('Category Performance Breakdown') }}
            </h5>
            <p class="text-muted small mb-0">{{ __('Detailed financial and inventory metrics categorized by product groups') }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm" style="max-width: 250px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="catTableFilter" class="form-control bg-light border-start-0" placeholder="{{ __('Search...') }}" onkeyup="filterCategoryTable(this.value)">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="categoryReportTable">
                <thead class="table-light text-muted small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4" style="width: 50px;">#</th>
                        <th>{{ __('Category Name') }}</th>
                        <th class="text-center">{{ __('Products Count') }}</th>
                        <th class="text-center">{{ __('Total Stock') }}</th>
                        <th class="text-end">{{ __('Stock Cost Value') }}</th>
                        <th class="text-center">{{ __('Units Sold') }}</th>
                        <th class="text-end">{{ __('Total Sales (All Time)') }}</th>
                        <th class="text-end">{{ __('Profit') }}</th>
                        <th class="text-center">{{ __('Profit Margin') }}</th>
                        <th class="text-end pe-4">{{ __('Potential Profit') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0" style="font-size: 0.88rem;">
                    @forelse($categoryBreakdown as $idx => $cat)
                        <tr class="cat-row-item">
                            <td class="ps-4 text-muted fw-semibold">{{ $idx + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                        {{ strtoupper(mb_substr($cat->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark cat-name-text">{{ $cat->name }}</span>
                                        @if($idx === 0 && $cat->revenue > 0)
                                            <span class="badge bg-success bg-opacity-10 text-success ms-1" style="font-size: 0.65rem;">Top Seller</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-semibold text-secondary">{{ number_format($cat->product_count) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $cat->total_stock > 10 ? 'bg-light text-dark border' : ($cat->total_stock > 0 ? 'bg-warning bg-opacity-10 text-warning border border-warning' : 'bg-danger bg-opacity-10 text-danger border border-danger') }} px-2 py-1">
                                    {{ number_format($cat->total_stock) }}
                                </span>
                            </td>
                            <td class="text-end text-muted">{{ number_format($cat->stock_cost) }} TSh</td>
                            <td class="text-center fw-bold text-dark">{{ number_format($cat->sold_qty) }}</td>
                            <td class="text-end">
                                <div class="fw-bold text-dark">{{ number_format($cat->revenue) }} TSh</div>
                                <div class="progress mt-1" style="height: 4px;" title="{{ $cat->revenue_share }}% of revenue">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $cat->revenue_share }}%;"></div>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-success">{{ number_format($cat->profit) }} TSh</td>
                            <td class="text-center">
                                <span class="badge {{ $cat->margin >= 25 ? 'bg-success' : ($cat->margin >= 15 ? 'bg-primary' : 'bg-warning text-dark') }} px-2 py-1" style="font-size: 0.75rem;">
                                    {{ $cat->margin }}%
                                </span>
                            </td>
                            <td class="text-end pe-4 fw-semibold text-primary">{{ number_format($cat->potential_profit) }} TSh</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                {{ __('No category data found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterCategoryTable(term) {
    term = (term || '').toLowerCase().trim();
    const rows = document.querySelectorAll('#categoryReportTable .cat-row-item');
    rows.forEach(r => {
        const text = r.querySelector('.cat-name-text')?.textContent.toLowerCase() || '';
        r.style.display = text.includes(term) ? '' : 'none';
    });
}
</script>

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
                    <a href="{{ route('reports.damaged_stock') }}" class="btn btn-sm btn-danger shadow-sm fw-semibold" style="border-radius: 8px;">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i> {{ __('Damaged Stock Report') }}
                    </a>
                    <a href="{{ route('defective-stock.index') }}" class="btn btn-sm btn-light shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-box-seam me-1"></i> {{ __('Manage Damaged Stock') }}
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

    @if(isset($recentDamagedStocks) && $recentDamagedStocks->isNotEmpty())
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>{{ __('Recent Damaged Stock Write-offs') }}
                    </h5>
                    <small class="text-muted">{{ __('Itemized record of inventory written off as damaged or defective') }}</small>
                </div>
                <a href="{{ route('reports.damaged_stock') }}" class="btn btn-sm btn-outline-danger fw-semibold" style="border-radius: 8px;">
                    {{ __('View Full Damaged Stock Report') }} &rarr;
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="ps-4 border-0 fw-semibold">{{ __('Date') }}</th>
                                <th class="border-0 fw-semibold">{{ __('Product') }}</th>
                                <th class="border-0 fw-semibold text-end">{{ __('Quantity') }}</th>
                                <th class="border-0 fw-semibold text-end">{{ __('Unit Cost') }}</th>
                                <th class="border-0 fw-semibold text-end">{{ __('Loss Amount') }}</th>
                                <th class="border-0 fw-semibold">{{ __('Reason') }}</th>
                                <th class="border-0 fw-semibold">{{ __('Recorded By') }}</th>
                                <th class="pe-4 border-0 fw-semibold">{{ __('Branch') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDamagedStocks as $rec)
                            @php
                                $cost = $rec->product->cost_price ?? 0;
                                $loss = $rec->quantity * $cost;
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-medium text-dark">{{ $rec->created_at->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $rec->product->name ?? 'Unknown' }}</div>
                                    @if($rec->product && $rec->product->sku)
                                        <small class="text-muted font-monospace">SKU: {{ $rec->product->sku }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-danger">
                                    -{{ number_format($rec->quantity) }} {{ $rec->product->unit->short_name ?? '' }}
                                </td>
                                <td class="text-end text-muted">{{ number_format($cost) }} TSh</td>
                                <td class="text-end fw-bold text-danger">-{{ number_format($loss) }} TSh</td>
                                <td class="text-muted small">{{ $rec->reason ?: '-' }}</td>
                                <td class="text-dark small">{{ $rec->user->name ?? '-' }}</td>
                                <td class="pe-4">
                                    <span class="badge bg-light text-dark border">{{ $rec->branch->name ?? __('Main') }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                    <a href="{{ route('reports.damaged_stock') }}" class="btn btn-outline-danger shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-shield-x me-2"></i> {{ __('Damaged Stock Report') }}
                    </a>
                    <a href="{{ route('defective-stock.index') }}" class="btn btn-outline-secondary shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-exclamation-triangle me-2"></i> {{ __('Damaged Stock History') }}
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
