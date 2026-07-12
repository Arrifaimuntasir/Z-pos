@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Product Categories</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Category
    </a>
</div>

<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul me-2"></i> All Categories</span>
        <div class="search-box">
            <input type="text" class="form-control form-control-sm" placeholder="Search categories...">
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
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="fw-bold text-muted">#{{ $category->id }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>{{ Str::limit($category->description, 50) ?: '-' }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Active</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-light text-primary me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-3"></i>
                            No categories found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
