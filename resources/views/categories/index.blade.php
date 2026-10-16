@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h2 class="fw-bold mb-0">{{ __('Product Categories') }}</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('Add Category') }}
    </a>
</div>

<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul me-2"></i> {{ __('All Categories') }}</span>
        <div class="search-box">
            <form action="{{ route('categories.index') }}" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <button type="submit" class="btn border-0 ps-3 pe-2 text-primary shadow-none bg-transparent" style="padding-top: 0; padding-bottom: 0;"><i class="bi bi-search fs-5"></i></button>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="{{ __('Search categories...') }}" value="{{ request('search') }}" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text">{{ __('Search') }}</span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
        </div>
    </div>
    <div class="px-3 px-md-4 pt-3 d-flex justify-content-end gap-2">
        <button type="submit" form="bulkDeleteForm"
            class="btn btn-sm btn-danger px-3 shadow-sm rounded-pill align-items-center gap-1 d-none"
            onclick="return confirm('{{ __('Are you sure you want to delete the selected categories?') }}')"
            id="deleteBtn">
            <i class="bi bi-trash3-fill me-1"></i> <span>{{ __('Delete') }}</span>
        </button>
        <button type="button" class="btn btn-sm shadow-sm rounded-pill px-3 fw-bold" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;" id="toggleSelectBtn">
            <i class="bi bi-check2-square me-1"></i> {{ __('Select') }}
        </button>
    </div>
    <div id="selectAllBar" class="d-none mx-3 mx-md-4 mt-2 px-3 py-2 rounded-3" style="background-color: #f1f5f9;">
        <div class="d-flex align-items-center gap-3">
            <div class="form-check mb-0">
                <input class="form-check-input border-secondary" type="checkbox" id="selectAll" style="cursor:pointer; width:1.15rem; height:1.15rem;">
                <label class="form-check-label ms-1 fw-bold text-dark" for="selectAll" style="font-size:0.9rem;">{{ __('Select All') }}</label>
            </div>
            <span class="text-muted small" id="selectedCountLabel">0 {{ __('selected') }}</span>
        </div>
    </div>
    <form id="bulkDeleteForm" action="{{ route('categories.bulk-destroy') }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-admin mb-0">
                <thead>
                    <tr>
                        <th class="selection-th d-none" style="width: 40px;"></th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="selection-cell d-none">
                            <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" form="bulkDeleteForm" value="{{ $category->id }}" style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                        </td>
                        <td class="fw-bold text-muted">#{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>{{ Str::limit($category->description, 50) ?: '-' }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success mb-1">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger mb-1">{{ __('Inactive') }}</span>
                            @endif
                            @if($category->is_service)
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">{{ __('Service/Food') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-light text-primary me-1" title="{{ __('Edit') }}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger" title="{{ __('Delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-3"></i>
                            {{ __('No categories found.') }}
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleSelectBtn    = document.getElementById('toggleSelectBtn');
    const deleteBtn          = document.getElementById('deleteBtn');
    const selectAllBar       = document.getElementById('selectAllBar');
    const selectAllInput     = document.getElementById('selectAll');
    const selectedCountLabel = document.getElementById('selectedCountLabel');
    const checkboxCells      = document.querySelectorAll('.selection-cell');
    const selectionThs       = document.querySelectorAll('.selection-th');
    const checkboxes         = document.querySelectorAll('.row-checkbox');

    let selectMode = false;

    function updateUI() {
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        selectedCountLabel.textContent = checkedCount + ' {{ __("selected") }}';

        if (checkedCount > 0) {
            deleteBtn.classList.remove('d-none');
            deleteBtn.classList.add('d-inline-flex');
        } else {
            deleteBtn.classList.add('d-none');
            deleteBtn.classList.remove('d-inline-flex');
        }

        if (selectAllInput) {
            selectAllInput.checked = checkboxes.length > 0 && checkedCount === checkboxes.length;
            selectAllInput.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        }
    }

    toggleSelectBtn.addEventListener('click', function () {
        selectMode = !selectMode;
        if (selectMode) {
            toggleSelectBtn.innerHTML = '<i class="bi bi-x-circle me-1"></i> {{ __("Cancel") }}';
            selectAllBar.classList.remove('d-none');
            checkboxCells.forEach(c => c.classList.remove('d-none'));
            selectionThs.forEach(c => c.classList.remove('d-none'));
        } else {
            toggleSelectBtn.innerHTML = '<i class="bi bi-check2-square me-1"></i> {{ __("Select") }}';
            selectAllBar.classList.add('d-none');
            checkboxCells.forEach(c => c.classList.add('d-none'));
            selectionThs.forEach(c => c.classList.add('d-none'));
            checkboxes.forEach(cb => cb.checked = false);
            if (selectAllInput) {
                selectAllInput.checked = false;
                selectAllInput.indeterminate = false;
            }
            updateUI();
        }
    });

    if (selectAllInput) {
        selectAllInput.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateUI();
        });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateUI));

    updateUI();
});
</script>
@endpush
@endsection

