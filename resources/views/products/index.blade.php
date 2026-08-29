@extends('layouts.admin')

@section('title', 'Products List')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">Products</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
</div>

<form action="{{ route('products.bulk-destroy') }}" method="POST" id="bulkDeleteForm">
    @csrf
    @method('DELETE')
</form>

<div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <h5 class="mb-0">Product List</h5>
            <div class="d-flex align-items-center gap-3">
                <button type="submit" form="bulkDeleteForm" class="btn btn-sm btn-danger d-none shadow-sm" id="bulkDeleteBtn" onclick="return confirm('Are you sure you want to delete all selected products?');">
                    <i class="bi bi-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
                </button>
                <form action="{{ route('products.index') }}" method="GET" class="d-flex m-0">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search products..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-muted table-light">
                        <tr>
                            <th style="width: 40px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>Product</th>
                            <th>Brand & Model</th>
                            <th>SKU</th>
                            <th class="text-center">Stock</th>
                            <th class="text-end">Buying Price</th>
                            <th class="text-end">Selling Price</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]" value="{{ $product->id }}" form="bulkDeleteForm">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
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
                                    $activeBranchId = session('active_branch_id');
                                    $hasBranches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->exists();
                                    
                                    if ($hasBranches && $activeBranchId) {
                                        $branch = $product->branches->firstWhere('id', $activeBranchId);
                                        $stock = $branch ? $branch->pivot->quantity : 0;
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
                                <div class="btn-group">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-light text-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No products found. Add your first product to get started.
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

        function updateBulkDeleteBtn() {
            const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('d-none');
                selectedCount.textContent = checkedCount;
            } else {
                bulkDeleteBtn.classList.add('d-none');
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
