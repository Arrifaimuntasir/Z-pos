@extends('layouts.admin')
@section('title', __('Warehouses / Godowns'))
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
    <div>
        <h4 class="fw-bold mb-0">🏭 {{ __('Warehouses & Godowns') }}</h4>
        <span class="text-muted small">{{ __('Manage your storage locations and stock levels') }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.movements.index') }}" class="btn btn-light shadow-sm" style="border-radius:10px;">
            <i class="bi bi-arrow-left-right me-2"></i>{{ __('Movement History') }}
        </a>
        <a href="{{ route('stock.transfer.create') }}" class="btn btn-outline-primary shadow-sm" style="border-radius:10px;">
            <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Transfer Stock') }}
        </a>
        <a href="{{ route('warehouses.create') }}" class="btn btn-primary shadow-sm" style="border-radius:10px;">
            <i class="bi bi-plus-lg me-2"></i>{{ __('Add Godown') }}
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" style="border-radius:12px;"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" style="border-radius:12px;"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px;background:linear-gradient(135deg,#1e40af,#2563eb);color:#fff;">
            <div style="font-size:2rem;">🏭</div>
            <div style="font-size:1.8rem;font-weight:800;">{{ $warehouses->count() }}</div>
            <div style="font-size:0.82rem;opacity:0.85;">{{ __('Total Godowns') }}</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px;background:linear-gradient(135deg,#065f46,#059669);color:#fff;">
            <div style="font-size:2rem;">📦</div>
            <div style="font-size:1.8rem;font-weight:800;">{{ number_format($warehouses->sum('total_qty')) }}</div>
            <div style="font-size:0.82rem;opacity:0.85;">{{ __('Total Items in Godowns') }}</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:14px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;">
            <div style="font-size:2rem;">🔄</div>
            <div style="font-size:1.8rem;font-weight:800;">{{ $warehouses->where('is_active', true)->count() }}</div>
            <div style="font-size:0.82rem;opacity:0.85;">{{ __('Active Godowns') }}</div>
        </div>
    </div>
</div>

@if($warehouses->isEmpty())
<div class="card border-0 shadow-sm text-center p-5" style="border-radius:16px;">
    <div style="font-size:4rem;">🏭</div>
    <h5 class="fw-bold mt-3">{{ __('No Godowns Yet') }}</h5>
    <p class="text-muted">{{ __('Add your first warehouse or storage location.') }}</p>
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary px-5" style="border-radius:10px;">
        <i class="bi bi-plus-lg me-2"></i>{{ __('Add First Godown') }}
    </a>
</div>
@else
<div class="row g-3">
    @foreach($warehouses as $wh)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;border-left:4px solid {{ $wh->is_active ? '#3b82f6' : '#94a3b8' }} !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-bold mb-1">🏭 {{ $wh->name }}</h6>
                        @if($wh->location)
                        <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $wh->location }}</div>
                        @endif
                    </div>
                    <span class="badge {{ $wh->is_active ? 'bg-success' : 'bg-secondary' }}" style="border-radius:8px;">
                        {{ $wh->is_active ? __('Active') : __('Inactive') }}
                    </span>
                </div>

                @if($wh->description)
                <p class="text-muted small mb-3">{{ Str::limit($wh->description, 80) }}</p>
                @endif

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-2 text-center">
                            <div class="fw-bold text-primary" style="font-size:1.3rem;">{{ number_format($wh->total_qty) }}</div>
                            <div class="text-muted" style="font-size:0.72rem;">{{ __('Total Units') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-2 text-center">
                            <div class="fw-bold text-success" style="font-size:1.3rem;">{{ $wh->total_products }}</div>
                            <div class="text-muted" style="font-size:0.72rem;">{{ __('Products') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('warehouses.show', $wh) }}" class="btn btn-sm btn-primary flex-fill" style="border-radius:8px;">
                        <i class="bi bi-eye me-1"></i>{{ __('View Stock') }}
                    </a>
                    <a href="{{ route('warehouses.edit', $wh) }}" class="btn btn-sm btn-light" style="border-radius:8px;" title="{{ __('Edit') }}">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('warehouses.destroy', $wh) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light text-danger" style="border-radius:8px;"
                            onclick="return confirm('Futa godown hii?')" title="{{ __('Delete') }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
