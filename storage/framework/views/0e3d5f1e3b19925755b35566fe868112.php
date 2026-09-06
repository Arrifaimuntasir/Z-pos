<?php $__env->startSection('title', 'Manage Branches'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Branches')); ?></h4>
        <span class="text-muted small">Manage your shop's physical locations</span>
    </div>
    <?php if(Auth::user()->shop->package !== 'starter'): ?>
    <div>
        <a href="<?php echo e(route('branches.create')); ?>" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> <?php echo e(__('Add Branch')); ?>

        </a>
    </div>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start py-3"><?php echo e(__('Branch Name')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Address')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Contact')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Status')); ?></th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-shop fs-5"></i>
                                </div>
                                <div class="fw-bold text-dark">
                                    <?php echo e($branch->name); ?>

                                    <?php if($branch->name === 'Main Branch'): ?>
                                        <span class="badge bg-secondary ms-1" style="font-size: 10px;"><?php echo e(__('Default')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($branch->address ?? '-'); ?></td>
                        <td>
                            <?php if($branch->phone): ?>
                                <div><i class="bi bi-telephone text-muted me-1"></i> <?php echo e($branch->phone); ?></div>
                            <?php endif; ?>
                            <?php if($branch->email): ?>
                                <div><i class="bi bi-envelope text-muted me-1"></i> <?php echo e($branch->email); ?></div>
                            <?php endif; ?>
                            <?php if(!$branch->phone && !$branch->email): ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($branch->is_active): ?>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 rounded-pill"><?php echo e(__('Active')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 rounded-pill"><?php echo e(__('Inactive')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('branches.edit', $branch->id)); ?>" class="btn btn-sm btn-light text-primary shadow-sm rounded-3">
                                <i class="bi bi-pencil"></i> <?php echo e(__('Edit')); ?>

                            </a>
                            <form action="<?php echo e(route('branches.destroy', $branch->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3 ms-1" onclick="return confirm('Are you sure you want to delete this branch? This action cannot be undone.')">
                                    <i class="bi bi-trash"></i> <?php echo e(__('Delete')); ?>

                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-shop fs-1 text-light-secondary mb-3 d-block"></i>
                            <h5><?php echo e(__('No branches found')); ?></h5>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\branches\index.blade.php ENDPATH**/ ?>