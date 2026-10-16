@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Fuel Deliveries') }}</h4>
        <span class="text-muted small">{{ __('Every load received at the station, added to the tank it went into') }}</span>
    </div>
    <a href="{{ route('petroleum.deliveries.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;"><i class="bi bi-plus-lg me-2"></i>{{ __('Record Delivery') }}</a>
</div>
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted"><tr><th class="ps-4">{{ __('Date') }}</th><th>{{ __('Tank') }}</th><th>{{ __('Supplier') }}</th><th>{{ __('Invoice / DN') }}</th><th class="text-end">{{ __('Litres') }}</th><th class="text-end">{{ __('Unit cost') }}</th><th class="text-end pe-4">{{ __('Total cost') }}</th></tr></thead>
                <tbody>
                    @forelse($deliveries as $d)
                    <tr>
                        <td class="ps-4">{{ $d->delivery_date->format('d M Y') }} @if($d->delivery_time)<small class="text-muted">{{ substr($d->delivery_time, 0, 5) }}</small>@endif</td>
                        <td>{{ $d->tank->name ?? '-' }} <small class="text-muted">{{ $d->tank->product->name ?? '' }}</small></td>
                        <td>{{ $d->supplier->name ?? '-' }}</td>
                        <td>{{ $d->invoice_number ?: ($d->delivery_note_number ?: '-') }}</td>
                        <td class="text-end fw-bold">{{ number_format($d->quantity, 2) }}</td>
                        <td class="text-end">{{ number_format($d->unit_cost, 2) }}</td>
                        <td class="text-end pe-4 fw-bold">{{ number_format($d->total_cost, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">{{ __('No deliveries recorded yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
