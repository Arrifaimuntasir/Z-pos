@extends('layouts.admin')

@section('title', 'Products List')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0">{{ __('Products') }}</h4>
        @if(isset($activeBranch) && $activeBranch)
            <span class="badge bg-primary rounded-pill mt-1 px-3 py-1">
                <i class="bi bi-shop me-1"></i> {{ $activeBranch->name }}
            </span>
        @else
            <span class="badge bg-secondary rounded-pill mt-1 px-3 py-1">
                <i class="bi bi-grid me-1"></i> {{ __('All Branches') }}
            </span>
        @endif
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> {{ __('Add Product') }}
    </a>
</div>


<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <!-- Bulk Delete Controls -->
        <div class="d-flex align-items-center">
            <button type="button" id="toggleSelectBtn" class="btn btn-outline-secondary btn-sm shadow-sm rounded-pill px-3">
                <i class="bi bi-check2-square"></i> Select
            </button>
            <form id="bulkDeleteForm" action="{{ route('products.bulk-destroy') }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" id="bulkDeleteBtn" class="btn btn-danger btn-sm shadow-sm rounded-pill px-3 ms-2 d-none" onclick="return confirm('Are you sure you want to delete selected products?');">
                    <i class="bi bi-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
                </button>
            </form>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('products.index') }}" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 350px;">
            <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
            <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="{{ __('Search products...') }}" value="{{ request('search') }}" style="font-size: 0.95rem; height: 38px;">
        </form>
    </div>
    
    <div class="card-body p-4 pt-0">
        <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-muted table-light">
                        <tr>
                            <th class="select-column d-none" style="width: 40px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Brand & Model') }}</th>
                            <th>{{ __('SKU') }}</th>
                            <th class="text-center">{{ __('Stock') }}</th>
                            <th class="text-end">{{ __('Buying Price') }}</th>
                            <th class="text-end">{{ __('Selling Price') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="select-column d-none">
                                <div class="form-check">
                                    <input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]" value="{{ $product->id }}" form="bulkDeleteForm">
                                </div>
                            </td>
                            <td>
                                <div class="search-toolbar">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white bg-primary me-3 shadow-sm" style="width: 28px; height: 28px; font-size: 0.85rem;">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $product->name }}</h6>
                                        <span class="text-muted small">{{ $product->category->name ?? 'No Category' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $product->brand->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $product->model ?? '-' }}</div>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td class="text-center">
                                @php
                                    $hasBranches = $product->branches->isNotEmpty();

                                    if ($hasBranches && isset($activeBranchId) && $activeBranchId) {
                                        $branchRow = $product->branches->firstWhere('id', $activeBranchId);
                                        $stock = $branchRow ? $branchRow->pivot->quantity : 0;
                                        $label = '';
                                    } elseif ($hasBranches) {
                                        $stock = $product->branches->sum('pivot.quantity');
                                        $label = ' <small>(Total)</small>';
                                    } else {
                                        $stock = $product->stock;
                                        $label = '';
                                    }
                                @endphp
                                @if($stock <= $product->alert_quantity)
                                    <span class="badge bg-danger rounded-pill px-3">{{ $stock }} {{ $product->unit->short_name ?? '' }}{!! $label !!}</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">{{ $stock }} {{ $product->unit->short_name ?? '' }}{!! $label !!}</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold text-muted">{{ number_format($product->cost_price) }} TSh</td>
                            <td class="text-end fw-bold">{{ number_format($product->selling_price) }} TSh</td>
                            <td class="text-end">
                                <div class="btn-group gap-1">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;" title="{{ __('Delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                {{ __('No products found. Add your first product to get started.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 d-flex justify-content-end">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');
        const toggleSelectBtn = document.getElementById('toggleSelectBtn');
        const selectColumns = document.querySelectorAll('.select-column');

        let selectMode = false;

        toggleSelectBtn.addEventListener('click', function() {
            selectMode = !selectMode;
            if (selectMode) {
                toggleSelectBtn.innerHTML = '<i class="bi bi-x-circle"></i> Cancel';
                selectColumns.forEach(col => col.classList.remove('d-none'));
            } else {
                toggleSelectBtn.innerHTML = '<i class="bi bi-check2-square"></i> Select';
                selectColumns.forEach(col => col.classList.add('d-none'));
                // Uncheck all
                selectAll.checked = false;
                checkboxes.forEach(cb => cb.checked = false);
                updateBulkDeleteBtn();
            }
        });

        function updateBulkDeleteBtn() {
            const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('d-none');
                selectedCount.textContent = checkedCount;
                toggleSelectBtn.classList.add('d-none');
            } else {
                bulkDeleteBtn.classList.add('d-none');
                toggleSelectBtn.classList.remove('d-none');
            }
            selectAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkDeleteBtn();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteBtn);
        });
    });
</script>
@endsection
