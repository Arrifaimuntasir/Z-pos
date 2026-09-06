

<?php $__env->startSection('title', 'Staff & Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Staff & Users')); ?></h4>
        <span class="text-muted small"><?php echo e(__('Manage people who have access to your shop')); ?></span>
    </div>
    <div>
        <a href="<?php echo e(route('staff.create')); ?>" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-person-plus me-2"></i> <?php echo e(__('Add Staff')); ?>

        </a>
    </div>
</div>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start py-3"><?php echo e(__('Name')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Email')); ?></th>
                        <th class="fw-medium border-0"><?php echo e(__('Role')); ?></th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <!-- Owner / Admin Row -->
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-circle fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo e(Auth::user()->name); ?> (You)</div>
                                    <div class="text-muted small"><?php echo e(__('Shop Owner')); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e(Auth::user()->email); ?></td>
                        <td><span class="badge bg-primary rounded-pill px-3"><?php echo e(__('Admin')); ?></span></td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('staff.edit', Auth::user()->id)); ?>" class="btn btn-sm btn-light text-primary shadow-sm rounded-3">
                                <i class="bi bi-pencil"></i> <?php echo e(__('Edit')); ?>

                            </a>
                        </td>
                    </tr>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo e($user->name); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <?php if($user->hasRole('Cashier')): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e(__('Cashier')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3"><?php echo e(__('Staff')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('staff.edit', $user->id)); ?>" class="btn btn-sm btn-light text-primary shadow-sm rounded-3 me-1">
                                <i class="bi bi-pencil"></i> <?php echo e(__('Edit')); ?>

                            </a>
                            <form action="<?php echo e(route('staff.destroy', $user->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to remove this user from your shop? They will no longer be able to log in.')">
                                    <i class="bi bi-trash"></i> <?php echo e(__('Remove')); ?>

                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 text-light-secondary mb-3 d-block"></i>
                            <h5><?php echo e(__('No other staff added yet')); ?></h5>
                            <p class="mb-0"><?php echo e(__('You can add staff like Cashiers to help you manage sales.')); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\staff\index.blade.php ENDPATH**/ ?>