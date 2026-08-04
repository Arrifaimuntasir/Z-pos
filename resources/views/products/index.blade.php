@extends('layouts.admin')

@section('title', 'Products List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Products</h4>
    <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-muted table-light">
                    <tr>
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
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-box-seam text-secondary fs-4"></i>
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
                            @if($product->stock <= $product->alert_quantity)
                                <span class="badge bg-danger rounded-pill px-3">{{ $product->stock }} {{ $product->unit->short_name ?? '' }}</span>
                            @else
                                <span class="badge bg-success rounded-pill px-3">{{ $product->stock }} {{ $product->unit->short_name ?? '' }}</span>
                            @endif
                        </td>
                        <td class="text-end fw-semibold text-muted">{{ number_format($product->cost_price) }} TSh</td>
                        <td class="text-end fw-bold">{{ number_format($product->selling_price) }} TSh</td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-light text-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            No products found. Add your first product to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
