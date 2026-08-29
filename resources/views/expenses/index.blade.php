@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="font-size: 24px; color: #0f172a;">Expenses</h4>
        <p class="text-muted mb-0" style="font-size: 14px;">Track and manage your business expenses</p>
    </div>
    <a href="{{ route('expenses.create') }}" class="btn btn-dark shadow-sm px-3" style="border-radius: 8px; font-weight: 500;">
        <i class="bi bi-plus-lg me-1"></i> New Expense
    </a>
</div>

<div class="card border mb-4" style="border-radius: 12px; max-width: 280px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div class="card-body p-3 py-4">
        <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL EXPENSES</div>
        <h3 class="fw-bold mb-0" style="color: #0f172a; font-size: 24px;">{{ number_format($totalExpenses, 2) }}</h3>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0">Expense List</h5>
        <form action="{{ route('expenses.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search expenses..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-muted table-light">
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td class="text-muted">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                        <td class="fw-semibold">{{ $expense->description }}</td>
                        <td>
                            @if($expense->category)
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $expense->category }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-danger">{{ number_format($expense->amount) }} TSh</td>
                        <td class="text-end">
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-light text-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                            No expenses recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-end">
            {{ $expenses->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
