

<?php $__env->startSection('title', 'Expenses Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><?php echo e(__('Expenses Report')); ?></h3>
        <p class="text-muted small mb-0"><?php echo e(__('Detailed expense history')); ?></p>
    </div>
    <div>
        <div class="card bg-danger text-white px-4 py-2 border-0 shadow-sm rounded-pill">
            <span class="small fw-semibold">Total Expenses: <?php echo e(number_format($totalExpenses)); ?> TSh</span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="<?php echo e(route('reports.expenses')); ?>" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label"><?php echo e(__('Start Date')); ?></label>
                <input type="date" name="start_date" class="form-control" value="<?php echo e($startDate); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label"><?php echo e(__('End Date')); ?></label>
                <input type="date" name="end_date" class="form-control" value="<?php echo e($endDate); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-funnel me-2"></i> <?php echo e(__('Filter')); ?></button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start"><?php echo e(__('Date')); ?></th>
                        <th class="border-0 fw-semibold"><?php echo e(__('Category')); ?></th>
                        <th class="border-0 fw-semibold"><?php echo e(__('Description')); ?></th>
                        <th class="border-0 fw-semibold text-end pe-4 rounded-end"><?php echo e(__('Amount')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4"><?php echo e(\Carbon\Carbon::parse($expense->expense_date)->format('M d, Y')); ?></td>
                            <td><span class="fw-medium"><?php echo e($expense->category); ?></span></td>
                            <td><?php echo e($expense->description ?? '-'); ?></td>
                            <td class="text-end pe-4 fw-bold text-danger"><?php echo e(number_format($expense->amount)); ?> TSh</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-graph-down-arrow fs-1 mb-3 d-block"></i>
                                <?php echo e(__('No expenses found for the selected period.')); ?>

                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\reports\expenses.blade.php ENDPATH**/ ?>