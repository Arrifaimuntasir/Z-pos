@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    .dash-card {
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: none;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .dash-card-blue {
        background: #0d6efd;
        color: white;
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
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
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
        <h3 class="fw-bold mb-1">Dashboard</h3>
        <p class="text-muted small mb-0">Business statistics &middot; {{ date('M d, Y') }}</p>
    </div>
    <div>
        <select class="form-select border-0 shadow-sm rounded-pill px-4 bg-white" style="cursor: pointer;">
            <option>Overall statistics</option>
            <option>This Month</option>
            <option>This Year</option>
        </select>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col">
        <div class="dash-card dash-card-blue">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small fw-semibold opacity-75">Total Revenue</span>
                <div class="dash-icon-box bg-white bg-opacity-25 text-white">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0">-210,000 <span class="fs-6 fw-normal opacity-75">TSh</span></h3>
        </div>
    </div>
    
    <div class="col">
        <div class="dash-card dash-card-white">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted fw-semibold">Total Income</span>
                <div class="dash-icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark">0 <span class="fs-6 text-muted fw-normal">TSh</span></h3>
        </div>
    </div>

    <div class="col">
        <div class="dash-card dash-card-white">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted fw-semibold">Total Expense</span>
                <div class="dash-icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-graph-down-arrow"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark">210,000 <span class="fs-6 text-muted fw-normal">TSh</span></h3>
        </div>
    </div>

    <div class="col">
        <div class="dash-card dash-card-white">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted fw-semibold">Outstanding Invoices</span>
                <div class="dash-icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-warning">120,000 <span class="fs-6 opacity-75 fw-normal">TSh</span></h3>
        </div>
    </div>

    <div class="col">
        <div class="dash-card dash-card-white">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted fw-semibold">Total Customers</span>
                <div class="dash-icon-box" style="background: #f3e8ff; color: #a855f7;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-dark" style="color: #a855f7 !important;">5</h3>
        </div>
    </div>
</div>

<!-- Cash Flow Analytics -->
<div class="d-flex justify-content-between align-items-center mb-3 mt-5">
    <h5 class="fw-bold mb-0">Analytics</h5>
    <select class="form-select border-0 shadow-sm rounded-pill px-4 bg-white" style="width: auto; cursor: pointer;">
        <option>Yearly</option>
        <option>Monthly</option>
    </select>
</div>

<div class="chart-container mb-4">
    <div class="row">
        <div class="col-md-9 border-end pe-4">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold">Cash Flow</h6>
                <span class="text-muted small">This year - monthly</span>
            </div>
            <div style="height: 250px;">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>
        <div class="col-md-3 ps-4 d-flex flex-column justify-content-center">
            <div class="legend-item">
                <div class="legend-dot bg-success"></div>
                <div>
                    <div class="text-muted small">Total Income</div>
                    <div class="fw-bold fs-5">+ 0</div>
                </div>
            </div>
            <div class="legend-item">
                <div class="legend-dot bg-warning"></div>
                <div>
                    <div class="text-muted small">Total Expenses</div>
                    <div class="fw-bold fs-5">- 210,000</div>
                </div>
            </div>
            <div class="legend-item mb-0">
                <div class="legend-dot bg-danger"></div>
                <div>
                    <div class="text-muted small">Net Cash Flow</div>
                    <div class="fw-bold fs-5">- 210,000</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row: Additional Charts and Tables -->
<div class="row g-4 mb-4">
    <!-- Income & Expenses Bar Chart -->
    <div class="col-md-6">
        <div class="chart-container h-100">
            <div class="d-flex justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Income & Expenses</h6>
                <span class="text-muted small">This year - monthly</span>
            </div>
            <div class="d-flex mb-4">
                <div class="me-4">
                    <div class="d-flex align-items-center mb-1">
                        <div class="legend-dot bg-success"></div>
                        <span class="text-muted small">Total Income</span>
                    </div>
                    <div class="fw-bold">0 TSh</div>
                </div>
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <div class="legend-dot" style="background: #fd7e14;"></div>
                        <span class="text-muted small">Total Expenses</span>
                    </div>
                    <div class="fw-bold">210,000 TSh</div>
                </div>
            </div>
            <div style="height: 200px;">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
            <p class="text-muted small mt-3 mb-0">* Income and expense values displayed are exclusive of taxes.</p>
        </div>
    </div>

    <!-- Top Expenses Donut Chart -->
    <div class="col-md-6">
        <div class="chart-container h-100">
            <div class="d-flex justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Top Expenses</h6>
                <span class="text-muted small">This year - monthly</span>
            </div>
            <div class="row align-items-center h-100" style="min-height: 200px;">
                <div class="col-md-6 text-center position-relative h-100">
                    <canvas id="topExpensesChart"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle text-center" style="width: 100%;">
                        <div class="text-muted small">All Expenses</div>
                        <div class="fw-bold">210,000</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="legend-dot bg-success"></div>
                            <span class="fw-bold text-muted">209,999.98</span>
                        </div>
                        <span class="text-muted small">(100%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Banking Table -->
    <div class="col-md-6">
        <div class="chart-container h-100">
            <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                <h6 class="fw-bold mb-0">Banking</h6>
                <a href="#" class="text-decoration-none fw-semibold">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead class="text-muted border-bottom">
                        <tr>
                            <th class="fw-normal">#</th>
                            <th class="fw-normal">Account</th>
                            <th class="fw-normal text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Mpesa</td>
                            <td class="fw-bold text-end">100,000 TSh</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stock limit products -->
    <div class="col-md-6">
        <div class="chart-container h-100">
            <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                <h6 class="fw-bold mb-0">Stock limit products</h6>
                <a href="#" class="text-decoration-none fw-semibold">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless table-hover text-center">
                    <thead class="text-muted border-bottom">
                        <tr>
                            <th class="fw-normal text-start">#</th>
                            <th class="fw-normal text-start">Product</th>
                            <th class="fw-normal">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="py-4 text-muted">No low-stock products.</td>
                        </tr>
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
                        label: 'Cash Flow',
                        data: [0, 0, 0, 0, -20000, -210000, -210000, -210000, -210000, -210000, -210000, -210000],
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

        // Income & Expenses Chart (Bar)
        var ctxIncExp = document.getElementById('incomeExpenseChart');
        if(ctxIncExp) {
            ctxIncExp = ctxIncExp.getContext('2d');
            new Chart(ctxIncExp, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        { label: 'Income', data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], backgroundColor: '#198754', borderRadius: 4, barPercentage: 0.6 },
                        { label: 'Expenses', data: [0, 0, 0, 0, 10000, 210000, 0, 0, 0, 0, 0, 0], backgroundColor: '#fd7e14', borderRadius: 4, barPercentage: 0.6 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5], drawBorder: false },
                            ticks: { callback: function(value) { return value === 0 ? '0' : value / 1000 + 'K'; } }
                        },
                        x: { grid: { display: false, drawBorder: false }, stacked: false }
                    }
                }
            });
        }

        // Top Expenses (Doughnut)
        var ctxDonut = document.getElementById('topExpensesChart');
        if(ctxDonut) {
            ctxDonut = ctxDonut.getContext('2d');
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Main Expense'],
                    datasets: [{ data: [100], backgroundColor: ['#198754'], borderWidth: 0, cutout: '75%' }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } }
                }
            });
        }
    });
</script>
@endsection
