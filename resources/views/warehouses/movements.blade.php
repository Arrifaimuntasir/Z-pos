@extends('layouts.admin')
@section('title', __('Stock Movement History'))
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
    <div>
        <h4 class="fw-bold mb-0">🔄 {{ __('Stock Movement History') }}</h4>
        <span class="text-muted small">{{ __('All transfers between godowns and shop') }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.transfer.create') }}" class="btn btn-primary shadow-sm" style="border-radius:10px;">
            <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('New Transfer') }}
        </a>
        <a href="{{ route('warehouses.index') }}" class="btn btn-light shadow-sm" style="border-radius:10px;">
            <i class="bi bi-building me-2"></i>{{ __('Godowns') }}
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <form method="GET" class="search-toolbar">
            <select name="product_id" class="form-select">
                <option value="">{{ __('All Products') }}</option>
                @foreach($products as $p)
                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="warehouse_id" class="form-select">
                <option value="">{{ __('All Godowns') }}</option>
                @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            <a href="{{ route('stock.movements.index') }}" class="btn btn-light">{{ __('Reset') }}</a>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-body p-0">
        @if($movements->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-arrow-left-right fs-1 d-block mb-2"></i>
            <p>{{ __('No stock movements found.') }}</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 border-0">{{ __('Date') }}</th>
                        <th class="border-0">{{ __('Product') }}</th>
                        <th class="border-0">{{ __('From') }}</th>
                        <th class="border-0 text-center">→</th>
                        <th class="border-0">{{ __('To') }}</th>
                        <th class="border-0 text-center">{{ __('Qty') }}</th>
                        <th class="border-0">{{ __('By') }}</th>
                        <th class="border-0">{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $mv)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $mv->movement_date->format('d M Y') }}</td>
                        <td class="fw-semibold">{{ $mv->product->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $mv->from_type === 'shop' ? 'bg-info text-dark' : 'bg-warning text-dark' }}" style="border-radius:8px;">
                                {{ $mv->from_label }}
                            </span>
                        </td>
                        <td class="text-center text-muted">➡️</td>
                        <td>
                            <span class="badge {{ $mv->to_type === 'shop' ? 'bg-info text-dark' : 'bg-success' }}" style="border-radius:8px;">
                                {{ $mv->to_label }}
                            </span>
                        </td>
                        <td class="text-center fw-bold text-primary">{{ number_format($mv->quantity) }}</td>
                        <td class="text-muted small">{{ $mv->movedBy->name ?? '-' }}</td>
                        <td class="text-muted small">{{ Str::limit($mv->notes, 40) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection
