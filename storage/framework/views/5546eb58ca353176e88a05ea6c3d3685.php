

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Purchases')); ?></h4>
        <span class="text-muted small"><?php echo e(__('Manage your stock purchases and inventory intake')); ?></span>
    </div>
    <div>
        <a href="<?php echo e(route('purchases.create')); ?>" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> <?php echo e(__('Add Purchase')); ?>

        </a>
    </div>
</div>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;"><?php echo e(__('Date')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Reference No')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Supplier')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Status')); ?></th>
                        <th class="fw-medium border-0 text-end"><?php echo e(__('Total Amount')); ?></th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 py-3"><?php echo e(\Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y')); ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo e($purchase->reference_no); ?></span></td>
                        <td>
                            <div class="fw-bold text-dark"><?php echo e($purchase->supplier->name ?? 'Unknown'); ?></div>
                        </td>
                        <td>
                            <?php if($purchase->status == 'completed'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><?php echo e(__('Completed')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><?php echo e(__('Pending')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-bold"><?php echo e(number_format($purchase->total_amount, 2)); ?></td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('purchases.show', $purchase->id)); ?>" class="btn btn-sm btn-light text-primary me-2 shadow-sm rounded-3">
                                <i class="bi bi-eye"></i> <?php echo e(__('View')); ?>

                            </a>
                            <form action="<?php echo e(route('purchases.destroy', $purchase->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to delete this purchase?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-cart-plus fs-1 text-light-secondary"></i></div>
                            <h6 class="fw-bold"><?php echo e(__('No purchases found')); ?></h6>
                            <p class="small mb-0"><?php echo e(__('Start by adding your first stock purchase.')); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\purchases\index.blade.php ENDPATH**/ ?>