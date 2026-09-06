

<?php $__env->startSection('title', 'Brands'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h2 class="fw-bold mb-0"><?php echo e(__('Brands')); ?></h2>
    <a href="<?php echo e(route('brands.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('Add Brand')); ?>

    </a>
</div>

<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-tags me-2"></i> <?php echo e(__('All Brands')); ?></span>
        <div class="search-box">
            <form action="<?php echo e(route('brands.index')); ?>" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="<?php echo e(__('Search brands...')); ?>" value="<?php echo e(request('search')); ?>" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text"><?php echo e(__('Search')); ?></span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-admin mb-0">
                <thead>
                    <tr>
                        <th><?php echo e(__('ID')); ?></th>
                        <th><?php echo e(__('Name')); ?></th>
                        <th><?php echo e(__('Description')); ?></th>
                        <th><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-bold text-muted">#<?php echo e(($brands->currentPage() - 1) * $brands->perPage() + $loop->iteration); ?></td>
                        <td class="fw-semibold"><?php echo e($brand->name); ?></td>
                        <td><?php echo e(Str::limit($brand->description, 50) ?: '-'); ?></td>
                        <td>
                            <a href="<?php echo e(route('brands.edit', $brand)); ?>" class="btn btn-sm btn-light text-primary me-1" title="<?php echo e(__('Edit')); ?>">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="<?php echo e(route('brands.destroy', $brand)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this brand?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-light text-danger" title="<?php echo e(__('Delete')); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-tags fs-1 d-block mb-3"></i>
                            <?php echo e(__('No brands found.')); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            <?php echo e($brands->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\brands\index.blade.php ENDPATH**/ ?>