@extends('layouts.admin')

@section('content')
@php
    $badge = fn($s) => match($s) {
        'balanced' => ['🟢', 'bg-success', __('Balanced')],
        'over' => ['🟠', 'bg-warning', __('Over')],
        default => ['🔴', 'bg-danger', __('Short')],
    };
    $cb = $badge($closing->cash_status);
@endphp
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3 d-print-none">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Daily Closing Report') }} · {{ $closing->business_date->format('d M Y') }}</h4>
        <span class="text-muted small">{{ __('Closed') }} {{ $closing->closed_at->format('d M Y H:i') }}</span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-light shadow-sm" onclick="window.print()"><i class="bi bi-printer me-2"></i>{{ __('Print') }}</button>
        <a href="{{ route('petroleum.day.index') }}" class="btn btn-light shadow-sm">{{ __('Back') }}</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">{{ __('Stock reconciliation') }}</h6>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="text-muted"><tr><th>{{ __('Tank') }}</th><th class="text-end">{{ __('Opening') }}</th><th class="text-end">{{ __('Delivered') }}</th><th class="text-end">{{ __('Sold') }}</th><th class="text-end">{{ __('Expected') }}</th><th class="text-end">{{ __('Physical') }}</th><th class="text-end">{{ __('Variance') }}</th><th>{{ __('Status') }}</th></tr></thead>
                <tbody>
                    @foreach($closing->lines->where('line_type', 'tank') as $l)
                    @php $b = $badge($l->status); @endphp
                    <tr>
                        <td class="fw-bold">{{ $l->label }}</td>
                        <td class="text-end">{{ number_format($l->opening, 2) }}</td>
                        <td class="text-end">{{ number_format($l->delivered, 2) }}</td>
                        <td class="text-end">{{ number_format($l->sold, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($l->expected, 2) }}</td>
                        <td class="text-end">{{ number_format($l->physical, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($l->variance, 2) }} L</td>
                        <td><span class="badge {{ $b[1] }} bg-opacity-10 text-dark rounded-pill px-3">{{ $b[0] }} {{ $b[2] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">{{ __('Pump meters') }}</h6>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="text-muted"><tr><th>{{ __('Pump / Nozzle') }}</th><th class="text-end">{{ __('Opening meter') }}</th><th class="text-end">{{ __('Closing meter') }}</th><th class="text-end">{{ __('Metered litres') }}</th><th class="text-end">{{ __('Recorded litres') }}</th><th class="text-end">{{ __('Difference') }}</th><th>{{ __('Status') }}</th></tr></thead>
                <tbody>
                    @foreach($closing->lines->where('line_type', 'nozzle') as $l)
                    @php $b = $badge($l->status); @endphp
                    <tr>
                        <td class="fw-bold">{{ $l->label }}</td>
                        <td class="text-end">{{ number_format($l->opening, 3) }}</td>
                        <td class="text-end">{{ number_format($l->physical, 3) }}</td>
                        <td class="text-end">{{ number_format($l->physical - $l->opening, 3) }}</td>
                        <td class="text-end">{{ number_format($l->sold, 3) }}</td>
                        <td class="text-end fw-bold">{{ number_format($l->variance, 3) }}</td>
                        <td><span class="badge {{ $b[1] }} bg-opacity-10 text-dark rounded-pill px-3">{{ $b[0] }} {{ $b[2] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">{{ __('Cash') }}</h6>
        <div class="row g-3">
            <div class="col-6 col-md-3"><div class="small text-muted">{{ __('Expected cash') }}</div><div class="fw-bold fs-5">{{ number_format($closing->expected_cash, 2) }} TSh</div></div>
            <div class="col-6 col-md-3"><div class="small text-muted">{{ __('Counted cash') }}</div><div class="fw-bold fs-5">{{ number_format($closing->physical_cash, 2) }} TSh</div></div>
            <div class="col-6 col-md-3"><div class="small text-muted">{{ __('Difference') }}</div><div class="fw-bold fs-5">{{ number_format($closing->cash_variance, 2) }} TSh</div></div>
            <div class="col-6 col-md-3"><div class="small text-muted">{{ __('Status') }}</div><span class="badge {{ $cb[1] }} bg-opacity-10 text-dark rounded-pill px-3 py-2">{{ $cb[0] }} {{ $cb[2] }}</span></div>
        </div>
        @if($closing->notes)<p class="mt-3 mb-0 small"><span class="text-muted">{{ __('Notes') }}:</span> {{ $closing->notes }}</p>@endif
    </div>
</div>

<p class="small text-muted">{{ __('Tolerance: tanks ±0.5 L, cash ±500 TSh. Anything beyond this is reported as Over or Short.') }}</p>
@endsection
