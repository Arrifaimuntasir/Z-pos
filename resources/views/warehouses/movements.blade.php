@extends('layouts.admin')
@section('title', __('Movement History'))

@push('styles')
    @include('warehouses._styles')
    <style>
        .filter-card {
            background: #ffffff;
            border: 1px solid var(--gd-line);
            border-radius: 14px;
            padding: 18px 20px;
        }
        [data-theme="dark"] .filter-card,
        body.dark-mode .filter-card {
            background: #111827 !important;
            border-color: #1f2937 !important;
        }
        .movement-route {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
    </style>
@endpush

@section('content')
@php
    $place = function ($type, $wh, $branch = null) {
        if ($type === 'supplier') {
            return [
                'icon' => 'bi-truck',
                'cls'  => 'gd-pill-teal',
                'name' => __('Supplier / Upokeaji')
            ];
        }
        if ($branch) {
            return [
                'icon' => 'bi-shop',
                'cls'  => 'gd-pill-blue',
                'name' => $branch->name . ' (' . __('Duka') . ')'
            ];
        }
        if ($type === 'shop' || $type === 'branch') {
            return [
                'icon' => 'bi-shop',
                'cls'  => 'gd-pill-blue',
                'name' => __('Duka Kuu (Shop)')
            ];
        }
        return [
            'icon' => 'bi-buildings',
            'cls'  => 'gd-pill-amber',
            'name' => ($wh->name ?? __('Warehouse')) . ' (' . __('Ghala') . ')'
        ];
    };
@endphp
<div class="gd">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="gd-title">{{ __('Movement History') }}</h2>
            <p class="gd-sub">{{ __('Complete audit trail of all inventory transfers between warehouses, shop branches, and intake') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stock.transfer.create') }}" class="gd-btn gd-btn-dark">
                <i class="bi bi-arrow-left-right"></i> {{ __('New Transfer') }}
            </a>
        </div>
    </div>

    @include('warehouses._nav')

    {{-- Filter Panel --}}
    <div class="filter-card mb-4 shadow-sm">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="gd-label small mb-1">{{ __('Filter by Product') }}</label>
                <select name="product_id" class="form-select" style="border-radius:9px;">
                    <option value="">{{ __('All Products') }}</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} @if($p->sku) ({{ $p->sku }}) @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="gd-label small mb-1">{{ __('Warehouse') }}</label>
                <select name="warehouse_id" class="form-select" style="border-radius:9px;">
                    <option value="">{{ __('All Warehouses') }}</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="gd-label small mb-1">{{ __('Shop / Branch') }}</label>
                <select name="branch_id" class="form-select" style="border-radius:9px;">
                    <option value="">{{ __('All Branches') }}</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="gd-label small mb-1">{{ __('Date From') }}</label>
                <input type="date" name="date_from" class="form-control" style="border-radius:9px;" value="{{ request('date_from') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="gd-label small mb-1">{{ __('Date To') }}</label>
                <input type="date" name="date_to" class="form-control" style="border-radius:9px;" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="gd-btn gd-btn-dark flex-fill justify-content-center" style="border-radius:9px;" title="{{ __('Filter') }}">
                    <i class="bi bi-funnel"></i>
                </button>
                @if(request()->hasAny(['product_id', 'warehouse_id', 'branch_id', 'date_from', 'date_to']))
                    <a href="{{ route('stock.movements.index') }}" class="gd-btn gd-btn-ghost" style="border-radius:9px;" title="{{ __('Reset Filter') }}">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Movements Table Card --}}
    <div class="gd-card overflow-hidden shadow-sm">
        <div class="gd-card-head">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary"></i> {{ __('Stock Movement Logs') }}
            </h5>
            <span class="gd-pill gd-pill-slate">
                {{ method_exists($movements, 'total') ? $movements->total() : $movements->count() }} {{ __('record(s)') }}
            </span>
        </div>

        @if($movements->isEmpty())
            <div class="gd-empty">
                <div class="gd-empty-icon"><i class="bi bi-arrow-left-right"></i></div>
                <h5 class="fw-bold mb-1" style="color:var(--gd-ink);">{{ __('No stock movements found') }}</h5>
                <p class="text-muted mb-3">{{ __('Transfers and intakes will appear here automatically with complete audit logs.') }}</p>
                <a href="{{ route('stock.transfer.create') }}" class="gd-btn gd-btn-dark">
                    <i class="bi bi-arrow-left-right"></i> {{ __('Create First Transfer') }}
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="gd-table">
                    <thead>
                        <tr>
                            <th style="width:130px;">{{ __('Date') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Movement Route') }}</th>
                            <th class="text-center" style="width:110px;">{{ __('Quantity') }}</th>
                            <th>{{ __('Moved By') }}</th>
                            <th>{{ __('Notes / Reference') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $mv)
                            @php
                                $from = $place($mv->from_type, $mv->fromWarehouse, $mv->fromBranch);
                                $to   = $place($mv->to_type, $mv->toWarehouse, $mv->toBranch);
                            @endphp
                            <tr>
                                <td class="text-nowrap">
                                    <div class="fw-semibold text-dark">{{ $mv->movement_date ? $mv->movement_date->format('d M Y') : '—' }}</div>
                                    <div class="text-muted" style="font-size:11.5px;">{{ $mv->created_at ? $mv->created_at->format('H:i') : '' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $mv->product->name ?? '—' }}</div>
                                    @if(optional($mv->product)->sku)
                                        <div class="text-muted" style="font-size:11.5px;">SKU: {{ $mv->product->sku }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="movement-route">
                                        <span class="gd-pill {{ $from['cls'] }}">
                                            <i class="bi {{ $from['icon'] }}"></i> {{ $from['name'] }}
                                        </span>
                                        <i class="bi bi-arrow-right text-muted" style="font-size:12px;"></i>
                                        <span class="gd-pill {{ $to['cls'] }}">
                                            <i class="bi {{ $to['icon'] }}"></i> {{ $to['name'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="gd-pill gd-pill-slate fw-bold" style="font-size:13px; color:var(--gd-ink);">
                                        {{ number_format($mv->quantity, $mv->quantity == floor($mv->quantity) ? 0 : 2) }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <span class="d-inline-flex align-items-center gap-1.5 text-secondary">
                                        <i class="bi bi-person-circle"></i> {{ $mv->movedBy->name ?? 'System' }}
                                    </span>
                                </td>
                                <td style="max-width:250px;">
                                    @if($mv->notes)
                                        <span class="text-muted small" title="{{ $mv->notes }}">{{ Str::limit($mv->notes, 60) }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($movements, 'hasPages') && $movements->hasPages())
                <div class="p-3 px-4 border-top">
                    {{ $movements->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
