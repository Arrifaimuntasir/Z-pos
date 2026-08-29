@extends('layouts.admin')

@section('title', 'Brands')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h2 class="fw-bold mb-0">Brands</h2>
    <a href="{{ route('brands.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Brand
    </a>
</div>

<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-tags me-2"></i> All Brands</span>
        <div class="search-box">
            <form action="{{ route('brands.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search brands..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-admin mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td class="fw-bold text-muted">#{{ $brand->id }}</td>
                        <td class="fw-semibold">{{ $brand->name }}</td>
                        <td>{{ Str::limit($brand->description, 50) ?: '-' }}</td>
                        <td>
                            <a href="{{ route('brands.edit', $brand) }}" class="btn btn-sm btn-light text-primary me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this brand?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-tags fs-1 d-block mb-3"></i>
                            No brands found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            {{ $brands->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
