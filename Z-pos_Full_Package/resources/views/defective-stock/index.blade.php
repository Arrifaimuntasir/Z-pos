@extends('layouts.admin')

@section('content')
@php
    // Supermarket/Grocery: defective items are a loss until marked good, which returns them to stock.
    $isGrocery = Auth::user()->shop && Auth::user()->shop->business_type === 'Supermarket / Grocery';
    $canMarkGood = $isGrocery && Auth::user()->hasRole('Administrator');
@endphp
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ $isGrocery ? __('Defective Items') : __('Damaged Stock') }}</h4>
        <span class="text-muted small">{{ $isGrocery ? __('Defective items count as a loss until you mark them as good') : __('History of defective stock removed from inventory') }}</span>
    </div>
    <div>
        <a href="{{ route('defective-stock.create') }}" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> {{ $isGrocery ? __('Add Defective Item') : __('Remove Defective Stock') }}
        </a>
    </div>
</div>

{{-- Success/error notifications are already handled globally by the toast in layouts.admin --}}

<div class="d-flex justify-content-end gap-2 mb-3">
    <button type="button" class="btn btn-sm btn-danger px-3 shadow-sm rounded-pill align-items-center gap-1 d-none" id="deleteBtn" data-bs-toggle="modal" data-bs-target="#bulkDeleteDefectiveModal">
        <i class="bi bi-trash3-fill me-1"></i> <span>{{ __('Delete') }}</span>
    </button>
    <button type="button" class="btn btn-sm shadow-sm rounded-pill px-3 fw-bold" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;" id="toggleSelectBtn">
        <i class="bi bi-check2-square me-1"></i> {{ __('Select') }}
    </button>
</div>
<div id="selectAllBar" class="d-none mb-3 px-3 py-2 rounded-3" style="background-color: #f1f5f9;">
    <div class="d-flex align-items-center gap-3">
        <div class="form-check mb-0">
            <input class="form-check-input border-secondary" type="checkbox" id="selectAll" style="cursor:pointer; width:1.15rem; height:1.15rem;">
            <label class="form-check-label ms-1 fw-bold text-dark" for="selectAll" style="font-size:0.9rem;">{{ __('Select All') }}</label>
        </div>
        <span class="text-muted small" id="selectedCountLabel">0 {{ __('selected') }}</span>
    </div>
</div>
<form id="bulkDeleteForm" action="{{ route('defective-stock.bulk-destroy') }}" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="restore_stock" id="bulkRestoreStockInput" value="0">
</form>

{{-- Desktop / tablet table --}}
<div class="card border-0 shadow-sm d-none d-md-block" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 selection-th d-none" style="width: 40px;"></th>
                        <th class="fw-medium border-0" style="padding-top: 15px; padding-bottom: 15px;">{{ __('Date') }}</th>
                        <th class="fw-medium border-0">{{ __('Product') }}</th>
                        <th class="fw-medium border-0 text-end">{{ __('Quantity') }}</th>
                        @if($isGrocery)
                        <th class="fw-medium border-0">{{ __('Status') }}</th>
                        @endif
                        <th class="fw-medium border-0">{{ __('Reason') }}</th>
                        <th class="fw-medium border-0">{{ __('Recorded By') }}</th>
                        <th class="pe-4 fw-medium border-0 text-center rounded-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($defectiveStocks as $record)
                    <tr>
                        <td class="ps-4 selection-cell d-none">
                            <input class="form-check-input row-checkbox" type="checkbox" data-id="{{ $record->id }}" style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                        </td>
                        <td class="py-3">{{ $record->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $record->product->name ?? 'Unknown' }}</div>
                        </td>
                        @if($record->isRestored())
                        <td class="text-end fw-bold text-success">+{{ number_format($record->quantity) }}</td>
                        @else
                        <td class="text-end fw-bold text-danger">-{{ number_format($record->quantity) }}</td>
                        @endif
                        @if($isGrocery)
                        <td>
                            @if($record->isRestored())
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">{{ __('Back in stock') }}</span>
                                @if($record->restored_at)
                                <div class="text-muted small mt-1">{{ $record->restored_at->format('d M Y') }}</div>
                                @endif
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">{{ __('Loss') }}</span>
                            @endif
                        </td>
                        @endif
                        <td class="text-muted">{{ $record->reason ?? '-' }}</td>
                        <td class="text-muted">{{ $record->user->name ?? '-' }}</td>
                        <td class="pe-4 text-center">
                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                @if($canMarkGood && !$record->isRestored())
                                <form action="{{ route('defective-stock.mark-good', $record->id) }}" method="POST" class="d-inline mark-good-form">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success shadow-sm" style="border-radius: 6px;" title="{{ __('Mark as Good') }}">
                                        <i class="bi bi-check-circle me-1"></i> {{ __('Mark as Good') }}
                                    </button>
                                </form>
                                @endif
                                @if(!$record->isRestored())
                                <a href="{{ route('defective-stock.edit', $record->id) }}" class="btn btn-sm btn-light text-dark shadow-sm" style="border-radius: 6px;" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;" title="{{ __('Delete') }}" data-bs-toggle="modal" data-bs-target="#deleteDefectiveModal" onclick="openDeleteDefectiveModal('{{ route('defective-stock.destroy', $record->id) }}', '{{ $record->product->name ?? '' }}', '{{ number_format($record->quantity) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @else
                                <form action="{{ route('defective-stock.destroy', $record->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this record?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="restore_stock" value="0">
                                    <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;" title="{{ __('Delete') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isGrocery ? 8 : 7 }}" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-box-seam fs-1 text-light-secondary"></i></div>
                            <h6 class="fw-bold">{{ __('No damaged stock recorded') }}</h6>
                            <p class="small mb-0">{{ __('Remove defective units from stock as soon as you find them.') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Mobile card list --}}
<div class="d-md-none">
    @forelse($defectiveStocks as $record)
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 16px;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-start gap-2">
                    <div class="selection-cell d-none pt-1">
                        <input class="form-check-input row-checkbox" type="checkbox" data-id="{{ $record->id }}" style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                    </div>
                    <div class="fw-bold text-dark">{{ $record->product->name ?? 'Unknown' }}</div>
                </div>
                @if($record->isRestored())
                <span class="fw-bold text-success">+{{ number_format($record->quantity) }}</span>
                @else
                <span class="fw-bold text-danger">-{{ number_format($record->quantity) }}</span>
                @endif
            </div>
            @if($isGrocery)
            <div class="mb-2">
                @if($record->isRestored())
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">{{ __('Back in stock') }}</span>
                @else
                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">{{ __('Loss') }}</span>
                @endif
            </div>
            @endif
            <div class="text-muted small mb-1">{{ $record->created_at->format('d M Y') }} &middot; {{ $record->user->name ?? '-' }}</div>
            @if($record->reason)
            <div class="text-muted small mb-2">{{ $record->reason }}</div>
            @endif
            <div class="d-flex flex-wrap gap-2 mt-2">
                @if($canMarkGood && !$record->isRestored())
                <form action="{{ route('defective-stock.mark-good', $record->id) }}" method="POST" class="w-100 mark-good-form">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success shadow-sm rounded-3 w-100">
                        <i class="bi bi-check-circle me-1"></i> {{ __('Mark as Good') }}
                    </button>
                </form>
                @endif
                @if(!$record->isRestored())
                <a href="{{ route('defective-stock.edit', $record->id) }}" class="btn btn-sm btn-light text-dark shadow-sm rounded-3 flex-fill">
                    <i class="bi bi-pencil"></i> {{ __('Edit') }}
                </a>
                <button type="button" class="btn btn-sm btn-light text-danger shadow-sm rounded-3 flex-fill" data-bs-toggle="modal" data-bs-target="#deleteDefectiveModal" onclick="openDeleteDefectiveModal('{{ route('defective-stock.destroy', $record->id) }}', '{{ $record->product->name ?? '' }}', '{{ number_format($record->quantity) }}')">
                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                </button>
                @else
                <form action="{{ route('defective-stock.destroy', $record->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('{{ __('Are you sure you want to delete this record?') }}')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="restore_stock" value="0">
                    <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3 w-100">
                        <i class="bi bi-trash"></i> {{ __('Delete') }}
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body text-center py-5 text-muted">
            <div class="mb-3"><i class="bi bi-box-seam fs-1 text-light-secondary"></i></div>
            <h6 class="fw-bold">{{ __('No damaged stock recorded') }}</h6>
            <p class="small mb-0">{{ __('Remove defective units from stock as soon as you find them.') }}</p>
        </div>
    </div>
    @endforelse
</div>

{{-- Shared delete confirmation modal --}}
<div class="modal fade" id="deleteDefectiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">{{ __('Delete Record') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">{{ __('Are you sure you want to delete this record?') }}</p>
                <p class="text-muted small mb-0" id="deleteDefectiveDetails"></p>
                <hr>
                <p class="mb-0 fw-medium text-dark">{{ __('Should this quantity be added back to stock?') }}</p>
            </div>
            <div class="modal-footer border-0 flex-column gap-2">
                <form id="deleteDefectiveForm" method="POST" class="w-100 d-flex flex-column gap-2">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="restore_stock" id="restoreStockInput" value="0">
                    <button type="submit" class="btn btn-success w-100" onclick="document.getElementById('restoreStockInput').value='1'">
                        <i class="bi bi-arrow-return-left me-1"></i> {{ __('Yes, add back to stock') }}
                    </button>
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="document.getElementById('restoreStockInput').value='0'">
                        {{ __("No, don't add back to stock") }}
                    </button>
                    <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Bulk delete confirmation modal --}}
<div class="modal fade" id="bulkDeleteDefectiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">{{ __('Delete Selected Records') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="bulkDeleteDefectiveCount"></p>
                <hr>
                <p class="mb-0 fw-medium text-dark">{{ __('Should these quantities be added back to stock?') }}</p>
            </div>
            <div class="modal-footer border-0 flex-column gap-2">
                <button type="button" class="btn btn-success w-100" id="bulkRestoreYesBtn">
                    <i class="bi bi-arrow-return-left me-1"></i> {{ __('Yes, add back to stock') }}
                </button>
                <button type="button" class="btn btn-outline-danger w-100" id="bulkRestoreNoBtn">
                    {{ __("No, don't add back to stock") }}
                </button>
                <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteDefectiveModal(url, productName, quantity) {
    document.getElementById('deleteDefectiveForm').action = url;
    document.getElementById('deleteDefectiveDetails').innerText = productName + ' — ' + quantity + ' {{ __("units") }}';
    // Modal is opened by the button's own data-bs-toggle/data-bs-target attributes.
}
</script>

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
    const bulkRestoreInput   = document.getElementById('bulkRestoreStockInput');
    const bulkCountLabel     = document.getElementById('bulkDeleteDefectiveCount');
    const selectedIds = new Set();

    document.querySelectorAll('.mark-good-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (form.dataset.busy || !confirm('{{ __("Mark this item as good and add it back to stock? It will no longer count as a loss.") }}')) {
                e.preventDefault();
                return;
            }
            form.dataset.busy = '1';
            form.querySelector('button[type=submit]').disabled = true;
        });
    });

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

    document.getElementById('deleteBtn').addEventListener('click', function () {
        bulkCountLabel.textContent = selectedIds.size + ' {{ __("record(s) selected for deletion.") }}';
    });

    function submitBulkDelete(restore) {
        bulkRestoreInput.value = restore ? '1' : '0';
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            bulkDeleteForm.appendChild(input);
        });
        bulkDeleteForm.submit();
    }

    document.getElementById('bulkRestoreYesBtn').addEventListener('click', function () { submitBulkDelete(true); });
    document.getElementById('bulkRestoreNoBtn').addEventListener('click', function () { submitBulkDelete(false); });

    updateUI();
});
</script>
@endpush
@endsection
