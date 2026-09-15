@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Edit Purchase') }}</h4>
        <span class="text-muted small">{{ __('Update this stock purchase') }}</span>
    </div>
    <div>

    </div>
</div>

<form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">{{ __('Purchase Items') }}</h6>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle" id="itemsTable">
                            <thead class="bg-light">
                                <tr>
                                    <th style="min-width: 200px;">{{ __('Product') }}</th>
                                    <th style="min-width: 120px;">{{ __('Unit Cost') }}</th>
                                    <th style="min-width: 100px;">{{ __('Quantity') }}</th>
                                    <th style="min-width: 150px;">{{ __('Subtotal') }}</th>
                                    <th style="min-width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $item)
                                <tr class="item-row">
                                    <td>
                                        <select name="product_id[]" class="form-select product-select" required>
                                            <option value="">{{ __('Select Product...') }}</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-cost="{{ $product->cost_price }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>{{ $product->name }}{{ $product->model ? ' - ' . $product->model : '' }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="unit_cost[]" class="form-control unit-cost" step="0.01" min="0" value="{{ $item->unit_cost }}" required>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" class="form-control qty" min="1" value="{{ $item->quantity }}" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal" readonly value="{{ number_format($item->subtotal, 2) }}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-item"><i class="bi bi-x"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                                            <i class="bi bi-plus"></i> {{ __('Add Another Item') }}
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-5 text-end">
                            <h5 class="fw-bold text-dark">{{ __('Total:') }} <span id="grandTotal">{{ number_format($purchase->total_amount, 2) }}</span></h5>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">{{ __('Notes') }}</h6>
                    <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('Any additional notes about this purchase...') }}">{{ $purchase->notes }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">{{ __('Purchase Info') }}</h6>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">{{ __('Supplier') }} <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select Supplier...') }}</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $supplier->id == $purchase->supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">{{ __('Reference No') }}</label>
                        <input type="text" class="form-control" value="{{ $purchase->reference_no }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">{{ __('Date') }} <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') }}" required>
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">{{ __('Status') }} <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="completed" {{ $purchase->status == 'completed' ? 'selected' : '' }}>{{ __('Completed (Adds to Stock)') }}</option>
                            <option value="pending" {{ $purchase->status == 'pending' ? 'selected' : '' }}>{{ __('Pending (Ordered)') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" style="border-radius: 8px;">
                        {{ __('Update Purchase') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('#itemsTable tbody');
    const addItemBtn = document.getElementById('addItemBtn');

    function calculateRow(row) {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const cost = parseFloat(row.querySelector('.unit-cost').value) || 0;
        const subtotal = qty * cost;
        row.querySelector('.subtotal').value = subtotal.toFixed(2);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grandTotal').innerText = total.toFixed(2);
    }

    addItemBtn.addEventListener('click', function() {
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.unit-cost').value = '';
        newRow.querySelector('.qty').value = '1';
        newRow.querySelector('.subtotal').value = '0.00';

        tableBody.appendChild(newRow);
        attachEvents(newRow);
    });

    function attachEvents(row) {
        row.querySelector('.qty').addEventListener('input', () => calculateRow(row));
        row.querySelector('.unit-cost').addEventListener('input', () => calculateRow(row));

        row.querySelector('.product-select').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const cost = selected ? selected.getAttribute('data-cost') : null;
            if (cost !== null && cost !== '') {
                row.querySelector('.unit-cost').value = parseFloat(cost).toFixed(2);
                calculateRow(row);
            }
        });

        row.querySelector('.remove-item').addEventListener('click', function() {
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                calculateTotal();
            } else {
                alert('You must have at least one item.');
            }
        });
    }

    document.querySelectorAll('.item-row').forEach(row => attachEvents(row));
});
</script>
@endsection
