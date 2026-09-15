@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single { height: calc(2.5rem + 2px) !important; display: flex; align-items: center; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: normal !important; padding-left: 0.75rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 100% !important; }
</style>
@endpush

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Edit Damaged Stock Record') }}</h4>
        <span class="text-muted small">{{ __('Update this defective stock entry') }}</span>
    </div>
    <div>
        <a href="{{ route('defective-stock.index') }}" class="btn btn-light shadow-sm rounded-3">
            <i class="bi bi-clock-history me-2"></i> {{ __('History') }}
        </a>
    </div>
</div>


<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('defective-stock.update', $defectiveStock->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">{{ __('Product') }} <span class="text-danger">*</span></label>
                        <select name="product_id" id="productSelect" class="form-select @error('product_id') is-invalid @enderror" required style="width: 100%;">
                            <option value="">{{ __('Select Product...') }}</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $defectiveStock->product_id) == $product->id ? 'selected' : '' }} data-stock="{{ $product->current_stock }}">
                                    {{ $product->name }} ({{ __('Stock') }}: {{ $product->current_stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="stockHint"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">{{ __('Defective Quantity') }} <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $defectiveStock->quantity) }}" min="0.01" step="0.01" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">{{ __('Reason') }}</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="{{ __('e.g. Cracked screen, water damage...') }}">{{ old('reason', $defectiveStock->reason) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius: 8px;">
                        <i class="bi bi-check-lg me-2"></i>{{ __('Update Record') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productSelect').select2({
            placeholder: "{{ __('Select Product...') }}",
            width: '100%'
        });

        function updateStockHint() {
            const selected = document.getElementById('productSelect').options[document.getElementById('productSelect').selectedIndex];
            const stock = selected ? selected.getAttribute('data-stock') : null;
            const hint = document.getElementById('stockHint');
            hint.innerText = (stock !== null && stock !== '') ? "{{ __('Current stock') }}: " + stock : '';
        }

        $('#productSelect').on('change', updateStockHint);
        updateStockHint();
    });
</script>
@endpush
