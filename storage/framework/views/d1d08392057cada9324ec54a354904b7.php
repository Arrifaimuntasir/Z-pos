

<?php $__env->startSection('title', 'Point of Sale (POS)'); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        color: #212529;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
<?php $__env->stopPush(); ?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Point of Sale (POS)')); ?></h4>
        <span class="text-muted small"><?php echo e(__('Record new sales')); ?></span>
    </div>
    <div>
        <a href="<?php echo e(route('sales.index')); ?>" class="btn btn-light px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-clock-history me-2"></i> <?php echo e(__('Sales History')); ?>

        </a>
    </div>
</div>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if($errors->any()): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i><strong>Validation Error:</strong>
    <ul class="mb-0 mt-2">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($err); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<form action="<?php echo e(route('sales.store')); ?>" method="POST" id="posForm">
    <?php echo csrf_field(); ?>
    <div class="row g-4">
        <!-- POS Left Side (Products Selection) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
                        <h6 class="fw-bold mb-0"><?php echo e(__('Products List')); ?></h6>
                    </div>
                    
                    <?php
                        $shopCategory = Auth::user()->shop->business_type;
                        $showExpiry = in_array($shopCategory, ['Pharmacy / Health', 'Supermarket / Grocery', 'Restaurant / Food']);
                        $showImei = in_array($shopCategory, ['Electronics / IT']);
                        $isRestaurant = $shopCategory === 'Restaurant / Food';
                    ?>

                    <?php if($isRestaurant): ?>
                        <div class="restaurant-pos-grid mb-4" style="max-height: 500px; overflow-y: auto; padding-right: 10px;">
                            <?php
                                $groupedProducts = $products->groupBy(function($p) {
                                    return $p->category ? $p->category->name : 'Other';
                                });
                            ?>
                            
                            <?php $__currentLoopData = $groupedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryName => $catProducts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-4">
                                    <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2"><?php echo e($categoryName); ?></h6>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <?php $__currentLoopData = $catProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button" class="btn btn-outline-primary p-3 text-start product-btn position-relative" style="width: 140px; height: 100px; border-radius: 12px; transition: all 0.2s;"
                                                data-id="<?php echo e($p->id); ?>"
                                                data-name="<?php echo e($p->name); ?>"
                                                data-price="<?php echo e($p->selling_price); ?>"
                                                data-stock="<?php echo e($p->track_stock ? $p->stock : 999999); ?>"
                                                data-requires-imei="false"
                                                data-expiry=""
                                                data-is-expired="false">
                                                <div class="fw-bold text-truncate" style="font-size: 0.9rem;"><?php echo e($p->name); ?></div>
                                                <div class="small fw-bold text-success position-absolute bottom-0 start-0 m-2"><?php echo e(number_format($p->selling_price)); ?></div>
                                            </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="row mb-3">
                            <div class="col-12 col-md-8 mb-2 mb-md-0">
                                <select id="productSelect" class="form-select border-0 shadow-sm bg-light" style="border-radius: 8px;">
                                    <option value=""><?php echo e(__('-- Search & Select Product --')); ?></option>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($product->id); ?>" 
                                                data-name="<?php echo e($product->name); ?>" 
                                                data-price="<?php echo e($product->selling_price); ?>"
                                                data-stock="<?php echo e($product->track_stock ? $product->stock : 999999); ?>"
                                                data-requires-imei="<?php echo e(($showImei && $product->requires_imei) ? 'true' : 'false'); ?>"
                                                data-expiry="<?php echo e(($showExpiry && $product->expiry_date) ? \Carbon\Carbon::parse($product->expiry_date)->format('M d, Y') : ''); ?>"
                                                data-is-expired="<?php echo e(($showExpiry && $product->expiry_date && \Carbon\Carbon::parse($product->expiry_date)->isPast()) ? 'true' : 'false'); ?>">
                                            <?php echo e($product->name); ?> (Stock: <?php echo e($product->track_stock ? $product->stock : 'N/A'); ?>) - <?php echo e(number_format($product->selling_price)); ?> TSh
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="button" id="addItemBtn" class="btn btn-primary w-100" style="border-radius: 8px;">
                                    <i class="bi bi-plus-lg"></i> <?php echo e(__('Add to Cart')); ?>

                                </button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table align-middle" id="cartTable">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="ps-3 border-0 rounded-start" style="min-width: 250px;"><?php echo e(__('Product')); ?></th>
                                    <th class="border-0 text-center" style="min-width: 120px;"><?php echo e(__('Qty')); ?></th>
                                    <th class="border-0 text-end" style="min-width: 120px;"><?php echo e(__('Price')); ?></th>
                                    <th class="border-0 text-end" style="min-width: 120px;"><?php echo e(__('Total')); ?></th>
                                    <th class="border-0 rounded-end text-center" style="min-width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="cartBody">
                                <tr id="emptyCartRow">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x fs-1 text-light-secondary mb-2 d-block"></i>
                                        <p class="mb-0"><?php echo e(__('Cart is empty. Select products to sell.')); ?></p>
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
                    <h6 class="fw-bold mb-4"><?php echo e(__('Sale Details')); ?></h6>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium"><?php echo e(__('Reference / Receipt No.')); ?></label>
                        <input type="text" class="form-control bg-light border-0" value="<?php echo e($reference_no); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium"><?php echo e(__('Date')); ?></label>
                        <input type="date" name="sale_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium"><?php echo e(__('Customer (Optional)')); ?></label>
                        <select name="customer_id" class="form-select">
                            <option value=""><?php echo e(__('Walk-in Customer (None)')); ?></option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <hr class="border-light-secondary">




                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted fw-medium"><?php echo e(__('Subtotal')); ?></span>
                        <span class="fw-bold" id="cartSubtotal">0 TSh</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <h4 class="fw-bold mb-0"><?php echo e(__('Total')); ?></h4>
                        <h4 class="fw-bold text-primary mb-0" id="cartTotal">0 TSh</h4>
                    </div>

                    <hr class="border-light-secondary">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark"><?php echo e(__('Amount Paid')); ?> (TSh) <span class="text-danger">*</span></label>
                        <input type="number" name="paid_amount" id="paidAmount" class="form-control form-control-lg fw-bold" value="0" min="0" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium"><?php echo e(__('Payment Method')); ?> <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select">
                            <option value="Cash"><?php echo e(__('Cash')); ?></option>
                            <option value="Mpesa">Mpesa / Mobile Money</option>
                            <option value="Bank"><?php echo e(__('Bank Transfer')); ?></option>
                            <option value="Credit">Credit (Deni)</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="<?php echo e(__('Any comments...')); ?>"></textarea>
                    </div>

                    <button type="submit" name="action" value="sale" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" style="border-radius: 10px;" id="submitBtn" disabled>
                        <i class="bi bi-check-circle me-2"></i> <?php echo e(__('Complete Sale')); ?>

                    </button>
                    <button type="submit" name="action" value="proforma" class="btn btn-outline-primary btn-lg w-100 fw-bold shadow-sm mt-2" style="border-radius: 10px;" id="proformaBtn" disabled>
                        <i class="bi bi-file-earmark-text me-2"></i> <?php echo e(__('Save as Pro-forma')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#productSelect').select2({
        placeholder: "-- Search & Select Product --",
        allowClear: true,
        width: '100%'
    });
    
    // Also initialize customer select if you want
    $('select[name="customer_id"]').select2({
        placeholder: "Walk-in Customer (None)",
        allowClear: true,
        width: '100%'
    });
});

document.addEventListener('DOMContentLoaded', function() {
    let cart = [];
    let itemIndex = 0;
    
    // Since Select2 overrides the standard DOM select, we need to listen to its event if we want instant add, 
    // but the user clicks "Add to Cart" anyway. So we just need to get value from the original select.
    
    const productSelect = document.getElementById('productSelect');
    const addItemBtn = document.getElementById('addItemBtn');
    const cartBody = document.getElementById('cartBody');
    const emptyCartRow = document.getElementById('emptyCartRow');
    const cartSubtotalEl = document.getElementById('cartSubtotal');
    const cartTotalEl = document.getElementById('cartTotal');
    const paidAmountInput = document.getElementById('paidAmount');
    const submitBtn = document.getElementById('submitBtn');
    
    // Restaurant Grid Clicks
    document.querySelectorAll('.product-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const stock = parseInt(this.getAttribute('data-stock'));
            
            // Add a little visual feedback effect
            this.classList.add('bg-primary', 'text-white');
            setTimeout(() => {
                this.classList.remove('bg-primary', 'text-white');
            }, 150);

            addToCart(id, name, price, stock, false, '', false);
        });
    });

    if(addItemBtn) {
        addItemBtn.addEventListener('click', function() {
            if(productSelect.value === "") return;
            
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const id = selectedOption.value;
            const name = selectedOption.getAttribute('data-name');
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            const stock = parseInt(selectedOption.getAttribute('data-stock'));
            const requiresImei = selectedOption.getAttribute('data-requires-imei') === 'true';
            const expiry = selectedOption.getAttribute('data-expiry');
            const isExpired = selectedOption.getAttribute('data-is-expired') === 'true';
            
            addToCart(id, name, price, stock, requiresImei, expiry, isExpired);
            
            // Reset select2
            $('#productSelect').val(null).trigger('change');
        });
    }

    function addToCart(id, name, price, stock, requiresImei, expiry, isExpired) {
        if(isExpired) {
            if(!confirm('WARNING: This product is expired (' + expiry + '). Are you sure you want to sell it?')) {
                return;
            }
        }
        
        // Check if exists
        let existingItem = cart.find(i => i.id === id);
        if(existingItem) {
            if(existingItem.qty >= stock) {
                alert('Cannot add more than available stock!');
                return;
            }
            existingItem.qty++;
            let qtyInput = document.getElementById('qty_' + id);
            if(qtyInput) qtyInput.value = existingItem.qty;
        } else {
            cart.push({ id: id, name: name, price: price, qty: 1, stock: stock, index: itemIndex, requiresImei: requiresImei, expiry: expiry, isExpired: isExpired });
            renderNewRow(id, name, price, stock, itemIndex, requiresImei, expiry, isExpired);
            itemIndex++;
        }
        
        updateTotals();
    }
    
    function renderNewRow(id, name, price, stock, index, requiresImei, expiry, isExpired) {
        if(emptyCartRow) emptyCartRow.style.display = 'none';
        
        let extraInfo = '';
        if(expiry) {
            extraInfo += `<br><small class="${isExpired ? 'text-danger fw-bold' : 'text-warning'}">Exp: ${expiry}</small>`;
        }
        if(requiresImei) {
            extraInfo += `<div class="mt-2"><input type="text" name="items[${index}][imei]" class="form-control border-primary" style="min-width: 180px; padding: 0.5rem;" placeholder="Enter IMEI / Serial Number" required></div>`;
        }

        const tr = document.createElement('tr');
        tr.id = 'row_' + id;
        tr.innerHTML = `
            <td class="ps-3 fw-bold text-dark">
                ${name}
                ${extraInfo}
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
            document.getElementById('proformaBtn').disabled = false;
        } else {
            submitBtn.disabled = true;
            document.getElementById('proformaBtn').disabled = true;
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\sales\create.blade.php ENDPATH**/ ?>