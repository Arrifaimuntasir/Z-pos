@extends('layouts.admin')
@section('title', 'Godown: ' . $warehouse->name)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
    <div>
        <h4 class="fw-bold mb-0">🏭 {{ $warehouse->name }}</h4>
        @if($warehouse->location)<span class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $warehouse->location }}</span>@endif
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.transfer.create') }}?warehouse={{ $warehouse->id }}" class="btn btn-primary shadow-sm" style="border-radius:10px;">
            <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Transfer Stock') }}
        </a>
        <a href="{{ route('warehouses.index') }}" class="btn btn-light shadow-sm" style="border-radius:10px;">
            <i class="bi bi-arrow-left me-2"></i>{{ __('Back') }}
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:14px;">
            <div class="fw-bold text-primary" style="font-size:1.8rem;">{{ number_format($stocks->sum('quantity')) }}</div>
            <div class="text-muted small">{{ __('Total Units in Godown') }}</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:14px;">
            <div class="fw-bold text-success" style="font-size:1.8rem;">{{ $stocks->total() }}</div>
            <div class="text-muted small">{{ __('Product Types') }}</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:14px;">
            <div class="fw-bold" style="font-size:1.8rem;color:#f59e0b;">{{ $stocks->where('quantity', '<=', 5)->count() }}</div>
            <div class="text-muted small">{{ __('Low Stock Products') }}</div>
        </div>
    </div>
</div>

{{-- Stock Table --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-header bg-white border-0 p-4">
        <h6 class="fw-bold mb-0">📦 {{ __('Products in Godown') }}</h6>
    </div>
    <div class="card-body p-0">
        @if($stocks->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-box fs-1 d-block mb-2"></i>
            <p>{{ __('No stock in this godown yet.') }}</p>
            <a href="{{ route('stock.transfer.create') }}?to_warehouse={{ $warehouse->id }}"
               class="btn btn-primary btn-sm" style="border-radius:8px;">
                <i class="bi bi-plus me-1"></i>{{ __('Add Stock') }}
            </a>
        </div>
        @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 border-0">{{ __('Product') }}</th>
                        <th class="border-0">{{ __('Category') }}</th>
                        <th class="border-0">{{ __('Unit') }}</th>
                        <th class="border-0 text-center">{{ __('Godown Stock') }}</th>
                        <th class="border-0 text-center">{{ __('Shop Stock') }}</th>
                        <th class="border-0 text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $ws)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $ws->product->name }}</td>
                        <td class="text-muted small">{{ $ws->product->category->name ?? '-' }}</td>
                        <td class="text-muted small">{{ $ws->product->unit->name ?? 'Pcs' }}</td>
                        <td class="text-center">
                            <span class="badge {{ $ws->quantity <= 0 ? 'bg-danger' : ($ws->quantity <= 5 ? 'bg-warning text-dark' : 'bg-success') }}"
                                  style="border-radius:8px;font-size:0.85rem;padding:6px 12px;">
                                {{ number_format($ws->quantity) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border" style="border-radius:8px;font-size:0.85rem;padding:6px 12px;">
                                {{ number_format($ws->product->stock ?? 0) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('stock.transfer.create') }}?product={{ $ws->product_id }}&from_type=warehouse&from_warehouse={{ $warehouse->id }}"
                               class="btn btn-sm btn-outline-primary" style="border-radius:8px;" title="{{ __('Transfer to Shop') }}">
                                <i class="bi bi-shop me-1"></i>{{ __('To Shop') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $stocks->links() }}</div>
        @endif
    </div>
</div>
@endsection
