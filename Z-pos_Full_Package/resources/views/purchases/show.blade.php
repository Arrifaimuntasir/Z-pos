@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Purchase Details') }}</h4>
        <span class="text-muted small">Reference: {{ $purchase->reference_no }}</span>
    </div>
    <div>
        
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3">{{ __('Supplier Information') }}</h6>
                @if($purchase->supplier)
                    <p class="mb-1"><span class="text-muted">{{ __('Name:') }}</span> <strong class="ms-2">{{ $purchase->supplier->name }}</strong></p>
                    <p class="mb-1"><span class="text-muted">{{ __('Contact:') }}</span> <span class="ms-2">{{ $purchase->supplier->contact_person ?? '-' }}</span></p>
                    <p class="mb-1"><span class="text-muted">{{ __('Phone:') }}</span> <span class="ms-2">{{ $purchase->supplier->phone ?? '-' }}</span></p>
                    <p class="mb-0"><span class="text-muted">{{ __('Email:') }}</span> <span class="ms-2">{{ $purchase->supplier->email ?? '-' }}</span></p>
                @else
                    <p class="text-muted mb-0">{{ __('Supplier information unavailable.') }}</p>
                @endif
            </div>
        </div>
        
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3">{{ __('Purchase Information') }}</h6>
                <p class="mb-1"><span class="text-muted">{{ __('Date:') }}</span> <strong class="ms-2">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</strong></p>
                <p class="mb-1"><span class="text-muted">{{ __('Status:') }}</span> 
                    <span class="ms-2 badge {{ $purchase->status == 'completed' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($purchase->status) }}</span>
                </p>
                <p class="mb-0 mt-3"><span class="text-muted">{{ __('Notes:') }}</span><br>
                    <span class="small">{{ $purchase->notes ?? 'No notes provided.' }}</span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">{{ __('Purchased Items') }}</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('Product Name') }}</th>
                                <th class="text-end">{{ __('Unit Cost') }}</th>
                                <th class="text-center">{{ __('Qty') }}</th>
                                <th class="text-end">{{ __('Subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                                <td class="text-end">{{ number_format($item->unit_cost, 2) }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end fw-semibold">{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">{{ __('Grand Total') }}</td>
                                <td class="text-end fw-bold fs-5 text-primary">{{ number_format($purchase->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
