@extends('layouts.admin')

@section('title', 'Return Invoices')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Return Invoices') }}</h4>
        <span class="text-muted small">{{ __('Manage returned items and print return receipts') }}</span>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0 text-dark fw-bold">{{ __('Recent Returns') }}</h5>
        <form action="{{ route('returns.index') }}" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="{{ __('Search by Ref...') }}" value="{{ request('search') }}" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text">{{ __('Search') }}</span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start">{{ __('Date') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Return Ref') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Invoice Ref') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Customer') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Items') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Refund Amount') }}</th>
                        <th class="border-0 fw-semibold text-center">{{ __('Print PDF') }}</th>
                        <th class="pe-4 border-0 fw-semibold text-center rounded-end">{{ __('View Sale') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($returns as $rtn)
                    <tr>
                        <td class="ps-4">
                            {{ \Carbon\Carbon::parse($rtn->return_date)->format('M d, Y') }}
                        </td>
                        <td>
                            <span class="fw-medium text-dark">{{ $rtn->reference_no }}</span>
                        </td>
                        <td>
                            <a href="{{ route('sales.show', $rtn->sale_id) }}" class="text-decoration-none fw-medium text-primary">{{ $rtn->sale->reference_no }}</a>
                        </td>
                        <td>
                            @if($rtn->sale->customer)
                                {{ $rtn->sale->customer->name }}
                            @else
                                <span class="text-muted fst-italic">{{ __('Walk-in Customer') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="small fw-medium text-dark">
                                @foreach($rtn->items as $item)
                                    <div>
                                        {{ $item->saleItem && $item->saleItem->product ? $item->saleItem->product->name : 'Unknown' }} ({{ $item->quantity }})
                                        @if($item->condition === 'good')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2" style="font-size: 0.65rem;">{{ __('Good') }}</span>
                                        @elseif($item->condition === 'defective')
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2" style="font-size: 0.65rem;">{{ __('Defective') }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </span>
                        </td>
                        <td class="text-end fw-bold text-danger">
                            {{ number_format($rtn->total_refund) }} TSh
                        </td>
                        <td class="text-center">
                            <a href="{{ route('returns.pdf', $rtn->id) }}" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;" title="{{ __('Print Invoice') }}">
                                <i class="bi bi-file-earmark-pdf fs-5"></i>
                            </a>
                        </td>
                        <td class="pe-4 text-center">
                            <a href="{{ route('sales.show', $rtn->sale_id) }}" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;" title="{{ __('View Sale') }}">
                                <i class="bi bi-eye fs-5"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-arrow-return-left fs-1 text-light-secondary mb-3 d-block"></i>
                            <h5>{{ __('No returns found') }}</h5>
                            <p class="mb-4">{{ __('You do not have any returns or refunds yet.') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            {{ $returns->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
