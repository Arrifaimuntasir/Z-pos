@extends('layouts.admin')

@section('title', 'Edit Expense')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Edit Expense</h4>
    <a href="{{ route('expenses.index') }}" class="btn btn-light shadow-sm rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i> Back to Expenses
    </a>
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('expenses.update', $expense) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Expense Description <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control form-control-lg bg-light border-0" value="{{ old('description', $expense->description) }}" placeholder="e.g. Electricity Bill, Transport..." required>
                        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Amount (TSh) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control form-control-lg bg-light border-0 fw-bold text-primary" value="{{ old('amount', $expense->amount) }}" required min="0">
                            @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mt-4 mt-md-0">
                            <label class="form-label fw-semibold text-muted">Date <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control form-control-lg bg-light border-0" value="{{ old('expense_date', $expense->expense_date) }}" required>
                            @error('expense_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold text-muted">Category (Optional)</label>
                        <input type="text" name="category" class="form-control bg-light border-0" value="{{ old('category', $expense->category) }}" placeholder="e.g. Utilities, Operations, Office">
                        @error('category') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Update Expense
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
