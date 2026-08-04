@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Units (Vipimo)</h4>
        <span class="text-muted small">Manage how your products are measured</span>
    </div>
    <div>
        <a href="{{ route('units.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> Add Unit
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;">Name (e.g. Pieces)</th>
                        <th class="fw-medium border-0">Short Name (e.g. Pcs)</th>
                        <th class="fw-medium border-0">Allow Decimal (Desimali)</th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($units as $unit)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-dark">{{ $unit->name }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $unit->short_name }}</span></td>
                        <td>
                            @if($unit->allow_decimal)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Yes</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">No</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-sm btn-light text-primary me-2 shadow-sm rounded-3">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to delete this unit?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-rulers fs-1 text-light-secondary"></i></div>
                            <h6 class="fw-bold">No units found</h6>
                            <p class="small mb-0">Add your first unit to start measuring products.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
