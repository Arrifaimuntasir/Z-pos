

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Add Purchase')); ?></h4>
        <span class="text-muted small"><?php echo e(__('Record new stock intake')); ?></span>
    </div>
    <div>
        
    </div>
</div>

<form action="<?php echo e(route('purchases.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    
    <div class="row g-4">
        <!-- Main Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4"><?php echo e(__('Purchase Items')); ?></h6>
                    
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle" id="itemsTable">
                            <thead class="bg-light">
                                <tr>
                                    <th style="min-width: 200px;"><?php echo e(__('Product')); ?></th>
                                    <th style="min-width: 120px;"><?php echo e(__('Unit Cost')); ?></th>
                                    <th style="min-width: 100px;"><?php echo e(__('Quantity')); ?></th>
                                    <th style="min-width: 150px;"><?php echo e(__('Subtotal')); ?></th>
                                    <th style="min-width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select name="product_id[]" class="form-select product-select" required>
                                            <option value=""><?php echo e(__('Select Product...')); ?></option>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="unit_cost[]" class="form-control unit-cost" step="0.01" min="0" required>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" class="form-control qty" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal" readonly value="0.00">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-item"><i class="bi bi-x"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                                            <i class="bi bi-plus"></i> <?php echo e(__('Add Another Item')); ?>

                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="row justify-content-end">
                        <div class="col-md-5 text-end">
                            <h5 class="fw-bold text-dark"><?php echo e(__('Total:')); ?> <span id="grandTotal">0.00</span></h5>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><?php echo e(__('Notes')); ?></h6>
                    <textarea name="notes" class="form-control" rows="3" placeholder="<?php echo e(__('Any additional notes about this purchase...')); ?>"></textarea>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Details -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4"><?php echo e(__('Purchase Info')); ?></h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark"><?php echo e(__('Supplier')); ?> <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value=""><?php echo e(__('Select Supplier...')); ?></option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark"><?php echo e(__('Reference No')); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="reference_no" class="form-control <?php $__errorArgs = ['reference_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('reference_no', $reference_no)); ?>" readonly required>
                        <?php $__errorArgs = ['reference_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark"><?php echo e(__('Date')); ?> <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control <?php $__errorArgs = ['purchase_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(date('Y-m-d')); ?>" required>
                        <?php $__errorArgs = ['purchase_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark"><?php echo e(__('Status')); ?> <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="completed">Completed (Adds to Stock)</option>
                            <option value="pending">Pending (Ordered)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" style="border-radius: 8px;">
                        <?php echo e(__('Save Purchase')); ?>

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
    
    // Calculate row subtotal
    function calculateRow(row) {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const cost = parseFloat(row.querySelector('.unit-cost').value) || 0;
        const subtotal = qty * cost;
        row.querySelector('.subtotal').value = subtotal.toFixed(2);
        calculateTotal();
    }
    
    // Calculate grand total
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grandTotal').innerText = total.toFixed(2);
    }
    
    // Add new row
    addItemBtn.addEventListener('click', function() {
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Clear values
        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.unit-cost').value = '';
        newRow.querySelector('.qty').value = '1';
        newRow.querySelector('.subtotal').value = '0.00';
        
        tableBody.appendChild(newRow);
        attachEvents(newRow);
    });
    
    // Attach events to a row
    function attachEvents(row) {
        row.querySelector('.qty').addEventListener('input', () => calculateRow(row));
        row.querySelector('.unit-cost').addEventListener('input', () => calculateRow(row));
        
        row.querySelector('.remove-item').addEventListener('click', function() {
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                calculateTotal();
            } else {
                alert('You must have at least one item.');
            }
        });
    }
    
    // Initial attach
    document.querySelectorAll('.item-row').forEach(row => attachEvents(row));
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\purchases\create.blade.php ENDPATH**/ ?>