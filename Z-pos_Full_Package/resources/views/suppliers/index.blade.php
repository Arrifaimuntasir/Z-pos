@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Suppliers') }}</h4>
        <span class="text-muted small">{{ __('Manage your suppliers and distributors') }}</span>
    </div>
    <div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> {{ __('Add Supplier') }}
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex justify-content-end gap-2">
        <button type="submit" form="bulkDeleteForm"
            class="btn btn-sm btn-danger px-3 shadow-sm rounded-pill align-items-center gap-1 d-none"
            onclick="return confirm('{{ __('Are you sure you want to delete the selected suppliers?') }}')"
            id="deleteBtn">
            <i class="bi bi-trash3-fill me-1"></i> <span>{{ __('Delete') }}</span>
        </button>
        <button type="button" class="btn btn-sm shadow-sm rounded-pill px-3 fw-bold" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;" id="toggleSelectBtn">
            <i class="bi bi-check2-square me-1"></i> {{ __('Select') }}
        </button>
    </div>

    <div id="selectAllBar" class="d-none px-3 px-md-4 py-2 border-bottom" style="background-color: #f1f5f9;">
        <div class="d-flex align-items-center gap-3">
            <div class="form-check mb-0">
                <input class="form-check-input border-secondary" type="checkbox" id="selectAll" style="cursor:pointer; width:1.15rem; height:1.15rem;">
                <label class="form-check-label ms-1 fw-bold text-dark" for="selectAll" style="font-size:0.9rem;">{{ __('Select All') }}</label>
            </div>
            <span class="text-muted small" id="selectedCountLabel">0 {{ __('selected') }}</span>
        </div>
    </div>

    <form id="bulkDeleteForm" action="{{ route('suppliers.bulk-destroy') }}" method="POST">
        @csrf
        @method('DELETE')
    </form>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-3 border-0 selection-th d-none" style="width: 40px;"></th>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;">{{ __('Supplier Name') }}</th>
                        <th class="fw-medium border-0">{{ __('Contact Person') }}</th>
                        <th class="fw-medium border-0">{{ __('Phone') }}</th>
                        <th class="fw-medium border-0">{{ __('Email') }}</th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td class="ps-3 selection-cell d-none">
                            <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" form="bulkDeleteForm" value="{{ $supplier->id }}" style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                        </td>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-dark">{{ $supplier->name }}</div>
                        </td>
                        <td>{{ $supplier->contact_person ?? '-' }}</td>
                        <td>{{ $supplier->phone ?? '-' }}</td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-light text-primary me-2 shadow-sm rounded-3">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to delete this supplier?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-truck fs-1 text-light-secondary"></i></div>
                            <h6 class="fw-bold">{{ __('No suppliers found') }}</h6>
                            <p class="small mb-0">{{ __('Start by adding your first supplier.') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
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
