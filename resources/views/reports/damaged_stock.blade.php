@extends('layouts.admin')

@section('title', __('Damaged Stock Report'))

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark">{{ __('Damaged Stock Report') }}</h3>
        <p class="text-muted small mb-0">{{ __('Detailed history and loss calculation of damaged and defective inventory write-offs') }}</p>
    </div>
    <div class="d-flex flex-column flex-sm-row gap-2">
        <div class="card bg-danger text-white px-3 py-2 border-0 shadow-sm rounded-pill d-flex align-items-center justify-content-center">
            <span class="small fw-bold">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ __('Total Loss') }}: {{ number_format($totalLoss) }} TSh
            </span>
        </div>
        <div class="card bg-secondary bg-opacity-10 text-dark px-3 py-2 border-0 shadow-sm rounded-pill d-flex align-items-center justify-content-center">
            <span class="small fw-bold text-danger">
                {{ __('Units') }}: {{ number_format($totalQty) }}
            </span>
        </div>
        <a href="{{ route('reports.damaged_stock.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'search' => $search]) }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-download me-2"></i> {{ __('Download PDF') }}
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('reports.damaged_stock') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">{{ __('Start Date') }}</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">{{ __('End Date') }}</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-muted">{{ __('Search Product') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name, SKU...') }}" value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-funnel me-1"></i> {{ __('Filter') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start">{{ __('Date') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Product') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Cost Price') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Quantity') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Total Loss') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Reason') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Recorded By') }}</th>
                        <th class="pe-4 border-0 fw-semibold rounded-end">{{ __('Branch') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                        @php
                            $cost = $rec->cost_price ?? ($rec->product->cost_price ?? 0);
                            $loss = $rec->loss ?? ($rec->quantity * $cost);
                            $dateObj = \Carbon\Carbon::parse($rec->created_at);
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium text-dark">{{ $dateObj->format('d M Y') }}</span>
                                <div class="text-muted small">{{ $dateObj->format('H:i') }}</div>
                                @if(isset($rec->source_label))
                                    <div class="mt-1"><span class="badge {{ $rec->source_badge ?? 'bg-danger' }}" style="font-size: 0.65rem;">{{ $rec->source_label }}</span></div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $rec->product->name ?? __('Unknown Product') }}</div>
                                @if($rec->product && $rec->product->sku)
                                    <small class="text-muted font-monospace">SKU: {{ $rec->product->sku }}</small>
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($cost) }} TSh</td>
                            <td class="text-end fw-bold text-danger">
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">
                                    -{{ number_format($rec->quantity) }} {{ $rec->product->unit->short_name ?? '' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-danger">-{{ number_format($loss) }} TSh</td>
                            <td>
                                <span class="text-muted small">{{ $rec->reason ?: __('No reason provided') }}</span>
                            </td>
                            <td>
                                <span class="text-dark small fw-medium">{{ $rec->user->name ?? '-' }}</span>
                            </td>
                            <td class="pe-4">
                                <span class="badge bg-light text-dark border">{{ $rec->branch->name ?? __('Main') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check fs-1 text-success mb-3 d-block"></i>
                                {{ __('No damaged stock recorded for the selected period.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($records->isNotEmpty())
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td colspan="3" class="ps-4 text-end">{{ __('Total') }}:</td>
                        <td class="text-end text-danger">{{ number_format($totalQty) }}</td>
                        <td class="text-end text-danger">-{{ number_format($totalLoss) }} TSh</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
