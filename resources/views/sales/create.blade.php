@extends('layouts.admin')

@section('title', 'Point of Sale (POS)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Point of Sale (POS)</h4>
        <span class="text-muted small">Record new sales</span>
    </div>
    <div>
        <a href="{{ route('sales.index') }}" class="btn btn-light px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-clock-history me-2"></i> Sales History
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="{{ route('sales.store') }}" method="POST" id="posForm">
    @csrf
    <div class="row g-4">
        <!-- POS Left Side (Products Selection) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Products List</h6>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <select id="productSelect" class="form-select border-0 shadow-sm bg-light" style="border-radius: 8px;">
                                <option value="">-- Search & Select Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-name="{{ $product->name }}" 
                                            data-price="{{ $product->selling_price }}"
                                            data-stock="{{ $product->stock }}">
                                        {{ $product->name }} (Stock: {{ $product->stock }}) - {{ number_format($product->selling_price) }} TSh
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="addItemBtn" class="btn btn-primary w-100" style="border-radius: 8px;">
                                <i class="bi bi-plus-lg"></i> Add to Cart
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle" id="cartTable">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="ps-3 border-0 rounded-start">Product</th>
                                    <th class="border-0 text-center" style="width: 120px;">Qty</th>
                                    <th class="border-0 text-end">Price</th>
                                    <th class="border-0 text-end">Total</th>
                                    <th class="border-0 rounded-end text-center" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="cartBody">
                                <tr id="emptyCartRow">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x fs-1 text-light-secondary mb-2 d-block"></i>
                                        <p class="mb-0">Cart is empty. Select products to sell.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- POS Right Side (Summary & Payment) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Sale Details</h6>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Reference / Receipt No.</label>
                        <input type="text" class="form-control bg-light border-0" value="{{ $reference_no }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Date</label>
                        <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Customer (Optional)</label>
                        <select name="customer_id" class="form-select">
                            <option value="">Walk-in Customer (None)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="border-light-secondary">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fw-medium">Subtotal</span>
                        <span class="fw-bold" id="cartSubtotal">0 TSh</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <h4 class="fw-bold mb-0">Total</h4>
                        <h4 class="fw-bold text-primary mb-0" id="cartTotal">0 TSh</h4>
                    </div>

                    <hr class="border-light-secondary">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Amount Paid (TSh) <span class="text-danger">*</span></label>
                        <input type="number" name="paid_amount" id="paidAmount" class="form-control form-control-lg fw-bold" value="0" min="0" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select">
                            <option value="Cash">Cash</option>
                            <option value="Mpesa">Mpesa / Mobile Money</option>
                            <option value="Bank">Bank Transfer</option>
                            <option value="Credit">Credit (Deni)</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any comments..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" style="border-radius: 10px;" id="submitBtn" disabled>
                        <i class="bi bi-check-circle me-2"></i> Complete Sale
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let cart = [];
    let itemIndex = 0;
    
    const productSelect = document.getElementById('productSelect');
    const addItemBtn = document.getElementById('addItemBtn');
    const cartBody = document.getElementById('cartBody');
    const emptyCartRow = document.getElementById('emptyCartRow');
    const cartSubtotalEl = document.getElementById('cartSubtotal');
    const cartTotalEl = document.getElementById('cartTotal');
    const paidAmountInput = document.getElementById('paidAmount');
    const submitBtn = document.getElementById('submitBtn');
    
    addItemBtn.addEventListener('click', function() {
        if(productSelect.value === "") return;
        
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const id = selectedOption.value;
        const name = selectedOption.getAttribute('data-name');
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        
        // Check if exists
        let existingItem = cart.find(i => i.id === id);
        if(existingItem) {
            if(existingItem.qty >= stock) {
                alert('Cannot add more than available stock!');
                return;
            }
            existingItem.qty++;
            document.getElementById('qty_' + id).value = existingItem.qty;
        } else {
            cart.push({ id: id, name: name, price: price, qty: 1, stock: stock, index: itemIndex });
            renderNewRow(id, name, price, stock, itemIndex);
            itemIndex++;
        }
        
        updateTotals();
        productSelect.value = ""; // Reset select
    });
    
    function renderNewRow(id, name, price, stock, index) {
        if(emptyCartRow) emptyCartRow.style.display = 'none';
        
        const tr = document.createElement('tr');
        tr.id = 'row_' + id;
        tr.innerHTML = `
            <td class="ps-3 fw-bold text-dark">
                ${name}
                <input type="hidden" name="items[${index}][product_id]" value="${id}">
            </td>
            <td class="text-center">
                <input type="number" name="items[${index}][quantity]" id="qty_${id}" class="form-control form-control-sm text-center qty-input" value="1" min="1" max="${stock}" data-id="${id}">
            </td>
            <td class="text-end">
                <input type="number" name="items[${index}][price]" class="form-control form-control-sm text-end price-input" value="${price}" min="0" data-id="${id}">
            </td>
            <td class="text-end fw-bold" id="total_${id}">
                ${price.toLocaleString()}
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-light text-danger remove-btn" data-id="${id}"><i class="bi bi-x-lg"></i></button>
            </td>
        `;
        cartBody.appendChild(tr);
        
        // Add event listeners for new inputs
        tr.querySelector('.qty-input').addEventListener('input', function(e) {
            let item = cart.find(i => i.id === id);
            let val = parseInt(this.value);
            if(val > stock) {
                alert('Cannot exceed available stock (' + stock + ')');
                this.value = stock;
                val = stock;
            }
            if(val < 1 || isNaN(val)) val = 1;
            item.qty = val;
            updateTotals();
        });
        
        tr.querySelector('.price-input').addEventListener('input', function(e) {
            let item = cart.find(i => i.id === id);
            let val = parseFloat(this.value);
            if(val < 0 || isNaN(val)) val = 0;
            item.price = val;
            updateTotals();
        });
        
        tr.querySelector('.remove-btn').addEventListener('click', function() {
            cart = cart.filter(i => i.id !== id);
            tr.remove();
            if(cart.length === 0) {
                emptyCartRow.style.display = 'table-row';
            }
            updateTotals();
        });
    }
    
    function updateTotals() {
        let total = 0;
        cart.forEach(item => {
            let itemTotal = item.price * item.qty;
            total += itemTotal;
            document.getElementById('total_' + item.id).innerText = itemTotal.toLocaleString();
        });
        
        cartSubtotalEl.innerText = total.toLocaleString() + ' TSh';
        cartTotalEl.innerText = total.toLocaleString() + ' TSh';
        
        // Auto-fill paid amount if they haven't manually changed it, or for convenience
        paidAmountInput.value = total;
        
        if(cart.length > 0) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
});
</script>
@endsection
