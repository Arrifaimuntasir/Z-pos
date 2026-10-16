@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    .dash-card {
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .dash-card-blue {
        background: #0d6efd;
        color: white;
        border: none;
    }
    .dash-card-white {
        background: white;
    }
    .dash-icon-box {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 10px;
    }
</style>

<!-- Header Row -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark">{{ __('Dashboard') }}</h3>
        <p class="text-muted small mb-0">{{ __('Business statistics') }} &middot; {{ date('M d, Y') }}</p>
    </div>
    <div>
        <select id="dashboardFilter" class="form-select border shadow-sm rounded-3 px-4 bg-white fw-medium text-dark" style="cursor: pointer;" onchange="window.location.href='?filter='+this.value">
            <option value="overall" {{ $filter == 'overall' ? 'selected' : '' }}>{{ __('Overall statistics') }}</option>
            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>{{ __('Today\'s statistics') }}</option>
            <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>{{ __('This month\'s statistics') }}</option>
            <option value="half_year" {{ $filter == 'half_year' ? 'selected' : '' }}>{{ __('Half-year statistics') }}</option>
            <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>{{ __('Yearly statistics') }}</option>
        </select>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-5">
    <div class="col-md-3">
        <div class="dash-card dash-card-white">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted fw-semibold">{{ __('Total Sales') }}</span>
                <div class="dash-icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalSales) }} <span style="font-size: 0.8rem;" class="text-muted fw-normal">TSh</span></h3>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="dash-card dash-card-white">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted fw-semibold">{{ __('Gross Profit') }}</span>
                <div class="dash-icon-box bg-info bg-opacity-10 text-info">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark">{{ number_format($grossProfit) }} <span style="font-size: 0.8rem;" class="text-muted fw-normal">TSh</span></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="dash-card dash-card-white">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted fw-semibold">{{ __('Expenses') }}</span>
                <div class="dash-icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-graph-down"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-danger">{{ number_format($totalExpense) }} <span style="font-size: 0.8rem;" class="fw-normal">TSh</span></h3>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="dash-card {{ $netProfit >= 0 ? 'bg-success text-white' : 'bg-danger text-white' }} border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small fw-semibold opacity-75">{{ $netProfit >= 0 ? __('Net Profit') : __('Net Loss') }}</span>
                <div class="dash-icon-box bg-white bg-opacity-25 text-white">
                    <i class="bi {{ $netProfit >= 0 ? 'bi-emoji-smile' : 'bi-emoji-frown' }}"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0">{{ number_format($netProfit) }} <span style="font-size: 0.8rem;" class="fw-normal opacity-75">TSh</span></h3>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Top Chart: Cash Flow -->
    <div class="col-12">
        <div class="chart-container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0">{{ __('Cash Flow') }}</h6>
                        <span class="text-muted small">{{ __('This year') }} &middot; {{ __('monthly') }}</span>
                    </div>
                    <div id="cashFlowChart" style="min-height: 300px;"></div>
                </div>
                <div class="col-md-3 border-start ps-4">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-1">
                            <div class="legend-dot bg-success"></div>
                            <span class="small text-muted fw-semibold">{{ __('Total Income') }}</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-0">+ {{ number_format(array_sum($monthlyIncome)) }}</h4>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-1">
                            <div class="legend-dot bg-warning"></div>
                            <span class="small text-muted fw-semibold">{{ __('Total Expenses') }}</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-0">- {{ number_format(array_sum($monthlyExpense)) }}</h4>
                    </div>
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <div class="legend-dot bg-danger"></div>
                            <span class="small text-muted fw-semibold">{{ __('Net Cash Flow') }}</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-0">{{ number_format(array_sum($monthlyNetCash)) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Bottom Left: Income vs Expenses -->
    <div class="col-md-7">
        <div class="chart-container h-100">
            <div class="d-flex justify-content-between mb-4">
                <h6 class="fw-bold mb-0">{{ __('Income & Expenses') }}</h6>
                <span class="text-muted small">{{ __('This year') }} &middot; {{ __('monthly') }}</span>
            </div>
            <div id="incomeExpenseChart" style="min-height: 280px;"></div>
        </div>
    </div>
    
    <!-- Bottom Right: Top Expenses -->
    <div class="col-md-5">
        <div class="chart-container h-100">
            <div class="d-flex justify-content-between mb-4">
                <h6 class="fw-bold mb-0">{{ __('Top Expenses') }}</h6>
                <span class="text-muted small">{{ __('This year') }} &middot; {{ __('cumulative') }}</span>
            </div>
            <div id="topExpensesChart" style="min-height: 280px; display: flex; justify-content: center;"></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Recent Sales Table -->
    <div class="col-12">
        <div class="chart-container">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
                <h6 class="fw-bold mb-0">{{ __('Recent Sales') }}</h6>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">{{ __('View All') }}</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @forelse($recentSales as $sale)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $sale->reference_no }}</div>
                                <div class="small text-muted">{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</div>
                            </td>
                            <td>
                                @if($sale->payment_status == 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">{{ __('Paid') }}</span>
                                @elseif($sale->payment_status == 'partial')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">{{ __('Partial') }}</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">{{ __('Unpaid') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($sale->total_amount) }} <span class="fw-normal" style="font-size:0.75rem;">TSh</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">{{ __('No recent sales found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // --- 1. Cash Flow Line Chart ---
        var cashFlowOptions = {
            series: [{
                name: 'Net Cash Flow',
                data: {!! json_encode($monthlyNetCash) !!}
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#0d6efd'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: months,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value === 0 ? '0' : (value / 1000).toFixed(0) + 'K';
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                y: { formatter: function (val) { return val.toLocaleString() + " TSh" } }
            }
        };
        new ApexCharts(document.querySelector("#cashFlowChart"), cashFlowOptions).render();

        // --- 2. Income & Expenses Bar Chart ---
        var barOptions = {
            series: [{
                name: 'Total Income',
                data: {!! json_encode($monthlyIncome) !!}
            }, {
                name: 'Total Expenses',
                data: {!! json_encode($monthlyExpense) !!}
            }],
            chart: {
                type: 'bar',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#10b981', '#f59e0b'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '40%',
                    borderRadius: 4
                },
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: months,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value === 0 ? '0' : (value / 1000).toFixed(0) + 'K';
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                markers: { radius: 12 }
            },
            tooltip: {
                y: { formatter: function (val) { return val.toLocaleString() + " TSh" } }
            }
        };
        new ApexCharts(document.querySelector("#incomeExpenseChart"), barOptions).render();

        // --- 3. Top Expenses Donut Chart ---
        var topExpenseData = {!! json_encode($topExpenseData) !!};
        // Convert array of string numbers to actual numbers for ApexCharts
        topExpenseData = topExpenseData.map(Number);
        
        var topExpenseLabels = {!! json_encode($topExpenseLabels) !!};
        
        var donutOptions = {
            series: topExpenseData.length > 0 ? topExpenseData : [1],
            labels: topExpenseLabels.length > 0 ? topExpenseLabels : ['No Data'],
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: { fontSize: '14px', color: '#64748b' },
                            value: {
                                fontSize: '20px',
                                fontWeight: 700,
                                color: '#0f172a',
                                formatter: function (val) {
                                    return Number(val).toLocaleString();
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'All Expenses',
                                fontSize: '14px',
                                color: '#64748b',
                                formatter: function (w) {
                                    if(topExpenseData.length === 0) return '0';
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString();
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: {
                show: true,
                position: 'bottom'
            },
            stroke: { show: false },
            tooltip: {
                y: { formatter: function(val) { return topExpenseData.length > 0 ? val.toLocaleString() + " TSh" : ""; } }
            }
        };
        new ApexCharts(document.querySelector("#topExpensesChart"), donutOptions).render();
    });
</script>
@endsection
