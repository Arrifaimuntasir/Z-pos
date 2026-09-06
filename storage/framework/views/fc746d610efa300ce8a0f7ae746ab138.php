

<?php $__env->startSection('title', 'Edit Shop'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">Edit Shop: <?php echo e($shop->name); ?></h4>
    
</div>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="<?php echo e(route('superadmin.shops.update', $shop)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted"><?php echo e(__('Shop Name')); ?></label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $shop->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Valid Until (Expiry Date)</label>
                        <input type="date" name="valid_until" class="form-control" value="<?php echo e(old('valid_until', $shop->valid_until ? \Carbon\Carbon::parse($shop->valid_until)->format('Y-m-d') : '')); ?>">
                        <?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted d-block mt-1"><?php echo e(__('Leave empty for unlimited access.')); ?></small>
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', $shop->is_active) ? 'checked' : ''); ?>>
                        <label class="form-check-label fw-semibold text-muted" for="is_active"><?php echo e(__('Shop is Active')); ?></label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-save me-1"></i> <?php echo e(__('Save Changes')); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\superadmin\shops\edit.blade.php ENDPATH**/ ?>