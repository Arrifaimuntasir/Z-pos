@extends('layouts.admin')

@section('title', 'Expenses Report')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark">{{ __('Expenses Report') }}</h3>
        <p class="text-muted small mb-0">{{ __('Detailed expense history') }}</p>
    </div>
    <div>
        <div class="card bg-danger text-white px-4 py-2 border-0 shadow-sm rounded-pill">
            <span class="small fw-semibold">Total Expenses: {{ number_format($totalExpenses) }} TSh</span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('reports.expenses') }}" method="GET" class="row g-3 align-items-end">
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

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start">{{ __('Date') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Category') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Description') }}</th>
                        <th class="border-0 fw-semibold text-end pe-4 rounded-end">{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td class="ps-4">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                            <td><span class="fw-medium">{{ $expense->category }}</span></td>
                            <td>{{ $expense->description ?? '-' }}</td>
                            <td class="text-end pe-4 fw-bold text-danger">{{ number_format($expense->amount) }} TSh</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-graph-down-arrow fs-1 mb-3 d-block"></i>
                                {{ __('No expenses found for the selected period.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
