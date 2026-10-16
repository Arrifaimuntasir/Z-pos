@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Station Day') }}</h4>
        <span class="text-muted small">{{ __('Open the day before selling, close it when the shift ends') }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('petroleum.sales.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;"><i class="bi bi-fuel-pump me-2"></i>{{ __('New Fuel Sale') }}</a>
        @if($open && Auth::user()->hasRole('Administrator'))
        <a href="{{ route('petroleum.day.close') }}" class="btn btn-danger px-4 shadow-sm" style="border-radius: 8px;"><i class="bi bi-lock me-2"></i>{{ __('Close Day') }}</a>
        @endif
    </div>
</div>

@if($open)
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border-left: 5px solid #10b981 !important;">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
            <div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-2">{{ __('Open') }}</span>
                <h5 class="fw-bold mb-0">{{ $open->business_date->format('d M Y') }}</h5>
                <small class="text-muted">{{ __('Opened by') }} {{ $open->opened_by ? \App\Models\User::find($open->opened_by)?->first_name : '-' }} · {{ $open->opened_at->format('H:i') }}</small>
            </div>
            <div class="text-end">
                <small class="text-muted d-block">{{ __('Opening cash') }}</small>
                <span class="fw-bold fs-5">{{ number_format($open->opening_cash, 2) }} TSh</span>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-6 col-md-3"><div class="small text-muted">{{ __('Litres sold today') }}</div><div class="fw-bold fs-5">{{ number_format($summary['sales']->sum('litres'), 2) }} L</div></div>
            <div class="col-6 col-md-3"><div class="small text-muted">{{ __('Net sales today') }}</div><div class="fw-bold fs-5">{{ number_format($summary['sales']->sum('net_amount'), 2) }} TSh</div></div>
            <div class="col-6 col-md-3"><div class="small text-muted">{{ __('Cash sales today') }}</div><div class="fw-bold fs-5">{{ number_format($summary['cash_sales'], 2) }} TSh</div></div>
            <div class="col-6 col-md-3"><div class="small text-muted">{{ __('Expected cash in drawer') }}</div><div class="fw-bold fs-5">{{ number_format($summary['expected_cash'], 2) }} TSh</div></div>
        </div>
        <hr>
        <div class="row g-3">
            @foreach($summary['tanks'] as $t)
            <div class="col-md-6">
                <div class="d-flex justify-content-between small"><span class="fw-bold">{{ $t['tank']->name }} · {{ $t['tank']->product->name ?? '' }}</span><span>{{ __('Expected now') }}: {{ number_format($t['expected'], 2) }} L</span></div>
                <div class="text-muted small">{{ __('Opening') }} {{ number_format($t['opening'], 2) }} + {{ __('Delivered') }} {{ number_format($t['delivered'], 2) }} − {{ __('Sold') }} {{ number_format($t['sold'], 2) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@else
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-1">{{ __('No station day is open') }}</h5>
        <p class="text-muted small mb-3">{{ __('Open the day to record tank levels, pump meters and the cash in the drawer. Fuel sales are only allowed on an open day.') }}</p>
        <form action="{{ route('petroleum.day.open') }}" method="POST" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-4">
                <label class="form-label fw-medium">{{ __('Business date') }}</label>
                <input type="date" name="business_date" class="form-control" value="{{ old('business_date', $suggestedDate) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium">{{ __('Opening cash in drawer (TSh)') }}</label>
                <input type="number" step="0.01" min="0" name="opening_cash" class="form-control" value="{{ old('opening_cash', 0) }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100 fw-bold py-2"><i class="bi bi-unlock me-2"></i>{{ __('Open Station Day') }}</button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted"><tr><th class="ps-4">{{ __('Date') }}</th><th>{{ __('Status') }}</th><th class="text-end">{{ __('Opening cash') }}</th><th class="text-end pe-4">{{ __('Closing') }}</th></tr></thead>
                <tbody>
                    @forelse($days as $d)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $d->business_date->format('d M Y') }}</td>
                        <td>@if($d->closed_at)<span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3">{{ __('Closed') }}</span>@else<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('Open') }}</span>@endif</td>
                        <td class="text-end">{{ number_format($d->opening_cash, 2) }}</td>
                        <td class="text-end pe-4">
                            @if($d->closing)
                                <a href="{{ route('petroleum.day.closings.show', $d->closing) }}" class="btn btn-sm btn-light shadow-sm rounded-3">{{ __('View report') }}</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-5 text-muted">{{ __('No station days yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
