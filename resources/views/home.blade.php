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
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">Dashboard</h3>
        <p class="text-muted small mb-0">Business statistics &middot; {{ date('M d, Y') }}</p>
    </div>
    <div>
        <select id="dashboardFilter" class="form-select border shadow-sm rounded-3 px-4 bg-white fw-medium text-dark" style="cursor: pointer;" onchange="window.location.href='?filter='+this.value">
            <option value="overall" {{ $filter == 'overall' ? 'selected' : '' }}>Overall statistics</option>
            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Today's statistics</option>
            <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This month's statistics</option>
            <option value="half_year" {{ $filter == 'half_year' ? 'selected' : '' }}>Half-year statistics</option>
            <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Yearly statistics</option>
        </select>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-5">
    <div class="col-md-3">
        <div class="dash-card dash-card-white">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted fw-semibold">Total Sales</span>
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
                <span class="small text-muted fw-semibold">Gross Profit</span>
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
                <span class="small text-muted fw-semibold">Expenses</span>
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
                <span class="small fw-semibold opacity-75">{{ $netProfit >= 0 ? 'Net Profit' : 'Net Loss' }}</span>
                <div class="dash-icon-box bg-white bg-opacity-25 text-white">
                    <i class="bi {{ $netProfit >= 0 ? 'bi-emoji-smile' : 'bi-emoji-frown' }}"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0">{{ number_format($netProfit) }} <span style="font-size: 0.8rem;" class="fw-normal opacity-75">TSh</span></h3>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Cash Flow Chart (Line) -->
    <div class="col-md-7">
        <div class="chart-container h-100">
            <div class="d-flex justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Cash Flow</h6>
                <span class="text-muted small">This year - monthly</span>
            </div>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Sales Table -->
    <div class="col-md-5">
        <div class="chart-container h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">Recent Sales</h6>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
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
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Paid</span>
                                @elseif($sale->payment_status == 'partial')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">Partial</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">Unpaid</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($sale->total_amount) }} <span class="fw-normal" style="font-size:0.75rem;">TSh</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No recent sales found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cash Flow Chart (Line)
        var ctxCash = document.getElementById('cashFlowChart');
        if(ctxCash) {
            ctxCash = ctxCash.getContext('2d');
            var gradientBlue = ctxCash.createLinearGradient(0, 0, 0, 400);
            gradientBlue.addColorStop(0, 'rgba(13, 110, 253, 0.2)');
            gradientBlue.addColorStop(1, 'rgba(13, 110, 253, 0)');
            
            new Chart(ctxCash, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Net Cash Flow',
                        data: {!! json_encode($monthlyNetCash) !!},
                        borderColor: '#0d6efd',
                        backgroundColor: gradientBlue,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#fff',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: { borderDash: [5, 5], drawBorder: false },
                            ticks: { callback: function(value) { return value === 0 ? '0' : value / 1000 + 'K'; } }
                        },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });
        }
    });
</script>
@endsection
