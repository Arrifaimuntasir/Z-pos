

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Units (Vipimo)</h4>
        <span class="text-muted small"><?php echo e(__('Manage how your products are measured')); ?></span>
    </div>
    <div>
        <a href="<?php echo e(route('units.create')); ?>" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> <?php echo e(__('Add Unit')); ?>

        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0 text-dark fw-bold"><?php echo e(__('All Units')); ?></h5>
        <form action="<?php echo e(route('units.index')); ?>" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="<?php echo e(__('Search units...')); ?>" value="<?php echo e(request('search')); ?>" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text"><?php echo e(__('Search')); ?></span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-medium border-0 rounded-start" style="padding-top: 15px; padding-bottom: 15px;">Name (e.g. Pieces)</th>
                        <th class="fw-medium border-0">Short Name (e.g. Pcs)</th>
                        <th class="fw-medium border-0">Allow Decimal (Desimali)</th>
                        <th class="text-end pe-4 fw-medium border-0 rounded-end"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-dark"><?php echo e($unit->name); ?></div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo e($unit->short_name); ?></span></td>
                        <td>
                            <?php if($unit->allow_decimal): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e(__('Yes')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3"><?php echo e(__('No')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('units.edit', $unit->id)); ?>" class="btn btn-sm btn-light text-primary me-2 shadow-sm rounded-3">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('units.destroy', $unit->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3" onclick="return confirm('Are you sure you want to delete this unit?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-rulers fs-1 text-light-secondary mb-3 d-block"></i>
                            <h5><?php echo e(__('No units found')); ?></h5>
                            <p class="mb-0">Please add some units (like Pcs, Kg) first.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            <?php echo e($units->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\units\index.blade.php ENDPATH**/ ?>