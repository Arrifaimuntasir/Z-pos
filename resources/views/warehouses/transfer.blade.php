@extends('layouts.admin')
@section('title', __('Transfer Stock'))
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">🔄 {{ __('Transfer Stock') }}</h4>
        <span class="text-muted small">{{ __('Move stock between godown and shop') }}</span>
    </div>
    <a href="{{ route('warehouses.index') }}" class="btn btn-light shadow-sm" style="border-radius:10px;">
        <i class="bi bi-arrow-left me-2"></i>{{ __('Back') }}
    </a>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" style="border-radius:12px;">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body p-4">
                <form action="{{ route('stock.transfer.store') }}" method="POST" id="transferForm">
                    @csrf

                    {{-- Product --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">📦 {{ __('Product') }} <span class="text-danger">*</span></label>
                        <select name="product_id" id="productSelect" class="form-select" required onchange="loadStockInfo()">
                            <option value="">-- {{ __('Select Product') }} --</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ request('product') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} @if($p->sku)({{ $p->sku }})@endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Stock Info Display --}}
                    <div id="stockInfoBox" class="mb-4" style="display:none;">
                        <div class="alert alert-info border-0 mb-0" style="border-radius:12px;background:#eff6ff;">
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="fw-bold text-primary" style="font-size:1.4rem;" id="shopStockNum">-</div>
                                    <div class="text-muted small">🏪 {{ __('Shop Stock') }}</div>
                                </div>
                                <div class="col-6">
                                    <div id="warehouseStockList" class="text-start small"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FROM --}}
                    <div class="card border-0 bg-light mb-3" style="border-radius:12px;">
                        <div class="card-body p-3">
                            <label class="form-label fw-bold text-danger mb-2">📤 {{ __('FROM (Source)') }}</label>
                            <div class="mb-2">
                                <select name="from_type" id="fromType" class="form-select" onchange="toggleWarehouseSelect('from')" required>
                                    <option value="shop" {{ request('from_type') === 'shop' || !request('from_type') ? 'selected' : '' }}>🏪 {{ __('Shop (Duka)') }}</option>
                                    <option value="warehouse" {{ request('from_type') === 'warehouse' ? 'selected' : '' }}>🏭 {{ __('Godown / Warehouse') }}</option>
                                </select>
                            </div>
                            <div id="fromWarehouseDiv" style="{{ request('from_type') === 'warehouse' ? '' : 'display:none;' }}">
                                <select name="from_warehouse_id" id="fromWarehouseId" class="form-select">
                                    <option value="">-- {{ __('Select Godown') }} --</option>
                                    @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ request('from_warehouse') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Arrow --}}
                    <div class="text-center mb-3">
                        <span style="font-size:1.8rem;">⬇️</span>
                    </div>

                    {{-- TO --}}
                    <div class="card border-0 bg-light mb-4" style="border-radius:12px;">
                        <div class="card-body p-3">
                            <label class="form-label fw-bold text-success mb-2">📥 {{ __('TO (Destination)') }}</label>
                            <div class="mb-2">
                                <select name="to_type" id="toType" class="form-select" onchange="toggleWarehouseSelect('to')" required>
                                    <option value="shop" {{ request('to_type') !== 'warehouse' ? 'selected' : '' }}>🏪 {{ __('Shop (Duka)') }}</option>
                                    <option value="warehouse" {{ request('to_type') === 'warehouse' ? 'selected' : '' }}>🏭 {{ __('Godown / Warehouse') }}</option>
                                </select>
                            </div>
                            <div id="toWarehouseDiv" style="{{ request('to_type') === 'warehouse' ? '' : 'display:none;' }}">
                                <select name="to_warehouse_id" id="toWarehouseId" class="form-select">
                                    <option value="">-- {{ __('Select Godown') }} --</option>
                                    @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ request('to_warehouse') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">{{ __('Quantity') }} <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control form-control-lg fw-bold" min="0.01" step="0.01" required placeholder="0">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">{{ __('Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="movement_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="{{ __('Optional notes about this transfer...') }}"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" style="border-radius:12px;">
                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Confirm Transfer') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleWarehouseSelect(type) {
    const sel = document.getElementById(type + 'Type').value;
    document.getElementById(type + 'WarehouseDiv').style.display = sel === 'warehouse' ? '' : 'none';
}

function loadStockInfo() {
    const pid = document.getElementById('productSelect').value;
    if (!pid) { document.getElementById('stockInfoBox').style.display = 'none'; return; }

    fetch('/warehouses/product-stock?product_id=' + pid)
        .then(r => r.json())
        .then(data => {
            document.getElementById('shopStockNum').textContent = parseFloat(data.shop_stock || 0).toLocaleString();
            let wList = '<div class="fw-bold text-success mb-1">🏭 Godowns:</div>';
            if (data.warehouse_stocks && data.warehouse_stocks.length) {
                data.warehouse_stocks.forEach(ws => {
                    wList += `<div>${ws.warehouse_name}: <b>${parseFloat(ws.quantity).toLocaleString()}</b></div>`;
                });
            } else {
                wList += '<div class="text-muted">Hakuna stock kwenye godown</div>';
            }
            document.getElementById('warehouseStockList').innerHTML = wList;
            document.getElementById('stockInfoBox').style.display = '';
        });
}

// Load on page load if product preselected
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('productSelect').value) loadStockInfo();
});
</script>
@endpush
@endsection
