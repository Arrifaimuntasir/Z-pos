@extends('layouts.admin')

@section('title', 'Record Expense')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('expenses.index') }}" class="btn btn-light rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Record New Expense</h4>
</div>

<div class="card border-0 shadow-sm rounded-4" style="max-width: 600px;">
    <div class="card-body p-4">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="form-label fw-semibold text-muted small text-uppercase">Description <span class="text-danger">*</span></label>
                <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" required autofocus placeholder="What was this expense for?">
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Amount <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">TSh</span>
                        <input type="number" step="0.01" name="amount" class="form-control border-start-0 @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="0" required>
                    </div>
                    @error('amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                    @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-muted small text-uppercase">Category (Optional)</label>
                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" placeholder="e.g. Rent, Utilities, Transport">
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex justify-content-end pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">Record Expense</button>
            </div>
        </form>
    </div>
</div>
@endsection
