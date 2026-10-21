@extends('layouts.admin')
@section('title', __('Transfer Stock'))

@push('styles')
    @include('warehouses._styles')
    <style>
        .gd-transfer-route {
            position: relative;
        }
        .gd-endpoint {
            border: 1px solid var(--gd-line);
            border-radius: 16px;
            padding: 20px;
            background: #ffffff;
            height: 100%;
            transition: all 0.2s ease;
            position: relative;
        }
        .gd-endpoint.from {
            border-color: #fecdd3;
            background: linear-gradient(180deg, #fff5f5 0%, #ffffff 48px);
        }
        .gd-endpoint.to {
            border-color: #bbf7d0;
            background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 48px);
        }
        .gd-seg {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            padding: 4px;
            background: #f1f5f9;
            border-radius: 12px;
            margin: 12px 0 14px;
        }
        .gd-seg input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .gd-seg label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            color: var(--gd-muted);
            cursor: pointer;
            transition: all 0.18s ease;
            margin: 0;
            user-select: none;
        }
        .gd-seg input:checked + label {
            background: #ffffff;
            color: var(--gd-ink);
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.12);
        }
        .gd-swap {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: var(--gd-ink);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
            transition: all 0.25s ease;
        }
        .gd-swap:hover {
            background: var(--gd-ink);
            color: #ffffff;
            border-color: var(--gd-ink);
            transform: scale(1.08) rotate(180deg);
        }
        .gd-side-sticky {
            position: sticky;
            top: 90px;
        }
        .transfer-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .transfer-table th {
            background: #f8fafc;
            color: var(--gd-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 14px;
            border-bottom: 1px solid var(--gd-line);
        }
        .transfer-table td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .transfer-table tr:last-child td {
            border-bottom: 0;
        }
        .btn-add-item {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            color: var(--gd-text);
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 18px;
            transition: all 0.2s ease;
        }
        .btn-add-item:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            color: var(--gd-ink);
        }
        .route-summary-box {
            background: #f8fafc;
            border: 1px solid var(--gd-line);
            border-radius: 12px;
            padding: 14px;
        }
        .route-step {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            padding: 4px 0;
        }

        [data-theme="dark"] .gd-endpoint,
        body.dark-mode .gd-endpoint {
            background: #111827 !important;
            border-color: #1f2937 !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .gd-endpoint.from,
        body.dark-mode .gd-endpoint.from {
            border-color: rgba(220, 38, 38, 0.4) !important;
            background: linear-gradient(180deg, rgba(220, 38, 38, 0.15) 0%, #111827 48px) !important;
        }
        [data-theme="dark"] .gd-endpoint.to,
        body.dark-mode .gd-endpoint.to {
            border-color: rgba(22, 163, 74, 0.4) !important;
            background: linear-gradient(180deg, rgba(22, 163, 74, 0.15) 0%, #111827 48px) !important;
        }
        [data-theme="dark"] .gd-seg,
        body.dark-mode .gd-seg {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
        }
        [data-theme="dark"] .gd-seg label,
        body.dark-mode .gd-seg label {
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .gd-seg input:checked + label,
        body.dark-mode .gd-seg input:checked + label {
            background: #2563eb !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .gd-swap,
        body.dark-mode .gd-swap {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .transfer-table th,
        body.dark-mode .transfer-table th {
            background: #0f172a !important;
            color: #cbd5e1 !important;
            border-bottom-color: #1f2937 !important;
        }
        [data-theme="dark"] .transfer-table td,
        body.dark-mode .transfer-table td {
            border-bottom-color: #1f2937 !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .btn-add-item,
        body.dark-mode .btn-add-item {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .route-summary-box,
        body.dark-mode .route-summary-box {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
    </style>
@endpush

@section('content')
@php
    $fromType   = old('from_type', request('from_type', 'warehouse'));
    $toType     = old('to_type', request('to_type', $fromType === 'warehouse' ? 'shop' : 'warehouse'));
    $fromWh     = old('from_warehouse_id', request('from_warehouse'));
    $toWh       = old('to_warehouse_id', request('to_warehouse'));
    $fromBranch = old('from_branch_id', request('from_branch', $activeBranchId));
    $toBranch   = old('to_branch_id', request('to_branch', $activeBranchId));
@endphp
<div class="gd">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="gd-title">{{ __('Transfer Stock') }}</h2>
            <p class="gd-sub">{{ __('Move stock safely between warehouses and shop branches') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stock.movements.index') }}" class="gd-btn gd-btn-ghost">
                <i class="bi bi-clock-history"></i> {{ __('View Movements') }}
            </a>
        </div>
    </div>

    @include('warehouses._nav')

    @if($warehouses->isEmpty())
        <div class="gd-card gd-empty">
            <div class="gd-empty-icon"><i class="bi bi-buildings"></i></div>
            <h5 class="fw-bold mb-1" style="color:var(--gd-ink);">{{ __('No Warehouses Found') }}</h5>
            <p class="text-muted mb-4">{{ __('Add your first warehouse to begin transferring stock.') }}</p>
            <a href="{{ route('warehouses.create') }}" class="gd-btn gd-btn-dark">
                <i class="bi bi-plus-lg"></i> {{ __('Add Warehouse') }}
            </a>
        </div>
    @else
    <form action="{{ route('stock.transfer.store') }}" method="POST" id="transferForm">
        @csrf
        <div class="row g-4">

            {{-- ===== Left Column: Transfer Form ===== --}}
            <div class="col-lg-8">
                <div class="gd-card p-4 mb-4">

                    {{-- FROM & TO SELECTORS --}}
                    <div class="row g-3 align-items-stretch position-relative mb-4 gd-transfer-route">
                        {{-- FROM (SOURCE) --}}
                        <div class="col-md-6">
                            <div class="gd-endpoint from">
                                <span class="gd-pill gd-pill-red mb-1">
                                    <i class="bi bi-box-arrow-up"></i> {{ __('FROM (Source)') }}
                                </span>
                                
                                <div class="gd-seg">
                                    <input type="radio" name="from_type" id="fromWh" value="warehouse" {{ $fromType === 'warehouse' ? 'checked' : '' }}>
                                    <label for="fromWh"><i class="bi bi-buildings"></i> {{ __('Warehouse') }}</label>

                                    <input type="radio" name="from_type" id="fromShop" value="shop" {{ ($fromType === 'shop' || $fromType === 'branch') ? 'checked' : '' }}>
                                    <label for="fromShop"><i class="bi bi-shop"></i> {{ __('Shop / Branch') }}</label>
                                </div>

                                {{-- Warehouse Dropdown --}}
                                <div id="fromWarehouseDiv" style="{{ $fromType === 'warehouse' ? '' : 'display:none;' }}">
                                    <label class="gd-label small mb-1">{{ __('Select Source Warehouse') }}</label>
                                    <select name="from_warehouse_id" id="fromWarehouseId" class="form-select shadow-sm" style="border-radius:10px;">
                                        <option value="">-- {{ __('Choose Warehouse') }} --</option>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}" {{ $fromWh == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Branch Dropdown --}}
                                <div id="fromBranchDiv" style="{{ ($fromType === 'shop' || $fromType === 'branch') ? '' : 'display:none;' }}">
                                    <label class="gd-label small mb-1">{{ __('Select Source Shop / Branch') }}</label>
                                    <select name="from_branch_id" id="fromBranchId" class="form-select shadow-sm" style="border-radius:10px;">
                                        @foreach($branches as $b)
                                            <option value="{{ $b->id }}" {{ ($fromBranch == $b->id || $activeBranchId == $b->id) ? 'selected' : '' }}>
                                                {{ $b->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- TO (DESTINATION) --}}
                        <div class="col-md-6">
                            <div class="gd-endpoint to">
                                <span class="gd-pill gd-pill-green mb-1">
                                    <i class="bi bi-box-arrow-in-down"></i> {{ __('TO (Destination)') }}
                                </span>

                                <div class="gd-seg">
                                    <input type="radio" name="to_type" id="toShop" value="shop" {{ ($toType === 'shop' || $toType === 'branch') ? 'checked' : '' }}>
                                    <label for="toShop"><i class="bi bi-shop"></i> {{ __('Shop / Branch') }}</label>

                                    <input type="radio" name="to_type" id="toWh" value="warehouse" {{ $toType === 'warehouse' ? 'checked' : '' }}>
                                    <label for="toWh"><i class="bi bi-buildings"></i> {{ __('Warehouse') }}</label>
                                </div>

                                {{-- Branch Dropdown --}}
                                <div id="toBranchDiv" style="{{ ($toType === 'shop' || $toType === 'branch') ? '' : 'display:none;' }}">
                                    <label class="gd-label small mb-1">{{ __('Select Target Shop / Branch') }}</label>
                                    <select name="to_branch_id" id="toBranchId" class="form-select shadow-sm" style="border-radius:10px;">
                                        @foreach($branches as $b)
                                            <option value="{{ $b->id }}" {{ ($toBranch == $b->id || $activeBranchId == $b->id) ? 'selected' : '' }}>
                                                {{ $b->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Warehouse Dropdown --}}
                                <div id="toWarehouseDiv" style="{{ $toType === 'warehouse' ? '' : 'display:none;' }}">
                                    <label class="gd-label small mb-1">{{ __('Select Target Warehouse') }}</label>
                                    <select name="to_warehouse_id" id="toWarehouseId" class="form-select shadow-sm" style="border-radius:10px;">
                                        <option value="">-- {{ __('Choose Warehouse') }} --</option>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}" {{ $toWh == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Swap Button --}}
                        <div class="d-none d-md-flex position-absolute top-50 start-50 translate-middle" style="z-index: 3;">
                            <button type="button" class="gd-swap" id="swapBtn" title="{{ __('Swap Source and Destination') }}">
                                <i class="bi bi-arrow-left-right fs-6"></i>
                            </button>
                        </div>
                    </div>

                    {{-- DATE & NOTES --}}
                    <div class="row g-3 mb-4">
                        <div class="col-sm-5">
                            <label class="gd-label" for="dateInput">{{ __('Transfer Date') }} <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="date" id="dateInput" name="movement_date" class="form-control" style="border-radius:10px;" value="{{ old('movement_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <label class="gd-label" for="notesInput">{{ __('Transfer Notes / Reference') }}</label>
                            <input type="text" id="notesInput" name="notes" class="form-control" style="border-radius:10px;" placeholder="{{ __('e.g. Replenishment for morning sales, invoice ref...') }}" value="{{ old('notes') }}">
                        </div>
                    </div>

                    {{-- PRODUCTS TO TRANSFER TABLE --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-0" style="color:var(--gd-ink);">
                                    <i class="bi bi-boxes me-1.5 text-primary"></i> {{ __('Products to Transfer') }} <span class="text-danger">*</span>
                                </h6>
                                <div class="text-muted small">{{ __('Select products and specify units to move') }}</div>
                            </div>
                            <span class="gd-pill gd-pill-slate">
                                <span id="itemRowsCount">1</span> {{ __('item(s)') }}
                            </span>
                        </div>

                        <div class="border rounded-3 overflow-hidden mb-3">
                            <div class="table-responsive">
                                <table class="transfer-table mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 45px;">#</th>
                                            <th>{{ __('Product') }}</th>
                                            <th style="width: 185px;">{{ __('Available at Source') }}</th>
                                            <th style="width: 160px;">{{ __('Transfer Qty') }}</th>
                                            <th style="width: 45px;" class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTableBody">
                                        <tr class="transfer-row" data-index="0">
                                            <td class="row-num text-muted fw-bold">1</td>
                                            <td>
                                                <select name="items[0][product_id]" class="form-select prod-select" style="border-radius:8px;" required>
                                                    <option value="">-- {{ __('Select Product') }} --</option>
                                                    @foreach($products as $p)
                                                        <option value="{{ $p->id }}" data-sku="{{ $p->sku }}">
                                                            {{ $p->name }} @if($p->sku) ({{ $p->sku }}) @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <span class="avail-badge gd-pill gd-pill-slate">—</span>
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][quantity]" class="form-control qty-input fw-bold" style="border-radius:8px;" min="0.01" step="0.01" placeholder="0" required>
                                                <div class="row-err small text-danger mt-1" style="display:none;"></div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-link text-danger p-0 remove-row-btn" title="{{ __('Remove item') }}" style="display:none;">
                                                    <i class="bi bi-trash3 fs-6"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-1">
                            <button type="button" class="btn btn-add-item d-flex align-items-center gap-2" id="addRowBtn">
                                <i class="bi bi-plus-lg text-primary"></i>
                                <span>{{ __('Add Another Product') }}</span>
                            </button>

                            <div class="text-muted small">
                                {{ __('Total Units to Transfer') }}: <strong class="fs-6" style="color:var(--gd-ink);" id="totalUnitsDisplay">0</strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('warehouses.index') }}" class="gd-btn gd-btn-ghost justify-content-center">{{ __('Cancel') }}</a>
                        <button type="submit" class="gd-btn gd-btn-dark justify-content-center" id="submitBtn">
                            <i class="bi bi-arrow-left-right"></i> {{ __('Confirm Transfer') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===== Right Column: Summary Panel ===== --}}
            <div class="col-lg-4">
                <div class="gd-card gd-side-sticky">
                    <div class="gd-card-head">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-receipt text-primary"></i> {{ __('Transfer Summary') }}
                        </h5>
                    </div>
                    <div class="p-3">
                        {{-- Route Flow Box --}}
                        <div class="route-summary-box mb-3">
                            <div class="route-step mb-1">
                                <span class="text-muted"><i class="bi bi-box-arrow-up text-danger me-1"></i> {{ __('From') }}:</span>
                                <strong id="summaryFromText" class="text-danger">-</strong>
                            </div>
                            <div class="text-center my-1">
                                <i class="bi bi-arrow-down text-muted"></i>
                            </div>
                            <div class="route-step mb-2">
                                <span class="text-muted"><i class="bi bi-box-arrow-in-down text-success me-1"></i> {{ __('To') }}:</span>
                                <strong id="summaryToText" class="text-success">-</strong>
                            </div>
                            <hr class="my-2 border-secondary-subtle">
                            <div class="route-step">
                                <span class="text-muted">{{ __('Total Products') }}:</span>
                                <strong id="summaryProdCountText" style="color:var(--gd-ink);">0</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-uppercase small text-muted" style="letter-spacing:0.04em;">{{ __('Items Breakdown') }}</h6>
                        </div>

                        <div id="sideItemsList" class="small">
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-boxes fs-3 d-block mb-1 text-slate-300" style="opacity:0.4;"></i>
                                {{ __('Select products to preview transfer.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const $ = (id) => document.getElementById(id);
    if (!$('transferForm')) return;

    // Fast client-side stock mapping: productStocks[pid] = { id, name, sku, shop_stock, warehouses: { wh_id: qty }, branches: { branch_id: qty } }
    const productStocks = @json($productStockMap ?? []);
    const warehouses = @json($warehouses ?? []);
    const branches = @json($branches ?? []);

    const labels = {
        shop: @json(__('Shop')),
        warehouse: @json(__('Warehouse')),
        pick: @json(__('Select product')),
        pickSrc: @json(__('Select source')),
        avail: @json(__('Available')),
        over: @json(__('Insufficient stock')),
        sameSrcDst: @json(__('Source and destination cannot be the same.')),
        emptyProducts: @json(__('Please add at least one product with quantity.'))
    };

    const radio = (name) => {
        const el = document.querySelector('input[name="' + name + '"]:checked');
        return el ? el.value : 'warehouse';
    };
    const fmt = (n) => parseFloat(n || 0).toLocaleString();

    let rowCounter = 1;

    function getSourceStock(productId) {
        if (!productId || !productStocks[productId]) return null;
        const p = productStocks[productId];
        const fromType = radio('from_type');

        if (fromType === 'warehouse') {
            const whId = $('fromWarehouseId').value;
            if (!whId) return undefined;
            return parseFloat((p.warehouses && p.warehouses[whId]) || 0);
        } else {
            // Branch / Shop
            const bId = $('fromBranchId') ? $('fromBranchId').value : null;
            if (!bId) return undefined;
            if (p.branches && p.branches[bId] !== undefined) {
                return parseFloat(p.branches[bId]);
            }
            return parseFloat(p.shop_stock || 0);
        }
    }

    function syncVisibility() {
        const fromType = radio('from_type');
        const toType = radio('to_type');

        $('fromWarehouseDiv').style.display = fromType === 'warehouse' ? '' : 'none';
        $('fromBranchDiv').style.display    = fromType !== 'warehouse' ? '' : 'none';

        $('toWarehouseDiv').style.display   = toType === 'warehouse' ? '' : 'none';
        $('toBranchDiv').style.display      = toType !== 'warehouse' ? '' : 'none';

        updateAllRows();
        updateSummary();
    }

    function updateRowState(row) {
        const prodSelect = row.querySelector('.prod-select');
        const qtyInput = row.querySelector('.qty-input');
        const availBadge = row.querySelector('.avail-badge');
        const errDiv = row.querySelector('.row-err');

        const pid = prodSelect.value;
        const qty = parseFloat(qtyInput.value || 0);
        const avail = getSourceStock(pid);

        qtyInput.classList.remove('is-invalid');
        errDiv.style.display = 'none';

        if (avail === null) {
            availBadge.textContent = '—';
            availBadge.className = 'avail-badge gd-pill gd-pill-slate';
            qtyInput.removeAttribute('max');
            return true;
        }

        if (avail === undefined) {
            availBadge.textContent = labels.pickSrc;
            availBadge.className = 'avail-badge gd-pill gd-pill-amber';
            qtyInput.removeAttribute('max');
            return true;
        }

        availBadge.textContent = labels.avail + ': ' + fmt(avail);
        if (avail <= 0) {
            availBadge.className = 'avail-badge gd-pill gd-pill-red';
        } else {
            availBadge.className = 'avail-badge gd-pill gd-pill-green';
        }

        qtyInput.max = avail;

        if (qty > avail) {
            qtyInput.classList.add('is-invalid');
            errDiv.textContent = labels.over + ' (' + fmt(avail) + ')';
            errDiv.style.display = 'block';
            return false;
        }

        return true;
    }

    function updateAllRows() {
        let allValid = true;
        const rows = document.querySelectorAll('#itemsTableBody .transfer-row');
        rows.forEach((row, idx) => {
            row.querySelector('.row-num').textContent = idx + 1;
            const valid = updateRowState(row);
            if (!valid) allValid = false;
        });

        const removeBtns = document.querySelectorAll('.remove-row-btn');
        removeBtns.forEach(btn => {
            btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        });

        $('itemRowsCount').textContent = rows.length;
        updateSummary();
        return allValid;
    }

    function updateSummary() {
        const fromType = radio('from_type');
        const toType = radio('to_type');

        let fromName = labels.shop;
        if (fromType === 'warehouse') {
            const wh = warehouses.find(w => String(w.id) === String($('fromWarehouseId').value));
            fromName = wh ? wh.name : labels.warehouse;
        } else {
            const b = branches.find(br => String(br.id) === String($('fromBranchId').value));
            fromName = b ? b.name : labels.shop;
        }

        let toName = labels.shop;
        if (toType === 'warehouse') {
            const wh = warehouses.find(w => String(w.id) === String($('toWarehouseId').value));
            toName = wh ? wh.name : labels.warehouse;
        } else {
            const b = branches.find(br => String(br.id) === String($('toBranchId').value));
            toName = b ? b.name : labels.shop;
        }

        $('summaryFromText').textContent = fromName;
        $('summaryToText').textContent = toName;

        let totalUnits = 0;
        let validProdCount = 0;
        let sideHtml = '';

        const rows = document.querySelectorAll('#itemsTableBody .transfer-row');
        rows.forEach(row => {
            const sel = row.querySelector('.prod-select');
            const qtyInp = row.querySelector('.qty-input');
            const pid = sel.value;
            const qty = parseFloat(qtyInp.value || 0);

            if (pid && productStocks[pid]) {
                validProdCount++;
                totalUnits += qty;
                const p = productStocks[pid];
                const avail = getSourceStock(pid) || 0;
                const remain = Math.max(0, avail - qty);

                sideHtml += `<div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                    <div>
                        <div class="fw-semibold text-dark">${p.name}</div>
                        <div class="text-muted" style="font-size:11.5px;">${labels.avail}: <b>${fmt(avail)}</b> &rarr; ${fmt(remain)}</div>
                    </div>
                    <div class="text-end">
                        <span class="gd-pill gd-pill-blue">+ ${fmt(qty)}</span>
                    </div>
                </div>`;
            }
        });

        $('summaryProdCountText').textContent = validProdCount;
        $('totalUnitsDisplay').textContent = fmt(totalUnits);

        if (sideHtml) {
            $('sideItemsList').innerHTML = sideHtml;
        } else {
            $('sideItemsList').innerHTML = `<div class="text-center text-muted py-4"><i class="bi bi-boxes fs-3 d-block mb-1 text-slate-300" style="opacity:0.4;"></i>${labels.pick}</div>`;
        }
    }

    // Add new product row
    $('addRowBtn').addEventListener('click', function () {
        const tbody = $('itemsTableBody');
        const firstRow = tbody.querySelector('.transfer-row');
        const newRow = firstRow.cloneNode(true);

        const newIndex = rowCounter++;
        newRow.setAttribute('data-index', newIndex);

        const select = newRow.querySelector('.prod-select');
        select.name = `items[${newIndex}][product_id]`;
        select.value = '';

        const qtyInput = newRow.querySelector('.qty-input');
        qtyInput.name = `items[${newIndex}][quantity]`;
        qtyInput.value = '';
        qtyInput.classList.remove('is-invalid');

        const availBadge = newRow.querySelector('.avail-badge');
        availBadge.textContent = '—';
        availBadge.className = 'avail-badge gd-pill gd-pill-slate';

        const errDiv = newRow.querySelector('.row-err');
        errDiv.textContent = '';
        errDiv.style.display = 'none';

        tbody.appendChild(newRow);
        bindRowEvents(newRow);
        updateAllRows();
    });

    function bindRowEvents(row) {
        const select = row.querySelector('.prod-select');
        const qty = row.querySelector('.qty-input');
        const removeBtn = row.querySelector('.remove-row-btn');

        select.addEventListener('change', () => {
            updateRowState(row);
            updateSummary();
        });

        qty.addEventListener('input', () => {
            updateRowState(row);
            updateSummary();
        });

        removeBtn.addEventListener('click', () => {
            const rows = document.querySelectorAll('#itemsTableBody .transfer-row');
            if (rows.length > 1) {
                row.remove();
                updateAllRows();
            }
        });
    }

    // Bind existing rows
    document.querySelectorAll('#itemsTableBody .transfer-row').forEach(bindRowEvents);

    $('fromWarehouseId').addEventListener('change', updateAllRows);
    $('toWarehouseId').addEventListener('change', updateSummary);
    if ($('fromBranchId')) $('fromBranchId').addEventListener('change', updateAllRows);
    if ($('toBranchId')) $('toBranchId').addEventListener('change', updateSummary);

    document.querySelectorAll('input[name="from_type"], input[name="to_type"]').forEach(r => r.addEventListener('change', syncVisibility));

    $('swapBtn').addEventListener('click', function () {
        const f = radio('from_type'), t = radio('to_type');
        const fw = $('fromWarehouseId').value, tw = $('toWarehouseId').value;
        const fb = $('fromBranchId') ? $('fromBranchId').value : '';
        const tb = $('toBranchId') ? $('toBranchId').value : '';

        document.querySelector('input[name="from_type"][value="' + t + '"]').checked = true;
        document.querySelector('input[name="to_type"][value="' + f + '"]').checked = true;

        $('fromWarehouseId').value = tw;
        $('toWarehouseId').value = fw;
        if ($('fromBranchId')) $('fromBranchId').value = tb;
        if ($('toBranchId')) $('toBranchId').value = fb;

        syncVisibility();
    });

    $('transferForm').addEventListener('submit', function (e) {
        const fType = radio('from_type'), tType = radio('to_type');
        const fWh = $('fromWarehouseId') ? $('fromWarehouseId').value : '';
        const tWh = $('toWarehouseId') ? $('toWarehouseId').value : '';
        const fBr = $('fromBranchId') ? $('fromBranchId').value : '';
        const tBr = $('toBranchId') ? $('toBranchId').value : '';

        if (fType === 'warehouse' && tType === 'warehouse' && fWh === tWh) {
            e.preventDefault();
            alert('Ghala la chanzo na ghala lengwa haviwezi kuwa sawa.');
            return;
        }

        if ((fType === 'shop' || fType === 'branch') && (tType === 'shop' || tType === 'branch') && fBr === tBr) {
            e.preventDefault();
            alert('Duka/Tawi la chanzo na duka/tawi lengwa haviwezi kuwa sawa.');
            return;
        }

        if (fType === 'warehouse' && !fWh) {
            e.preventDefault();
            alert('Tafadhali chagua ghala la chanzo cha mzigo.');
            $('fromWarehouseId').focus();
            return;
        }

        if (tType === 'warehouse' && !tWh) {
            e.preventDefault();
            alert('Tafadhali chagua ghala lengwa la kupeleka mzigo.');
            $('toWarehouseId').focus();
            return;
        }

        let hasValidItem = false;
        let hasOverStock = false;

        document.querySelectorAll('#itemsTableBody .transfer-row').forEach(row => {
            const pid = row.querySelector('.prod-select').value;
            const qty = parseFloat(row.querySelector('.qty-input').value || 0);
            if (pid && qty > 0) {
                hasValidItem = true;
                const avail = getSourceStock(pid);
                if (avail !== null && avail !== undefined && qty > avail) {
                    hasOverStock = true;
                }
            }
        });

        if (!hasValidItem) {
            e.preventDefault();
            alert(labels.emptyProducts);
            return;
        }

        if (hasOverStock) {
            e.preventDefault();
            alert(labels.over);
            return;
        }
    });

    syncVisibility();
})();
</script>
@endpush
