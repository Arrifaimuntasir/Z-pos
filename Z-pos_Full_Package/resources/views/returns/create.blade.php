@extends('layouts.admin')

@section('title', 'Process Return')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Process Return') }}</h4>
        <span class="text-muted small">Invoice #{{ $sale->reference_no }}</span>
    </div>
    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-light shadow-sm" style="border-radius: 8px;">
        <i class="bi bi-arrow-left me-2"></i> {{ __('Back to Invoice') }}
    </a>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="{{ route('sales.returns.store', $sale->id) }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">{{ __('Select Items to Return') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="border-0 rounded-start">{{ __('Product') }}</th>
                                    <th class="border-0">{{ __('Price') }}</th>
                                    <th class="border-0">{{ __('Purchased') }}</th>
                                    <th class="border-0">{{ __('Returned') }}</th>
                                    <th class="border-0" style="width: 120px;">{{ __('Return Qty') }}</th>
                                    <th class="border-0 rounded-end" style="width: 160px;">{{ __('Condition') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $index => $item)
                                @php
                                    $available = $item->quantity - $item->returned_quantity;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $item->product->name }}</div>
                                        @if($item->imei_serial_number)
                                            <small class="text-muted">IMEI: {{ $item->imei_serial_number }}</small>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->returned_quantity }}</td>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][sale_item_id]" value="{{ $item->id }}">
                                        @if($available > 0)
                                            <input type="number" name="items[{{ $index }}][return_quantity]" class="form-control form-control-sm" min="0" max="{{ $available }}" value="0">
                                            <small class="text-muted">Max: {{ $available }}</small>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success">Fully Returned</span>
                                            <input type="hidden" name="items[{{ $index }}][return_quantity]" value="0">
                                        @endif
                                    </td>
                                    <td>
                                        @if($available > 0)
                                            <select name="items[{{ $index }}][condition]" class="form-select form-select-sm">
                                                <option value="good">{{ __('Good (Add to Stock)') }}</option>
                                                <option value="defective">{{ __('Defective (No Stock)') }}</option>
                                            </select>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-4">{{ __('Return Details') }}</h6>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">{{ __('Return Date') }} <span class="text-danger">*</span></label>
                        <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">{{ __('Reason (Optional)') }}</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="{{ __('Why is the customer returning this?') }}"></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-2 shadow-sm" style="border-radius: 8px;" onclick="return confirm('Are you sure you want to process this return?')">
                        <i class="bi bi-arrow-return-left me-2"></i> {{ __('Process Return') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
