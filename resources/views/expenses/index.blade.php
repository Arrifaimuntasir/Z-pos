@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-dark" style="font-size: 24px;">{{ __('Expenses') }}</h4>
        <p class="text-muted mb-0" style="font-size: 14px;">{{ __('Track and manage your business expenses') }}</p>
    </div>
    <a href="{{ route('expenses.create') }}" class="btn btn-dark shadow-sm px-3" style="border-radius: 8px; font-weight: 500;">
        <i class="bi bi-plus-lg me-1"></i> {{ __('New Expense') }}
    </a>
</div>

<div class="card border mb-4" style="border-radius: 12px; max-width: 280px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div class="card-body p-3 py-4">
        <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">{{ __('TOTAL EXPENSES') }}</div>
        <h3 class="fw-bold mb-0 text-dark" style="font-size: 24px;">{{ number_format($totalExpenses, 2) }}</h3>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0">{{ __('Expense List') }}</h5>
        <form action="{{ route('expenses.index') }}" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <button type="submit" class="btn border-0 ps-3 pe-2 text-primary shadow-none bg-transparent" style="padding-top: 0; padding-bottom: 0;"><i class="bi bi-search fs-5"></i></button>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="{{ __('Search expenses...') }}" value="{{ request('search') }}" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text">{{ __('Search') }}</span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
    </div>
    <div class="px-4 pt-3 d-flex justify-content-end gap-2">
        <button type="submit" form="bulkDeleteForm"
            class="btn btn-sm btn-danger px-3 shadow-sm rounded-pill align-items-center gap-1 d-none"
            onclick="return confirm('{{ __('Are you sure you want to delete the selected expenses?') }}')"
            id="deleteBtn">
            <i class="bi bi-trash3-fill me-1"></i> <span>{{ __('Delete') }}</span>
        </button>
        <button type="button" class="btn btn-sm shadow-sm rounded-pill px-3 fw-bold" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;" id="toggleSelectBtn">
            <i class="bi bi-check2-square me-1"></i> {{ __('Select') }}
        </button>
    </div>
    <div id="selectAllBar" class="d-none mx-4 mt-2 px-3 py-2 rounded-3" style="background-color: #f1f5f9;">
        <div class="d-flex align-items-center gap-3">
            <div class="form-check mb-0">
                <input class="form-check-input border-secondary" type="checkbox" id="selectAll" style="cursor:pointer; width:1.15rem; height:1.15rem;">
                <label class="form-check-label ms-1 fw-bold text-dark" for="selectAll" style="font-size:0.9rem;">{{ __('Select All') }}</label>
            </div>
            <span class="text-muted small" id="selectedCountLabel">0 {{ __('selected') }}</span>
        </div>
    </div>
    <form id="bulkDeleteForm" action="{{ route('expenses.bulk-destroy') }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
    <div class="card-body p-4">
        {{-- Desktop table --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle">
                <thead class="text-muted table-light">
                    <tr>
                        <th class="selection-th d-none" style="width: 40px;"></th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th class="text-end">{{ __('Amount') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td class="selection-cell d-none">
                            <input class="form-check-input row-checkbox" type="checkbox" data-id="{{ $expense->id }}" style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                        </td>
                        <td class="text-muted">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                        <td class="fw-semibold">{{ $expense->description }}</td>
                        <td>
                            @if($expense->category)
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $expense->category }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-danger">{{ number_format($expense->amount) }} TSh</td>
                        <td class="text-end">
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-light text-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                            {{ __('No expenses recorded yet.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="d-md-none">
            @forelse($expenses as $expense)
            <div class="card border shadow-sm mb-3" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-start gap-2">
                            <div class="selection-cell d-none pt-1">
                                <input class="form-check-input row-checkbox" type="checkbox" data-id="{{ $expense->id }}" style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                            </div>
                            <div class="fw-semibold text-dark">{{ $expense->description }}</div>
                        </div>
                        <div class="fw-bold text-danger text-nowrap ms-2">{{ number_format($expense->amount) }} TSh</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</span>
                            @if($expense->category)
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $expense->category }}</span>
                            @endif
                        </div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-light text-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this expense?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                {{ __('No expenses recorded yet.') }}
            </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $expenses->links('pagination::bootstrap-5') }}
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
    const bulkDeleteForm     = document.getElementById('bulkDeleteForm');
    // Desktop and mobile each render their own checkbox for the same row, so
    // track selection by id and keep every checkbox sharing that id in sync,
    // instead of counting DOM nodes (which would double-count).
    const selectedIds = new Set();

    function allCheckboxesForId(id) {
        return document.querySelectorAll('.row-checkbox[data-id="' + id + '"]');
    }

    function updateUI() {
        selectedCountLabel.textContent = selectedIds.size + ' {{ __("selected") }}';

        if (selectedIds.size > 0) {
            deleteBtn.classList.remove('d-none');
            deleteBtn.classList.add('d-inline-flex');
        } else {
            deleteBtn.classList.add('d-none');
            deleteBtn.classList.remove('d-inline-flex');
        }

        if (selectAllInput) {
            const total = new Set(Array.from(document.querySelectorAll('.row-checkbox')).map(cb => cb.dataset.id)).size;
            selectAllInput.checked = total > 0 && selectedIds.size === total;
            selectAllInput.indeterminate = selectedIds.size > 0 && selectedIds.size < total;
        }
    }

    function bindCheckbox(cb) {
        cb.addEventListener('change', function () {
            const id = this.dataset.id;
            if (this.checked) {
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }
            allCheckboxesForId(id).forEach(other => other.checked = this.checked);
            updateUI();
        });
    }

    document.querySelectorAll('.row-checkbox').forEach(bindCheckbox);

    toggleSelectBtn.addEventListener('click', function () {
        const enteringSelectMode = selectAllBar.classList.contains('d-none');
        if (enteringSelectMode) {
            toggleSelectBtn.innerHTML = '<i class="bi bi-x-circle me-1"></i> {{ __("Cancel") }}';
            selectAllBar.classList.remove('d-none');
            checkboxCells.forEach(c => c.classList.remove('d-none'));
            selectionThs.forEach(c => c.classList.remove('d-none'));
        } else {
            toggleSelectBtn.innerHTML = '<i class="bi bi-check2-square me-1"></i> {{ __("Select") }}';
            selectAllBar.classList.add('d-none');
            checkboxCells.forEach(c => c.classList.add('d-none'));
            selectionThs.forEach(c => c.classList.add('d-none'));
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
            selectedIds.clear();
            if (selectAllInput) {
                selectAllInput.checked = false;
                selectAllInput.indeterminate = false;
            }
            updateUI();
        }
    });

    if (selectAllInput) {
        selectAllInput.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.checked = this.checked;
                if (this.checked) {
                    selectedIds.add(cb.dataset.id);
                } else {
                    selectedIds.delete(cb.dataset.id);
                }
            });
            updateUI();
        });
    }

    bulkDeleteForm.addEventListener('submit', function () {
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            bulkDeleteForm.appendChild(input);
        });
    });

    updateUI();
});
</script>
@endpush
@endsection

