@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Units (Vipimo)</h4>
        <span class="text-muted small">{{ __('Manage how your products are measured') }}</span>
    </div>
    <div>
        <a href="{{ route('units.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> {{ __('Add Unit') }}
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0 text-dark fw-bold">{{ __('All Units') }}</h5>
        <form action="{{ route('units.index') }}" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <button type="submit" class="btn border-0 ps-3 pe-2 text-primary shadow-none bg-transparent" style="padding-top: 0; padding-bottom: 0;"><i class="bi bi-search fs-5"></i></button>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="{{ __('Search units...') }}" value="{{ request('search') }}" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text">{{ __('Search') }}</span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
    </div>
    <div class="px-3 px-md-4 pt-3 d-flex justify-content-end gap-2">
        <button type="submit" form="bulkDeleteForm"
            class="btn btn-sm btn-danger px-3 shadow-sm rounded-pill align-items-center gap-1 d-none"
            onclick="return confirm('{{ __('Are you sure you want to delete the selected units?') }}')"
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
    <form id="bulkDeleteForm" action="{{ route('units.bulk-destroy') }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-3 border-0 selection-th d-none" style="width: 40px;"></th>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;">Name (e.g. Pieces)</th>
                        <th class="fw-medium border-0">Short Name (e.g. Pcs)</th>
                        <th class="fw-medium border-0">Allow Decimal (Desimali)</th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($units as $unit)
                    <tr>
                        <td class="ps-3 selection-cell d-none">
                            <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" form="bulkDeleteForm" value="{{ $unit->id }}" style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                        </td>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-dark">{{ $unit->name }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $unit->short_name }}</span></td>
                        <td>
                            @if($unit->allow_decimal)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('Yes') }}</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3">{{ __('No') }}</span>
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
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-rulers fs-1 text-light-secondary mb-3 d-block"></i>
                            <h5>{{ __('No units found') }}</h5>
                            <p class="mb-0">Please add some units (like Pcs, Kg) first.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            {{ $units->links('pagination::bootstrap-5') }}
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

