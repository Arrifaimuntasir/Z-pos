

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Suppliers')); ?></h4>
        <span class="text-muted small"><?php echo e(__('Manage your suppliers and distributors')); ?></span>
    </div>
    <div>
        <a href="<?php echo e(route('suppliers.create')); ?>" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> <?php echo e(__('Add Supplier')); ?>

        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;"><?php echo e(__('Supplier Name')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Contact Person')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Phone')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Email')); ?></th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-dark"><?php echo e($supplier->name); ?></div>
                        </td>
                        <td><?php echo e($supplier->contact_person ?? '-'); ?></td>
                        <td><?php echo e($supplier->phone ?? '-'); ?></td>
                        <td><?php echo e($supplier->email ?? '-'); ?></td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('suppliers.edit', $supplier->id)); ?>" class="btn btn-sm btn-light text-primary me-2 shadow-sm rounded-3">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('suppliers.destroy', $supplier->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to delete this supplier?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-truck fs-1 text-light-secondary"></i></div>
                            <h6 class="fw-bold"><?php echo e(__('No suppliers found')); ?></h6>
                            <p class="small mb-0"><?php echo e(__('Start by adding your first supplier.')); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\suppliers\index.blade.php ENDPATH**/ ?>