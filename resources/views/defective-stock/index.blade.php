@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Damaged Stock') }}</h4>
        <span class="text-muted small">{{ __('History of defective stock removed from inventory') }}</span>
    </div>
    <div>
        <a href="{{ route('defective-stock.create') }}" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> {{ __('Remove Defective Stock') }}
        </a>
    </div>
</div>

{{-- Success/error notifications are already handled globally by the toast in layouts.admin --}}

{{-- Desktop / tablet table --}}
<div class="card border-0 shadow-sm d-none d-md-block" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;">{{ __('Date') }}</th>
                        <th class="fw-medium border-0">{{ __('Product') }}</th>
                        <th class="fw-medium border-0 text-end">{{ __('Quantity') }}</th>
                        <th class="fw-medium border-0">{{ __('Reason') }}</th>
                        <th class="fw-medium border-0">{{ __('Recorded By') }}</th>
                        <th class="pe-4 fw-medium border-0 text-center rounded-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($defectiveStocks as $record)
                    <tr>
                        <td class="ps-4 py-3">{{ $record->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $record->product->name ?? 'Unknown' }}</div>
                        </td>
                        <td class="text-end fw-bold text-danger">-{{ number_format($record->quantity) }}</td>
                        <td class="text-muted">{{ $record->reason ?? '-' }}</td>
                        <td class="text-muted">{{ $record->user->name ?? '-' }}</td>
                        <td class="pe-4 text-center">
                            <a href="{{ route('defective-stock.edit', $record->id) }}" class="btn btn-sm btn-light text-dark shadow-sm" style="border-radius: 6px;" title="{{ __('Edit') }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;" title="{{ __('Delete') }}" data-bs-toggle="modal" data-bs-target="#deleteDefectiveModal" onclick="openDeleteDefectiveModal('{{ route('defective-stock.destroy', $record->id) }}', '{{ $record->product->name ?? '' }}', '{{ number_format($record->quantity) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
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
                <div class="fw-bold text-dark">{{ $record->product->name ?? 'Unknown' }}</div>
                <span class="fw-bold text-danger">-{{ number_format($record->quantity) }}</span>
            </div>
            <div class="text-muted small mb-1">{{ $record->created_at->format('d M Y') }} &middot; {{ $record->user->name ?? '-' }}</div>
            @if($record->reason)
            <div class="text-muted small mb-2">{{ $record->reason }}</div>
            @endif
            <div class="d-flex gap-2 mt-2">
                <a href="{{ route('defective-stock.edit', $record->id) }}" class="btn btn-sm btn-light text-dark shadow-sm rounded-3 flex-fill">
                    <i class="bi bi-pencil"></i> {{ __('Edit') }}
                </a>
                <button type="button" class="btn btn-sm btn-light text-danger shadow-sm rounded-3 flex-fill" data-bs-toggle="modal" data-bs-target="#deleteDefectiveModal" onclick="openDeleteDefectiveModal('{{ route('defective-stock.destroy', $record->id) }}', '{{ $record->product->name ?? '' }}', '{{ number_format($record->quantity) }}')">
                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                </button>
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

<script>
function openDeleteDefectiveModal(url, productName, quantity) {
    document.getElementById('deleteDefectiveForm').action = url;
    document.getElementById('deleteDefectiveDetails').innerText = productName + ' — ' + quantity + ' {{ __("units") }}';
    // Modal is opened by the button's own data-bs-toggle/data-bs-target attributes.
}
</script>
@endsection
