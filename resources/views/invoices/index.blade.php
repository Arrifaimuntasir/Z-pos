@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Invoices') }}</h4>
        <span class="text-muted small">{{ __('Manage and view all your invoices') }}</span>
    </div>
    <div>
        <a href="{{ route('sales.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> New Sale (POS)
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0 text-dark fw-bold">{{ __('Recent Invoices') }}</h5>
        <form action="{{ route('invoices.index') }}" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="{{ __('Search by Ref or Customer...') }}" value="{{ request('search') }}" style="font-size: 0.95rem; height: 42px;">
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
                        <th class="border-0 fw-semibold">{{ __('Reference No.') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Product Name') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Customer') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Status') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Total Amount') }}</th>
                        <th class="border-0 fw-semibold text-center">{{ __('View') }}</th>
                        @if(auth()->user()->shop && auth()->user()->shop->business_type === 'Electronics / IT')
                        <th class="border-0 fw-semibold text-center">{{ __('Warranty') }}</th>
                        @endif
                        <th class="pe-4 border-0 fw-semibold text-center rounded-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $sale)
                        <tr>
                            <td class="ps-4">
                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}
                            </td>
                            <td><span class="fw-medium">{{ $sale->reference_no }}</span></td>
                            <td>
                                @if($sale->items && $sale->items->count() > 0)
                                    @php
                                        $productNames = $sale->items->map(function($item) {
                                            return $item->product ? $item->product->name : 'Unknown';
                                        })->take(2)->implode(', ');
                                        
                                        if($sale->items->count() > 2) {
                                            $productNames .= ' (+'.($sale->items->count() - 2).' more)';
                                        }
                                    @endphp
                                    <span class="small fw-medium text-dark" title="{{ $sale->items->map(function($item) { return $item->product ? $item->product->name : 'Unknown'; })->implode(', ') }}">
                                        {{ $productNames }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($sale->customer)
                                    {{ $sale->customer->name }}
                                @else
                                    <span class="text-muted fst-italic">{{ __('Walk-in Customer') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($sale->payment_status == 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">{{ __('Paid') }}</span>
                                @elseif($sale->payment_status == 'partial')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill">{{ __('Partial') }}</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">{{ __('Unpaid') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">
                                {{ number_format($sale->total_amount) }} TSh
                            </td>
                            <td class="text-center">
                                <a href="{{ route('invoices.show', $sale->id) }}" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;" title="{{ __('View Invoice') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            @if(auth()->user()->shop && auth()->user()->shop->business_type === 'Electronics / IT')
                            <td class="text-center">
                                <a href="{{ route('warranties.create', ['sale_id' => $sale->id]) }}" class="btn btn-sm btn-light text-success shadow-sm" style="border-radius: 6px;" title="{{ __('Generate Warranty') }}">
                                    <i class="bi bi-shield-check"></i>
                                </a>
                            </td>
                            @endif
                            <td class="pe-4 text-center">
                                <div class="search-toolbar">
                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice? This will restore the sold items back to stock and reduce your total sales and profit.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;">
                                            <i class="bi bi-trash"></i> {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (auth()->user()->shop && auth()->user()->shop->business_type === 'Electronics / IT') ? 9 : 8 }}" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 text-light-secondary mb-3 d-block"></i>
                                <h5>{{ __('No invoices recorded yet') }}</h5>
                                <p class="mb-4">{{ __('Start selling products by going to the POS.') }}</p>
                                <a href="{{ route('sales.create') }}" class="btn btn-primary px-4">{{ __('Go to POS') }}</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
