<?php $__env->startSection('title', 'Return Invoices'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Return Invoices')); ?></h4>
        <span class="text-muted small"><?php echo e(__('Manage returned items and print return receipts')); ?></span>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0 text-dark fw-bold"><?php echo e(__('Recent Returns')); ?></h5>
        <form action="<?php echo e(route('returns.index')); ?>" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="<?php echo e(__('Search by Ref...')); ?>" value="<?php echo e(request('search')); ?>" style="font-size: 0.95rem; height: 42px;">
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
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start" style="min-width: 120px;"><?php echo e(__('Date')); ?></th>
                        <th class="border-0 fw-semibold" style="min-width: 150px;"><?php echo e(__('Return Ref')); ?></th>
                        <th class="border-0 fw-semibold" style="min-width: 150px;"><?php echo e(__('Invoice Ref')); ?></th>
                        <th class="border-0 fw-semibold" style="min-width: 150px;"><?php echo e(__('Customer')); ?></th>
                        <th class="border-0 fw-semibold" style="min-width: 200px;"><?php echo e(__('Items')); ?></th>
                        <th class="border-0 fw-semibold text-end" style="min-width: 150px;"><?php echo e(__('Refund Amount')); ?></th>
                        <th class="border-0 fw-semibold text-center" style="min-width: 100px;"><?php echo e(__('Print PDF')); ?></th>
                        <th class="pe-4 border-0 fw-semibold text-center rounded-end" style="min-width: 100px;"><?php echo e(__('View Sale')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rtn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <?php echo e(\Carbon\Carbon::parse($rtn->return_date)->format('M d, Y')); ?>

                        </td>
                        <td>
                            <span class="fw-medium text-dark"><?php echo e($rtn->reference_no); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('sales.show', $rtn->sale_id)); ?>" class="text-decoration-none fw-medium text-primary"><?php echo e($rtn->sale->reference_no); ?></a>
                        </td>
                        <td>
                            <?php if($rtn->sale->customer): ?>
                                <?php echo e($rtn->sale->customer->name); ?>

                            <?php else: ?>
                                <span class="text-muted fst-italic"><?php echo e(__('Walk-in Customer')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="small fw-medium text-dark">
                                <?php $__currentLoopData = $rtn->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <?php echo e($item->saleItem && $item->saleItem->product ? $item->saleItem->product->name : 'Unknown'); ?> (<?php echo e($item->quantity); ?> <?php echo e($item->saleItem && $item->saleItem->product && $item->saleItem->product->unit ? $item->saleItem->product->unit->short_name : ''); ?>)
                                        <?php if($item->condition === 'good'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2" style="font-size: 0.65rem;"><?php echo e(__('Good')); ?></span>
                                        <?php elseif($item->condition === 'defective'): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2" style="font-size: 0.65rem;"><?php echo e(__('Defective')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </span>
                        </td>
                        <td class="text-end fw-bold text-danger">
                            <?php echo e(number_format($rtn->total_refund)); ?> TSh
                        </td>
                        <td class="text-center">
                            <a href="<?php echo e(route('returns.pdf', $rtn->id)); ?>" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;" title="<?php echo e(__('Print Invoice')); ?>">
                                <i class="bi bi-file-earmark-pdf fs-5"></i>
                            </a>
                        </td>
                        <td class="pe-4 text-center">
                            <a href="<?php echo e(route('sales.show', $rtn->sale_id)); ?>" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;" title="<?php echo e(__('View Sale')); ?>">
                                <i class="bi bi-eye fs-5"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-arrow-return-left fs-1 text-light-secondary mb-3 d-block"></i>
                            <h5><?php echo e(__('No returns found')); ?></h5>
                            <p class="mb-4"><?php echo e(__('You do not have any returns or refunds yet.')); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            <?php echo e($returns->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\returns\index.blade.php ENDPATH**/ ?>