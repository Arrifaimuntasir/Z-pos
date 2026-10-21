@extends('layouts.admin')
@section('title', __('Warehouses'))

@push('styles')
    @include('warehouses._styles')
@endpush

@section('content')
@php
    $grandTotal = max(1, $warehouses->sum('total_qty'));
@endphp
<div class="gd">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="gd-title">{{ __('Warehouses') }}</h2>
            <p class="gd-sub">{{ __('Manage storage facilities and stock across warehouses') }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('stock.transfer.create') }}" class="gd-btn gd-btn-ghost">
                <i class="bi bi-arrow-left-right"></i> {{ __('Transfer Stock') }}
            </a>
            <a href="{{ route('warehouses.create') }}" class="gd-btn gd-btn-dark">
                <i class="bi bi-plus-lg"></i> {{ __('Add Warehouse') }}
            </a>
        </div>
    </div>

    @include('warehouses._nav')

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="gd-kpi-card gd-kpi-blue h-100">
                <div class="gd-kpi-icon gd-ic-blue"><i class="bi bi-buildings"></i></div>
                <div>
                    <div class="gd-kpi-label">{{ __('Total Warehouses') }}</div>
                    <div class="gd-kpi-value">{{ $warehouses->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gd-kpi-card gd-kpi-green h-100">
                <div class="gd-kpi-icon gd-ic-green"><i class="bi bi-boxes"></i></div>
                <div>
                    <div class="gd-kpi-label">{{ __('Total Items in Warehouses') }}</div>
                    <div class="gd-kpi-value">{{ number_format($warehouses->sum('total_qty')) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gd-kpi-card gd-kpi-violet h-100">
                <div class="gd-kpi-icon gd-ic-violet"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div class="gd-kpi-label">{{ __('Active Warehouses') }}</div>
                    <div class="gd-kpi-value">{{ $warehouses->where('is_active', true)->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($warehouses->isEmpty())
        <div class="gd-card gd-empty">
            <div class="gd-empty-icon"><i class="bi bi-buildings"></i></div>
            <h5 class="fw-bold mb-1" style="color:#0f172a;">{{ __('No Warehouses Yet') }}</h5>
            <p class="text-muted mb-4">{{ __('Add your first warehouse or storage location.') }}</p>
            <a href="{{ route('warehouses.create') }}" class="gd-btn gd-btn-dark">
                <i class="bi bi-plus-lg"></i> {{ __('Add First Warehouse') }}
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($warehouses as $wh)
                @php $share = round(($wh->total_qty / $grandTotal) * 100); @endphp
                <div class="col-md-6 col-xl-4">
                    <div class="gd-card gd-card-hover h-100 d-flex flex-column">
                        <div class="p-4 pb-3">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div class="d-flex align-items-center gap-3 min-w-0">
                                    <div class="gd-kpi-icon {{ $wh->is_active ? 'gd-ic-blue' : 'gd-ic-slate' }}" style="width:46px;height:46px;font-size:20px;">
                                        <i class="bi bi-buildings"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h6 class="fw-bold mb-0 text-truncate" style="color:#0f172a;font-size:16px;">{{ $wh->name }}</h6>
                                        <div class="text-muted text-truncate" style="font-size:13px;">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $wh->location ?: __('No location set') }}
                                        </div>
                                    </div>
                                </div>
                                <span class="gd-pill {{ $wh->is_active ? 'gd-pill-green' : 'gd-pill-slate' }}">
                                    <span class="gd-dot"></span>{{ $wh->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </div>

                            @if($wh->description)
                                <p class="text-muted mt-3 mb-0" style="font-size:13px;">{{ Str::limit($wh->description, 90) }}</p>
                            @endif
                        </div>

                        <div class="px-4 pb-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="rounded-3 p-3 gd-stat-box">
                                        <div class="fw-bold gd-stat-num" style="font-size:20px;">{{ number_format($wh->total_qty) }}</div>
                                        <div class="text-muted" style="font-size:11.5px;font-weight:600;text-transform:uppercase;">{{ __('Total Units') }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-3 gd-stat-box">
                                        <div class="fw-bold gd-stat-num" style="font-size:20px;">{{ $wh->total_products }}</div>
                                        <div class="text-muted" style="font-size:11.5px;font-weight:600;text-transform:uppercase;">{{ __('Products') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-1" style="font-size:12px;">
                                    <span class="text-muted">{{ __('Share of all warehouse stock') }}</span>
                                    <span class="fw-semibold gd-stat-num">{{ $share }}%</span>
                                </div>
                                <div class="progress" style="height:6px;border-radius:99px;">
                                    <div class="progress-bar" role="progressbar" style="width:{{ $share }}%;border-radius:99px;background:linear-gradient(90deg,#2563eb,#60a5fa);"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto p-3 px-4 d-flex align-items-center gap-2 gd-card-foot" style="border-radius:0 0 16px 16px;">
                            <a href="{{ route('warehouses.show', $wh) }}" class="gd-btn gd-btn-dark gd-btn-sm flex-fill justify-content-center">
                                <i class="bi bi-eye"></i> {{ __('View Stock') }}
                            </a>
                            <a href="{{ route('warehouses.show', $wh) }}#add-stock" class="gd-btn gd-btn-outline gd-btn-sm" title="{{ __('Add Product / Stock') }}">
                                <i class="bi bi-plus-lg"></i> {{ __('Add Stock') }}
                            </a>
                            <a href="{{ route('warehouses.edit', $wh) }}" class="gd-iconbtn" title="{{ __('Edit') }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('warehouses.destroy', $wh) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="gd-iconbtn danger" title="{{ __('Delete') }}"
                                    onclick="return confirm('{{ __('Delete this warehouse?') }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
