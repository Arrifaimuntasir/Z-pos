

<?php $__env->startSection('title', 'Expenses'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="font-size: 24px; color: #0f172a;"><?php echo e(__('Expenses')); ?></h4>
        <p class="text-muted mb-0" style="font-size: 14px;"><?php echo e(__('Track and manage your business expenses')); ?></p>
    </div>
    <a href="<?php echo e(route('expenses.create')); ?>" class="btn btn-dark shadow-sm px-3" style="border-radius: 8px; font-weight: 500;">
        <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('New Expense')); ?>

    </a>
</div>

<div class="card border mb-4" style="border-radius: 12px; max-width: 280px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div class="card-body p-3 py-4">
        <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;"><?php echo e(__('TOTAL EXPENSES')); ?></div>
        <h3 class="fw-bold mb-0" style="color: #0f172a; font-size: 24px;"><?php echo e(number_format($totalExpenses, 2)); ?></h3>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0"><?php echo e(__('Expense List')); ?></h5>
        <form action="<?php echo e(route('expenses.index')); ?>" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="<?php echo e(__('Search expenses...')); ?>" value="<?php echo e(request('search')); ?>" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text"><?php echo e(__('Search')); ?></span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-muted table-light">
                    <tr>
                        <th><?php echo e(__('Date')); ?></th>
                        <th><?php echo e(__('Description')); ?></th>
                        <th><?php echo e(__('Category')); ?></th>
                        <th class="text-end"><?php echo e(__('Amount')); ?></th>
                        <th class="text-end"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-muted"><?php echo e(\Carbon\Carbon::parse($expense->expense_date)->format('M d, Y')); ?></td>
                        <td class="fw-semibold"><?php echo e($expense->description); ?></td>
                        <td>
                            <?php if($expense->category): ?>
                                <span class="badge bg-light text-dark border px-2 py-1"><?php echo e($expense->category); ?></span>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-bold text-danger"><?php echo e(number_format($expense->amount)); ?> TSh</td>
                        <td class="text-end">
                            <a href="<?php echo e(route('expenses.edit', $expense)); ?>" class="btn btn-sm btn-light text-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form action="<?php echo e(route('expenses.destroy', $expense)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                            <?php echo e(__('No expenses recorded yet.')); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-end">
            <?php echo e($expenses->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\expenses\index.blade.php ENDPATH**/ ?>