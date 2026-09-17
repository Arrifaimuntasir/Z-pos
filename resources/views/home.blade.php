@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    /* ===== DASHBOARD DESIGN SYSTEM ===== */
    .dashboard-wrapper { padding: 0; }

    /* Header */
    .dash-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }
    .dash-header h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .dash-header .date-pill {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 2px;
    }
    .filter-select {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
        background: #fff;
        cursor: pointer;
        outline: none;
        transition: border .2s;
        min-width: 190px;
    }
    .filter-select:focus { border-color: #6366f1; }

    /* ===== KPI CARDS ===== */
    .kpi-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
        transition: box-shadow .2s, transform .2s;
        height: 100%;
    }
    .kpi-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .kpi-card-colored {
        border: none;
        color: #fff;
    }
    .kpi-card-colored .kpi-label,
    .kpi-card-colored .kpi-desc { color: rgba(255,255,255,0.8) !important; }
    .kpi-card-colored .kpi-value { color: #fff; }

    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .kpi-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 4px;
    }
    .kpi-value {
        font-size: 1.55rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 4px;
    }
    .kpi-value .kpi-currency {
        font-size: 0.78rem;
        font-weight: 500;
        color: #94a3b8;
        margin-left: 2px;
    }
    .kpi-desc {
        font-size: 0.7rem;
        color: #94a3b8;
        margin: 0;
    }
    .kpi-accent {
        position: absolute;
        bottom: -16px;
        right: -16px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        opacity: 0.08;
    }

    /* Gradient KPI backgrounds */
    .kpi-blue    { background: linear-gradient(135deg, #4f46e5, #7c3aed); }
    .kpi-green   { background: linear-gradient(135deg, #059669, #10b981); }
    .kpi-orange  { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .kpi-red     { background: linear-gradient(135deg, #dc2626, #ef4444); }
    .kpi-teal    { background: linear-gradient(135deg, #0891b2, #06b6d4); }

    /* Icon bg light versions */
    .icon-blue   { background: #ede9fe; color: #4f46e5; }
    .icon-green  { background: #d1fae5; color: #059669; }
    .icon-orange { background: #fef3c7; color: #d97706; }
    .icon-red    { background: #fee2e2; color: #dc2626; }
    .icon-teal   { background: #cffafe; color: #0891b2; }

    /* ===== CHART CARDS ===== */
    .chart-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 20px 14px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        height: 100%;
    }
    .chart-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .chart-card-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .chart-badge {
        font-size: 0.7rem;
        background: #f1f5f9;
        color: #64748b;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Cash flow summary strip */
    .cashflow-strip {
        display: flex;
        gap: 16px;
        padding: 14px 0 0;
        border-top: 1px solid #f1f5f9;
        margin-top: 8px;
        flex-wrap: wrap;
    }
    .cashflow-item { flex: 1; min-width: 90px; }
    .cashflow-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .cashflow-label { font-size: 0.68rem; color: #94a3b8; font-weight: 600; margin-bottom: 2px; }
    .cashflow-amount { font-size: 0.95rem; font-weight: 800; color: #0f172a; }

    /* ===== LEGEND DOTS ===== */
    .legend-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* ===== RECENT SALES TABLE ===== */
    .table-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .recent-table th {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #94a3b8;
        border: none;
        padding: 0 12px 10px;
        background: transparent;
    }
    .recent-table td {
        font-size: 0.82rem;
        border: none;
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
    }
    .recent-table tbody tr:last-child td { border-bottom: none; }
    .recent-table tbody tr:hover td { background: #f8fafc; }
    .ref-badge {
        font-size: 0.72rem;
        font-weight: 700;
        color: #4f46e5;
        background: #ede9fe;
        padding: 2px 8px;
        border-radius: 6px;
        letter-spacing: .3px;
    }
    .status-pill {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* ===== QUICK ACTIONS ===== */
    .quick-action-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .quick-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
        border: 1.5px solid #e2e8f0;
        transition: all .2s;
        background: #fff;
        margin-bottom: 8px;
    }
    .quick-btn:hover { border-color: #4f46e5; color: #4f46e5; background: #ede9fe; }
    .quick-btn .qb-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    /* ===== TOP PRODUCTS ===== */
    .product-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 0;
        border-bottom: 1px solid #f8fafc;
    }
    .product-row:last-child { border-bottom: none; }
    .product-rank {
        width: 22px; height: 22px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.65rem;
        font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .product-rank.rank-1 { background: #fef3c7; color: #d97706; }
    .product-rank.rank-2 { background: #e0e7ff; color: #4f46e5; }
    .product-rank.rank-3 { background: #d1fae5; color: #059669; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .kpi-value { font-size: 1.25rem; }
        .chart-card { padding: 14px; }
        .table-card { padding: 14px; }
        .cashflow-strip { gap: 10px; }
        .filter-select { min-width: unset; width: 100%; }
        .dash-header { gap: 10px; }
        .quick-btn { padding: 9px 12px; }
    }
    @media (max-width: 480px) {
        .kpi-card { padding: 15px; }
        .kpi-icon { width: 38px; height: 38px; font-size: 15px; }
    }
</style>

<div class="dashboard-wrapper">

    {{-- ===== HEADER ===== --}}
    <div class="dash-header">
        <div>
            <h4><i class="bi bi-speedometer2 me-2 text-primary"></i>{{ __('Dashboard') }}</h4>
            <div class="date-pill">{{ __('Business statistics') }} &nbsp;·&nbsp; {{ date('l, d M Y') }}</div>
        </div>
        <select id="dashboardFilter" class="filter-select" onchange="window.location.href='?filter='+this.value">
            <option value="overall" {{ $filter == 'overall' ? 'selected' : '' }}>📊 {{ __('Overall statistics') }}</option>
            <option value="today"   {{ $filter == 'today'   ? 'selected' : '' }}>📅 {{ __("Today's statistics") }}</option>
            <option value="month"   {{ $filter == 'month'   ? 'selected' : '' }}>📆 {{ __("This month's statistics") }}</option>
            <option value="half_year" {{ $filter == 'half_year' ? 'selected' : '' }}>📈 {{ __('Half-year statistics') }}</option>
            <option value="year"    {{ $filter == 'year'    ? 'selected' : '' }}>🗓️ {{ __('Yearly statistics') }}</option>
        </select>
    </div>

    {{-- ===== KPI CARDS ===== --}}
    <div class="row g-3 mb-4">

        {{-- Total Sales --}}
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="kpi-icon icon-blue"><i class="bi bi-cash-stack"></i></div>
                    <span class="kpi-label">{{ __('Total Sales') }}</span>
                </div>
                <div class="kpi-value">{{ number_format($totalSales) }}<span class="kpi-currency">TSh</span></div>
                <p class="kpi-desc">{{ __('Sales revenue received') }}</p>
            </div>
        </div>

        {{-- Gross Profit --}}
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="kpi-icon icon-teal"><i class="bi bi-graph-up-arrow"></i></div>
                    <span class="kpi-label">{{ __('Gross Profit') }}</span>
                </div>
                <div class="kpi-value">{{ number_format($grossProfit) }}<span class="kpi-currency">TSh</span></div>
                <p class="kpi-desc">{{ __('Revenue minus cost of goods') }}</p>
            </div>
        </div>

        @if($isAdmin)
        {{-- Purchases --}}
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="kpi-icon icon-orange"><i class="bi bi-bag-check"></i></div>
                    <span class="kpi-label">{{ __('Purchases') }}</span>
                </div>
                <div class="kpi-value" style="color:#d97706;">{{ number_format($totalPurchases) }}<span class="kpi-currency">TSh</span></div>
                <p class="kpi-desc">{{ __('Stock bought from suppliers') }}</p>
            </div>
        </div>

        {{-- Expenses --}}
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="kpi-icon icon-red"><i class="bi bi-receipt-cutoff"></i></div>
                    <span class="kpi-label">{{ __('Expenses') }}</span>
                </div>
                <div class="kpi-value" style="color:#dc2626;">{{ number_format($totalExpense) }}<span class="kpi-currency">TSh</span></div>
                <p class="kpi-desc">{{ __('Rent, utilities, salaries etc.') }}</p>
            </div>
        </div>

        {{-- Net Profit / Loss - Full-width accent card --}}
        <div class="col-12">
            @php $isProfit = $netProfit >= 0; @endphp
            <div class="kpi-card kpi-card-colored {{ $isProfit ? 'kpi-green' : 'kpi-red' }}" style="padding: 20px 24px;">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="kpi-icon" style="background:rgba(255,255,255,0.2); color:#fff; width:52px; height:52px; font-size:22px;">
                            <i class="bi {{ $isProfit ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="kpi-label" style="color:rgba(255,255,255,0.7);">{{ $isProfit ? __('Net Profit') : __('Net Loss') }}</div>
                        <div class="kpi-value" style="font-size:1.8rem;">
                            {{ $isProfit ? '+' : '' }}{{ number_format($netProfit) }}
                            <span class="kpi-currency" style="color:rgba(255,255,255,0.7);">TSh</span>
                        </div>
                    </div>
                    <div class="col-auto d-none d-sm-block">
                        <p class="kpi-desc mb-0" style="text-align:right; font-size:0.75rem; color:rgba(255,255,255,0.75);">
                            {{ __('Gross Profit - Expenses') }}<br>
                            <strong style="color:#fff;">{{ number_format($grossProfit) }}</strong> - <strong style="color:#fff;">{{ number_format($totalExpense) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if($isAdmin)
    {{-- ===== CASH FLOW CHART ===== --}}
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-card-header">
                    <span class="chart-card-title"><i class="bi bi-activity me-2 text-primary"></i>{{ __('Cash Flow') }}</span>
                    <span class="chart-badge">{{ __('This year') }} · {{ __('monthly') }}</span>
                </div>
                <div id="cashFlowChart"></div>
                <div class="cashflow-strip">
                    <div class="cashflow-item">
                        <div class="cashflow-label"><span class="cashflow-dot" style="background:#10b981;"></span>{{ __('Cash In') }}</div>
                        <div class="cashflow-amount">{{ number_format(array_sum($monthlyIncome)) }} <small style="font-size:.65rem;color:#94a3b8;">TSh</small></div>
                    </div>
                    <div class="cashflow-item">
                        <div class="cashflow-label"><span class="cashflow-dot" style="background:#f59e0b;"></span>{{ __('Cash Out') }}</div>
                        <div class="cashflow-amount">{{ number_format(array_sum($monthlyExpense)) }} <small style="font-size:.65rem;color:#94a3b8;">TSh</small></div>
                    </div>
                    <div class="cashflow-item">
                        @php $netCash = array_sum($monthlyNetCash); @endphp
                        <div class="cashflow-label"><span class="cashflow-dot" style="background:#6366f1;"></span>{{ __('Net Cash Flow') }}</div>
                        <div class="cashflow-amount" style="color:{{ $netCash >= 0 ? '#059669' : '#dc2626' }}">
                            {{ $netCash >= 0 ? '+' : '' }}{{ number_format($netCash) }} <small style="font-size:.65rem;color:#94a3b8;">TSh</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== BAR + DONUT CHARTS ===== --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-7">
            <div class="chart-card">
                <div class="chart-card-header">
                    <span class="chart-card-title"><i class="bi bi-bar-chart-line me-2 text-success"></i>{{ __('Income & Expenses') }}</span>
                    <span class="chart-badge">{{ __('monthly') }}</span>
                </div>
                <div id="incomeExpenseChart"></div>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="chart-card">
                <div class="chart-card-header">
                    <span class="chart-card-title"><i class="bi bi-pie-chart me-2 text-warning"></i>{{ __('Top Expenses') }}</span>
                    <span class="chart-badge">{{ __('cumulative') }}</span>
                </div>
                <div id="topExpensesChart"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== RECENT SALES + QUICK ACTIONS ===== --}}
    <div class="row g-3 mb-3">
        {{-- Recent Sales --}}
        <div class="col-12 col-lg-8">
            <div class="table-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="chart-card-title"><i class="bi bi-receipt me-2 text-primary"></i>{{ __('Recent Sales') }}</span>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm rounded-pill px-3" style="font-size:0.75rem;background:#ede9fe;color:#4f46e5;font-weight:600;border:none;">{{ __('View All') }} →</a>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Reference') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td><span class="ref-badge">{{ $sale->reference_no }}</span></td>
                                <td style="color:#64748b;">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                                <td>
                                    @if($sale->payment_status == 'paid')
                                        <span class="status-pill" style="background:#d1fae5;color:#059669;">✓ {{ __('Paid') }}</span>
                                    @elseif($sale->payment_status == 'partial')
                                        <span class="status-pill" style="background:#fef3c7;color:#d97706;">~ {{ __('Partial') }}</span>
                                    @else
                                        <span class="status-pill" style="background:#fee2e2;color:#dc2626;">✗ {{ __('Unpaid') }}</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold" style="color:#0f172a;">
                                    {{ number_format($sale->total_amount) }}
                                    <span style="font-size:0.68rem;color:#94a3b8;font-weight:400;">TSh</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color:#94a3b8;">
                                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                                    {{ __('No recent sales found.') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Actions + Top Products --}}
        <div class="col-12 col-lg-4">
            {{-- Quick Actions --}}
            <div class="quick-action-card mb-3">
                <div class="chart-card-title mb-3"><i class="bi bi-lightning-charge me-2 text-warning"></i>{{ __('Quick Actions') }}</div>
                <a href="{{ route('sales.create') }}" class="quick-btn">
                    <div class="qb-icon icon-blue"><i class="bi bi-cart-plus"></i></div>
                    {{ __('Record new sales') }}
                </a>
                <a href="{{ route('products.create') }}" class="quick-btn">
                    <div class="qb-icon icon-green"><i class="bi bi-plus-square"></i></div>
                    {{ __('Add Product') }}
                </a>
                <a href="{{ route('purchases.create') }}" class="quick-btn">
                    <div class="qb-icon icon-orange"><i class="bi bi-box-arrow-in-down"></i></div>
                    {{ __('Record new stock intake') }}
                </a>
                <a href="{{ route('expenses.create') }}" class="quick-btn" style="margin-bottom:0;">
                    <div class="qb-icon icon-red"><i class="bi bi-wallet2"></i></div>
                    {{ __('Record Expense') }}
                </a>
            </div>

            {{-- Top Products Today --}}
            @if(isset($topProducts) && $topProducts->count())
            <div class="quick-action-card">
                <div class="chart-card-title mb-3"><i class="bi bi-trophy me-2 text-warning"></i>{{ __('Top Products Today') }}</div>
                @foreach($topProducts->take(5) as $i => $product)
                <div class="product-row">
                    <div class="product-rank rank-{{ $i+1 }}">{{ $i+1 }}</div>
                    <div class="flex-grow-1" style="min-width:0;">
                        <div style="font-size:0.8rem;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $product->name }}
                        </div>
                        <div style="font-size:0.68rem;color:#94a3b8;">{{ $product->total_qty ?? 0 }} {{ __('units') }}</div>
                    </div>
                    <div style="font-size:0.78rem;font-weight:700;color:#4f46e5;white-space:nowrap;">
                        {{ number_format($product->total_revenue ?? 0) }} <span style="font-size:0.6rem;color:#94a3b8;font-weight:400;">TSh</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    const months = [
        '{{ __('Jan') }}','{{ __('Feb') }}','{{ __('Mar') }}','{{ __('Apr') }}',
        '{{ __('May') }}','{{ __('Jun') }}','{{ __('Jul') }}','{{ __('Aug') }}',
        '{{ __('Sep') }}','{{ __('Oct') }}','{{ __('Nov') }}','{{ __('Dec') }}'
    ];

    const commonChart = {
        toolbar: { show: false },
        fontFamily: 'Inter, sans-serif',
        animations: { enabled: true, easing: 'easeinout', speed: 600 }
    };

    @if($isAdmin)
    // --- 1. Cash Flow Area Chart ---
    new ApexCharts(document.querySelector("#cashFlowChart"), {
        series: [{ name: '{{ __('Net Cash Flow') }}', data: {!! json_encode($monthlyNetCash) !!} }],
        chart: { ...commonChart, type: 'area', height: 200 },
        colors: ['#6366f1'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 100] }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        xaxis: {
            categories: months,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { fontSize: '11px', colors: '#94a3b8' } }
        },
        yaxis: { labels: { formatter: v => v === 0 ? '0' : (v/1000).toFixed(0)+'K', style: { colors: '#94a3b8', fontSize: '11px' } } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, padding: { left: 0, right: 0 } },
        tooltip: { y: { formatter: val => val.toLocaleString() + " TSh" } }
    }).render();

    // --- 2. Income & Expenses Bar Chart ---
    new ApexCharts(document.querySelector("#incomeExpenseChart"), {
        series: [
            { name: '{{ __('Cash In') }}',  data: {!! json_encode($monthlyIncome) !!} },
            { name: '{{ __('Cash Out') }}', data: {!! json_encode($monthlyExpense) !!} }
        ],
        chart: { ...commonChart, type: 'bar', height: 240 },
        colors: ['#10b981', '#f59e0b'],
        plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 5, borderRadiusApplication: 'end' } },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: {
            categories: months,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { fontSize: '11px', colors: '#94a3b8' } }
        },
        yaxis: { labels: { formatter: v => v === 0 ? '0' : (v/1000).toFixed(0)+'K', style: { colors: '#94a3b8', fontSize:'11px' } } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        legend: { position: 'top', horizontalAlign: 'left', markers: { radius: 10 }, fontSize: '12px' },
        tooltip: { y: { formatter: val => val.toLocaleString() + " TSh" } }
    }).render();

    // --- 3. Top Expenses Donut Chart ---
    var topExpenseData   = ({!! json_encode($topExpenseData) !!}).map(Number);
    var topExpenseLabels = {!! json_encode($topExpenseLabels) !!};

    new ApexCharts(document.querySelector("#topExpensesChart"), {
        series: topExpenseData.length > 0 ? topExpenseData : [1],
        labels: topExpenseLabels.length > 0 ? topExpenseLabels : ['{{ __('No Data') }}'],
        chart: { ...commonChart, type: 'donut', height: 270 },
        colors: ['#10b981', '#6366f1', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        name: { fontSize: '13px', color: '#64748b' },
                        value: { fontSize: '18px', fontWeight: 700, color: '#0f172a', formatter: val => Number(val).toLocaleString() },
                        total: {
                            show: true, showAlways: true,
                            label: '{{ __('All Expenses') }}',
                            fontSize: '12px', color: '#94a3b8',
                            formatter: w => topExpenseData.length === 0 ? '0' : w.globals.seriesTotals.reduce((a,b) => a+b, 0).toLocaleString()
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: { show: true, position: 'bottom', fontSize: '11px' },
        stroke: { show: false },
        tooltip: { y: { formatter: val => topExpenseData.length > 0 ? val.toLocaleString() + " TSh" : "" } }
    }).render();
    @endif
});
</script>
@endsection
