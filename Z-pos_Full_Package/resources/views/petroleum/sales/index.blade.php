@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Fuel Sales') }}</h4>
        <form method="GET" class="d-flex gap-2 align-items-center mt-2">
            <input type="date" name="date" value="{{ $date }}" class="form-control form-control-sm" style="max-width: 180px;">
            <button class="btn btn-sm btn-light shadow-sm">{{ __('Show') }}</button>
        </form>
    </div>
    <a href="{{ route('petroleum.sales.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;"><i class="bi bi-plus-lg me-2"></i>{{ __('New Fuel Sale') }}</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="card border-0 shadow-sm p-3" style="border-radius: 14px;"><div class="small text-muted">{{ __('Litres') }}</div><div class="fw-bold fs-5">{{ number_format($totals['litres'], 2) }} L</div></div></div>
    <div class="col-6 col-md-3"><div class="card border-0 shadow-sm p-3" style="border-radius: 14px;"><div class="small text-muted">{{ __('Gross') }}</div><div class="fw-bold fs-5">{{ number_format($totals['gross'], 2) }}</div></div></div>
    <div class="col-6 col-md-3"><div class="card border-0 shadow-sm p-3" style="border-radius: 14px;"><div class="small text-muted">{{ __('Discounts') }}</div><div class="fw-bold fs-5 text-danger">-{{ number_format($totals['discount'], 2) }}</div></div></div>
    <div class="col-6 col-md-3"><div class="card border-0 shadow-sm p-3" style="border-radius: 14px;"><div class="small text-muted">{{ __('Net') }}</div><div class="fw-bold fs-5">{{ number_format($totals['net'], 2) }} TSh</div></div></div>
</div>
@if($byMethod->isNotEmpty())
<div class="mb-3 d-flex flex-wrap gap-2">
    @foreach($byMethod as $method => $amount)
        <span class="badge bg-light text-dark border rounded-pill px-3 py-2">{{ __(ucfirst(str_replace('_', ' ', $method))) }}: {{ number_format($amount, 2) }}</span>
    @endforeach
</div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted"><tr><th class="ps-4">{{ __('Receipt') }}</th><th>{{ __('Time') }}</th><th>{{ __('Pump / Nozzle') }}</th><th>{{ __('Fuel') }}</th><th class="text-end">{{ __('Litres') }}</th><th class="text-end">{{ __('Discount') }}</th><th class="text-end">{{ __('Net') }}</th><th>{{ __('Payment') }}</th><th>{{ __('Attendant') }}</th><th class="pe-4"></th></tr></thead>
                <tbody>
                    @forelse($sales as $s)
                    <tr>
                        <td class="ps-4"><span class="badge bg-light text-dark border">{{ $s->receipt_number }}</span></td>
                        <td>{{ $s->created_at->format('H:i') }}</td>
                        <td>{{ __('Pump') }} {{ $s->pump->number ?? '-' }} · {{ __('Nozzle') }} {{ $s->nozzle->number ?? '-' }}</td>
                        <td>{{ $s->product->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($s->litres, 2) }}</td>
                        <td class="text-end">{{ $s->discount_amount > 0 ? number_format($s->discount_amount, 2) : '-' }}</td>
                        <td class="text-end fw-bold">{{ number_format($s->net_amount, 2) }}</td>
                        <td>{{ __(ucfirst(str_replace('_', ' ', $s->payment_method))) }}</td>
                        <td>{{ $s->attendant->first_name ?? '-' }}</td>
                        <td class="pe-4 text-end"><a href="{{ route('petroleum.sales.show', $s) }}" class="btn btn-sm btn-light shadow-sm rounded-3"><i class="bi bi-receipt"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-5 text-muted">{{ __('No fuel sales on this date.') }} @unless($openDay)<div class="small mt-2">{{ __('Open the station day first.') }}</div>@endunless</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
