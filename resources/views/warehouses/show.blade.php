@extends('layouts.admin')
@section('title', $warehouse->name)

@push('styles')
    @include('warehouses._styles')
@endpush

@section('content')
<div class="gd">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('warehouses.index') }}" class="gd-iconbtn" title="{{ __('Back') }}"><i class="bi bi-arrow-left"></i></a>
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h2 class="gd-title">{{ $warehouse->name }}</h2>
                    <span class="gd-pill {{ $warehouse->is_active ? 'gd-pill-green' : 'gd-pill-slate' }}">
                        <span class="gd-dot"></span>{{ $warehouse->is_active ? __('Active') : __('Inactive') }}
                    </span>
                </div>
                <p class="gd-sub">
                    <i class="bi bi-geo-alt me-1"></i>{{ $warehouse->location ?: __('No location set') }}
                </p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <a href="{{ route('stock.transfer.create') }}?to_type=warehouse&to_warehouse={{ $warehouse->id }}" class="gd-btn gd-btn-ghost">
                <i class="bi bi-arrow-left-right"></i> {{ __('Transfer Stock') }}
            </a>
            <button type="button" class="gd-btn gd-btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#addStockModal">
                <i class="bi bi-plus-lg"></i> {{ __('Add Stock') }}
            </button>
            <a href="{{ route('products.create', ['warehouse_id' => $warehouse->id]) }}" class="gd-btn gd-btn-outline" title="{{ __('Create new product directly into this warehouse') }}">
                <i class="bi bi-box-seam"></i> {{ __('Register Product') }}
            </a>
            <a href="{{ route('warehouses.edit', $warehouse) }}" class="gd-iconbtn" title="{{ __('Edit Warehouse') }}">
                <i class="bi bi-pencil"></i>
            </a>
        </div>
    </div>

    @include('warehouses._nav')

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="gd-kpi-card gd-kpi-blue h-100">
                <div class="gd-kpi-icon gd-ic-blue"><i class="bi bi-boxes"></i></div>
                <div>
                    <div class="gd-kpi-label">{{ __('Total Units in Warehouse') }}</div>
                    <div class="gd-kpi-value">{{ number_format($stats['units']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gd-kpi-card gd-kpi-green h-100">
                <div class="gd-kpi-icon gd-ic-green"><i class="bi bi-tag"></i></div>
                <div>
                    <div class="gd-kpi-label">{{ __('Product Types') }}</div>
                    <div class="gd-kpi-value">{{ number_format($stats['products']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gd-kpi-card {{ $stats['low'] > 0 ? 'gd-kpi-amber' : 'gd-kpi-violet' }} h-100">
                <div class="gd-kpi-icon {{ $stats['low'] > 0 ? 'gd-ic-amber' : 'gd-ic-violet' }}"><i class="bi {{ $stats['low'] > 0 ? 'bi-exclamation-triangle' : 'bi-shield-check' }}"></i></div>
                <div>
                    <div class="gd-kpi-label">{{ __('Low Stock Products') }}</div>
                    <div class="gd-kpi-value" style="{{ $stats['low'] > 0 ? 'color:#b45309;' : '' }}">{{ number_format($stats['low']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="gd-card overflow-hidden">
        <div class="gd-card-head d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h5 class="mb-0">{{ __('Products in Warehouse') }}</h5>
                <span class="badge bg-light text-muted border px-2 py-1" style="font-size:12px;">{{ $stocks->total() }} {{ __('items') }}</span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="gd-search-wrap">
                    <i class="bi bi-search gd-search-icon"></i>
                    <input type="text" id="warehouseProductSearch" class="gd-search-input" placeholder="{{ __('Search products...') }}">
                </div>
                <button type="button" class="gd-btn gd-btn-dark gd-btn-sm" data-bs-toggle="modal" data-bs-target="#addStockModal">
                    <i class="bi bi-plus-lg"></i> {{ __('Add Stock') }}
                </button>
            </div>
        </div>

        @if($stocks->isEmpty())
            <div class="gd-empty">
                <div class="gd-empty-icon"><i class="bi bi-box"></i></div>
                <h5 class="fw-bold mb-1 gd-stat-num">{{ __('No stock in this warehouse yet.') }}</h5>
                <p class="text-muted mb-4">{{ __('Add products directly to this warehouse or transfer them from the shop.') }}</p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <button type="button" class="gd-btn gd-btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#addStockModal">
                        <i class="bi bi-plus-lg"></i> {{ __('Add Product / Stock') }}
                    </button>
                    <a href="{{ route('products.create', ['warehouse_id' => $warehouse->id]) }}" class="gd-btn gd-btn-outline shadow-sm">
                        <i class="bi bi-box-seam"></i> {{ __('Register New Product to Warehouse') }}
                    </a>
                    <a href="{{ route('stock.transfer.create') }}?to_type=warehouse&to_warehouse={{ $warehouse->id }}" class="gd-btn gd-btn-ghost">
                        <i class="bi bi-arrow-left-right"></i> {{ __('Transfer from Shop') }}
                    </a>
                </div>
            </div>
        @else
            <div class="table-responsive">
                <table class="gd-table">
                    <thead>
                        <tr>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Unit') }}</th>
                            <th class="text-center">{{ __('Warehouse Stock') }}</th>
                            <th class="text-center">{{ __('Shop Stock') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="warehouseStockTableBody">
                        @foreach($stocks as $ws)
                            @php
                                $q = (float) $ws->quantity;
                                $cls = $q <= 0 ? 'gd-pill-red' : ($q <= 5 ? 'gd-pill-amber' : 'gd-pill-green');
                            @endphp
                            <tr class="stock-row">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="gd-kpi-icon gd-ic-slate" style="width:36px;height:36px;font-size:15px;border-radius:9px;"><i class="bi bi-box-seam"></i></div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $ws->product->name ?? '-' }}</div>
                                            @if($ws->product && $ws->product->sku)
                                                <small class="text-muted font-monospace" style="font-size:11px;">SKU: {{ $ws->product->sku }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $ws->product->category->name ?? '-' }}</td>
                                <td class="text-muted">{{ $ws->product->unit->name ?? 'Pcs' }}</td>
                                <td class="text-center"><span class="gd-pill {{ $cls }}">{{ number_format($q, $q == floor($q) ? 0 : 2) }}</span></td>
                                <td class="text-center"><span class="gd-pill gd-pill-slate">{{ number_format($ws->product->stock ?? 0) }}</span></td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <button type="button" class="gd-btn gd-btn-ghost gd-btn-sm" onclick="openAddStockModalFor('{{ $ws->product_id }}')" title="{{ __('Add more stock') }}">
                                            <i class="bi bi-plus-lg text-primary"></i> {{ __('Add More') }}
                                        </button>
                                        <a href="{{ route('stock.transfer.create') }}?product={{ $ws->product_id }}&from_type=warehouse&from_warehouse={{ $warehouse->id }}&to_type=shop"
                                           class="gd-btn gd-btn-ghost gd-btn-sm" title="{{ __('Move to Shop') }}">
                                            <i class="bi bi-shop text-success"></i> {{ __('To Shop') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($stocks->hasPages())
                <div class="p-3 px-4" style="border-top:1px solid #e2e8f0;">{{ $stocks->links() }}</div>
            @endif
        @endif
    </div>
</div>

{{-- MODAL: ADD PRODUCT / STOCK DIRECTLY TO WAREHOUSE --}}
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="addStockModalLabel">
                    <i class="bi bi-box-arrow-in-down text-primary me-2"></i>{{ __('Add Stock to Warehouse') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('warehouses.add-stock', $warehouse) }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <div class="p-3 mb-3 rounded-3 gd-stat-box">
                        <div class="small text-muted">{{ __('Destination Warehouse') }}:</div>
                        <strong class="text-dark fs-6"><i class="bi bi-buildings me-1 text-primary"></i>{{ $warehouse->name }}</strong>
                    </div>

                    <div class="mb-3">
                        <label class="gd-label">{{ __('Product') }} <span class="text-danger">*</span></label>
                        <select name="product_id" id="modalProductSelect" class="form-select gd-input" required>
                            <option value="">-- {{ __('Select Product') }} --</option>
                            @foreach($allProducts as $p)
                                <option value="{{ $p->id }}" data-name="{{ $p->name }}">
                                    {{ $p->name }} @if($p->sku) ({{ $p->sku }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted" style="font-size:11.5px;">{{ __('Can’t find the product?') }}</small>
                            <a href="{{ route('products.create', ['warehouse_id' => $warehouse->id]) }}" class="small fw-semibold text-decoration-none text-primary">
                                <i class="bi bi-plus-circle me-1"></i>{{ __('Register New Product') }}
                            </a>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="gd-label">{{ __('Quantity') }} <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control gd-input fw-bold" min="0.01" step="0.01" placeholder="0" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="gd-label">{{ __('Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="movement_date" class="form-control gd-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="gd-label">{{ __('Notes / Source') }}</label>
                        <input type="text" name="notes" class="form-control gd-input" placeholder="{{ __('e.g. Received from supplier, direct purchase...') }}">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="gd-btn gd-btn-ghost gd-btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="gd-btn gd-btn-dark gd-btn-sm px-4">
                        <i class="bi bi-check2-circle me-1"></i>{{ __('Save to Warehouse') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openAddStockModalFor(productId) {
    const sel = document.getElementById('modalProductSelect');
    if (sel && productId) {
        sel.value = productId;
    }
    const modalEl = document.getElementById('addStockModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Open modal if hash is #add-stock
    if (window.location.hash === '#add-stock') {
        const modalEl = document.getElementById('addStockModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    // Real-time table search filter
    const searchInput = document.getElementById('warehouseProductSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#warehouseStockTableBody tr.stock-row');
            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(query) !== -1 ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
@endsection
