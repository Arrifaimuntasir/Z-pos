@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0 text-dark">{{ __('Close Station Day') }} · {{ $open->business_date->format('d M Y') }}</h4>
    <span class="text-muted small">{{ __('Count the tanks, read the pump meters and count the cash. The system compares them with what was recorded.') }}</span>
</div>

<form action="{{ route('petroleum.day.close.store') }}" method="POST">
    @csrf
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">{{ __('1. Tanks — physical level (litres)') }}</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="text-muted"><tr><th>{{ __('Tank') }}</th><th class="text-end">{{ __('Opening') }}</th><th class="text-end">{{ __('Delivered') }}</th><th class="text-end">{{ __('Sold') }}</th><th class="text-end">{{ __('Expected') }}</th><th style="min-width:160px;">{{ __('Physical dip (L)') }}</th></tr></thead>
                    <tbody>
                        @foreach($tanks as $t)
                        <tr>
                            <td class="fw-bold">{{ $t['tank']->name }} <span class="text-muted small">{{ $t['tank']->product->name ?? '' }}</span></td>
                            <td class="text-end">{{ number_format($t['opening'], 2) }}</td>
                            <td class="text-end">{{ number_format($t['delivered'], 2) }}</td>
                            <td class="text-end">{{ number_format($t['sold'], 2) }}</td>
                            <td class="text-end fw-bold">{{ number_format($t['expected'], 2) }}</td>
                            <td><input type="number" step="0.001" min="0" name="tank_physical[{{ $t['tank']->id }}]" class="form-control" value="{{ old('tank_physical.'.$t['tank']->id) }}" required></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">{{ __('2. Pump meters — closing reading') }}</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="text-muted"><tr><th>{{ __('Pump / Nozzle') }}</th><th class="text-end">{{ __('Opening meter') }}</th><th class="text-end">{{ __('Litres recorded') }}</th><th class="text-end">{{ __('Expected meter') }}</th><th style="min-width:180px;">{{ __('Closing meter') }}</th></tr></thead>
                    <tbody>
                        @foreach($nozzles as $n)
                        <tr>
                            <td class="fw-bold">{{ __('Pump') }} {{ $n['nozzle']->pump->number }} · {{ __('Nozzle') }} {{ $n['nozzle']->number }} <span class="text-muted small">{{ $n['nozzle']->tank->product->name ?? '' }}</span></td>
                            <td class="text-end">{{ number_format($n['opening'], 3) }}</td>
                            <td class="text-end">{{ number_format($n['sold'], 3) }}</td>
                            <td class="text-end fw-bold">{{ number_format($n['expected'], 3) }}</td>
                            <td><input type="number" step="0.001" min="0" name="nozzle_physical[{{ $n['nozzle']->id }}]" class="form-control" value="{{ old('nozzle_physical.'.$n['nozzle']->id) }}" required></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">{{ __('3. Cash in drawer') }}</h6>
            <div class="row g-3">
                <div class="col-md-4"><div class="small text-muted">{{ __('Opening cash') }}</div><div class="fw-bold">{{ number_format($opening_cash, 2) }} TSh</div></div>
                <div class="col-md-4"><div class="small text-muted">{{ __('Cash sales') }}</div><div class="fw-bold">{{ number_format($cash_sales, 2) }} TSh</div></div>
                <div class="col-md-4"><div class="small text-muted">{{ __('Expected cash') }}</div><div class="fw-bold">{{ number_format($expected_cash, 2) }} TSh</div></div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">{{ __('Counted cash (TSh)') }} <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="physical_cash" class="form-control" value="{{ old('physical_cash') }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-medium">{{ __('Notes') }}</label>
                    <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" maxlength="1000">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('petroleum.day.index') }}" class="btn btn-light px-4">{{ __('Cancel') }}</a>
        <button type="submit" class="btn btn-danger px-4 fw-bold" onclick="return confirm('{{ __('Close this station day? It cannot be reopened, and no more sales or openings can be recorded for it.') }}')">{{ __('Close Day & Reconcile') }}</button>
    </div>
</form>
@endsection
