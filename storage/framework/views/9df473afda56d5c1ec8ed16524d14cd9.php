

<?php $__env->startSection('title', 'Edit Expense'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0"><?php echo e(__('Edit Expense')); ?></h4>
    
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="<?php echo e(route('expenses.update', $expense)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted"><?php echo e(__('Expense Description')); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="description" class="form-control form-control-lg bg-light border-0" value="<?php echo e(old('description', $expense->description)); ?>" placeholder="<?php echo e(__('e.g. Electricity Bill, Transport...')); ?>" required>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Amount (TSh) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control form-control-lg bg-light border-0 fw-bold text-primary" value="<?php echo e(old('amount', $expense->amount)); ?>" required min="0">
                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mt-4 mt-md-0">
                            <label class="form-label fw-semibold text-muted"><?php echo e(__('Date')); ?> <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control form-control-lg bg-light border-0" value="<?php echo e(old('expense_date', $expense->expense_date)); ?>" required>
                            <?php $__errorArgs = ['expense_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold text-muted">Category (Optional)</label>
                        <input type="text" name="category" class="form-control bg-light border-0" value="<?php echo e(old('category', $expense->category)); ?>" placeholder="<?php echo e(__('e.g. Utilities, Operations, Office')); ?>">
                        <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> <?php echo e(__('Update Expense')); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\expenses\edit.blade.php ENDPATH**/ ?>