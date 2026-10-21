@extends('layouts.admin')

@section('title', 'Stock Movement')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark">{{ __('Stock Movement') }}</h3>
        <p class="text-muted small mb-0">{{ __('See how much of each product you started with, sold, and have left — at a glance.') }}</p>
    </div>
    <a href="{{ route('reports.stock_movement.pdf', ['search' => $search]) }}" class="btn btn-outline-primary shadow-sm" style="border-radius: 8px;">
        <i class="bi bi-download me-2"></i> {{ __('Download PDF') }}
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('reports.stock_movement') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label">{{ __('Search Product') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name, SKU, category or brand...') }}" value="{{ $search }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-funnel me-2"></i> {{ __('Filter') }}</button>
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
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start">{{ __('Product') }}</th>
                        <th class="border-0 fw-semibold">{{ __('Category') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Opening Stock') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Sold') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Damaged') }}</th>
                        <th class="border-0 fw-semibold text-end">{{ __('Remaining') }}</th>
                        <th class="border-0 fw-semibold text-center pe-4 rounded-end">{{ __('Movement') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-medium text-dark">{{ $row->product->name }}</div>
                                @if($row->product->model)
                                    <small class="text-muted">{{ $row->product->model }}</small>
                                @endif
                            </td>
                            <td>{{ $row->product->category->name ?? '-' }}</td>
                            <td class="text-end">{{ number_format($row->opening) }} {{ $row->product->unit->short_name ?? '' }}</td>
                            <td class="text-end fw-bold text-dark">{{ number_format($row->sold) }} {{ $row->product->unit->short_name ?? '' }}</td>
                            <td class="text-end">
                                @if(($row->damaged ?? 0) > 0)
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill fw-bold">
                                        {{ number_format($row->damaged) }} {{ $row->product->unit->short_name ?? '' }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($row->current) }} {{ $row->product->unit->short_name ?? '' }}</td>
                            <td class="text-center pe-4">
                                @if($row->sellThrough === null)
                                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">{{ __('No Activity') }}</span>
                                @elseif($row->sellThrough >= 60)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">{{ __('Fast Moving') }} ({{ $row->sellThrough }}%)</span>
                                @elseif($row->sellThrough >= 20)
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">{{ __('Moderate') }} ({{ $row->sellThrough }}%)</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">{{ __('Slow Moving') }} ({{ $row->sellThrough }}%)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 mb-3 d-block"></i>
                                {{ __('No products found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
