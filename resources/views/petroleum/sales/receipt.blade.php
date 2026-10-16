@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;" id="receipt">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <div class="fw-bold fs-5">{{ auth()->user()->shop->name ?? 'Z-POS' }}</div>
                    <div class="small text-muted">{{ $sale->branch->name ?? '' }}</div>
                    <div class="small text-muted">{{ auth()->user()->shop->phone ?? '' }}</div>
                    <div class="small text-muted">{{ auth()->user()->shop->address ?? '' }}</div>
                </div>
                <hr>
                <div class="small">
                    <div class="d-flex justify-content-between"><span>{{ __('Receipt') }}</span><span class="fw-bold">{{ $sale->receipt_number }}</span></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Date') }}</span><span>{{ $sale->created_at->format('d M Y H:i') }}</span></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Pump') }} / {{ __('Nozzle') }}</span><span>{{ $sale->pump->number }} / {{ $sale->nozzle->number }}</span></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Fuel') }}</span><span>{{ $sale->product->name }}</span></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Attendant') }}</span><span>{{ $sale->attendant->first_name ?? '-' }}</span></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Customer') }}</span><span>{{ $sale->customer->name ?? __('Walk-in customer') }}</span></div>
                </div>
                <hr>
                <div class="small">
                    <div class="d-flex justify-content-between"><span>{{ __('Litres') }}</span><span>{{ number_format($sale->litres, 3) }} L</span></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Price/Litre') }}</span><span>{{ number_format($sale->price_per_litre, 2) }}</span></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Gross Amount') }}</span><span>{{ number_format($sale->gross_amount, 2) }}</span></div>
                    <div class="d-flex justify-content-between"><span>{{ __('Discount') }}</span><span>-{{ number_format($sale->discount_amount, 2) }}</span></div>
                </div>
                <hr>
                <div class="d-flex justify-content-between fs-5 fw-bold"><span>{{ __('Net Amount') }}</span><span>{{ number_format($sale->net_amount, 2) }} TSh</span></div>
                <div class="small text-muted mt-1">{{ __('Paid by') }}: {{ __(ucfirst(str_replace('_', ' ', $sale->payment_method))) }}</div>
                <div class="text-center small text-muted mt-4">{{ __('Thank you for your business') }}</div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3 d-print-none">
            <button type="button" class="btn btn-light shadow-sm flex-fill" onclick="window.print()"><i class="bi bi-printer me-2"></i>{{ __('Print') }}</button>
            <a href="{{ route('petroleum.sales.create') }}" class="btn btn-primary shadow-sm flex-fill">{{ __('Next Sale') }}</a>
        </div>
    </div>
</div>
@endsection
