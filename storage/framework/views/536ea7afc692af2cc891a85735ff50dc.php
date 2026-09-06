<?php $__env->startSection('title', 'Process Return'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Process Return')); ?></h4>
        <span class="text-muted small">Invoice #<?php echo e($sale->reference_no); ?></span>
    </div>
    <a href="<?php echo e(route('sales.show', $sale->id)); ?>" class="btn btn-light shadow-sm" style="border-radius: 8px;">
        <i class="bi bi-arrow-left me-2"></i> <?php echo e(__('Back to Invoice')); ?>

    </a>
</div>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<form action="<?php echo e(route('sales.returns.store', $sale->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><?php echo e(__('Select Items to Return')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="border-0 rounded-start"><?php echo e(__('Product')); ?></th>
                                    <th class="border-0"><?php echo e(__('Price')); ?></th>
                                    <th class="border-0"><?php echo e(__('Purchased')); ?></th>
                                    <th class="border-0"><?php echo e(__('Returned')); ?></th>
                                    <th class="border-0" style="width: 120px;"><?php echo e(__('Return Qty')); ?></th>
                                    <th class="border-0 rounded-end" style="width: 160px;"><?php echo e(__('Condition')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $available = $item->quantity - $item->returned_quantity;
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-medium text-dark"><?php echo e($item->product->name); ?></div>
                                        <?php if($item->imei_serial_number): ?>
                                            <small class="text-muted">IMEI: <?php echo e($item->imei_serial_number); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(number_format($item->unit_price, 2)); ?></td>
                                    <td><?php echo e($item->quantity); ?></td>
                                    <td><?php echo e($item->returned_quantity); ?></td>
                                    <td>
                                        <input type="hidden" name="items[<?php echo e($index); ?>][sale_item_id]" value="<?php echo e($item->id); ?>">
                                        <?php if($available > 0): ?>
                                            <input type="number" name="items[<?php echo e($index); ?>][return_quantity]" class="form-control form-control-sm" min="0" max="<?php echo e($available); ?>" value="0">
                                            <small class="text-muted">Max: <?php echo e($available); ?></small>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success">Fully Returned</span>
                                            <input type="hidden" name="items[<?php echo e($index); ?>][return_quantity]" value="0">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($available > 0): ?>
                                            <select name="items[<?php echo e($index); ?>][condition]" class="form-select form-select-sm">
                                                <option value="good"><?php echo e(__('Good (Add to Stock)')); ?></option>
                                                <option value="defective"><?php echo e(__('Defective (No Stock)')); ?></option>
                                            </select>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-4"><?php echo e(__('Return Details')); ?></h6>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium"><?php echo e(__('Return Date')); ?> <span class="text-danger">*</span></label>
                        <input type="date" name="return_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium"><?php echo e(__('Reason (Optional)')); ?></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="<?php echo e(__('Why is the customer returning this?')); ?>"></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-2 shadow-sm" style="border-radius: 8px;" onclick="return confirm('Are you sure you want to process this return?')">
                        <i class="bi bi-arrow-return-left me-2"></i> <?php echo e(__('Process Return')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\returns\create.blade.php ENDPATH**/ ?>