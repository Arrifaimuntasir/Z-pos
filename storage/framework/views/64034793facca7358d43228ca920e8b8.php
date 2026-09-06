

<?php $__env->startSection('title', __('Defective Items')); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            <i class="bi bi-exclamation-triangle-fill text-danger me-2" style="font-size: 1.1rem;"></i>
            <?php echo e(__('Defective Items')); ?>

        </h4>
        <span class="text-muted small"><?php echo e(__('Products returned as defective — not restocked')); ?></span>
    </div>

</div>


<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-3 px-2" style="border-radius: 14px; border-left: 4px solid #ef4444 !important;">
            <div class="mb-1">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:42px;height:42px;">
                    <i class="bi bi-box-seam text-danger fs-5"></i>
                </span>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e(number_format($totalDefective)); ?></div>
            <div class="text-muted small"><?php echo e(__('Total Defective Units')); ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center py-3 px-2" style="border-radius: 14px; border-left: 4px solid #f97316 !important;">
            <div class="mb-1">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10" style="width:42px;height:42px;">
                    <i class="bi bi-cash-stack text-warning fs-5"></i>
                </span>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e(number_format($totalLostValue)); ?> TSh</div>
            <div class="text-muted small"><?php echo e(__('Estimated Loss (Cost)')); ?></div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <h5 class="mb-0 fw-bold text-dark"><?php echo e(__('Defective Returns List')); ?></h5>
        <form action="<?php echo e(route('returns.defective')); ?>" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="<?php echo e(__('Search product or ref...')); ?>" value="<?php echo e(request('search')); ?>" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text"><?php echo e(__('Search')); ?></span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 border-0 fw-semibold py-3 rounded-start"><?php echo e(__('Date')); ?></th>
                        <th class="border-0 fw-semibold"><?php echo e(__('Return Ref')); ?></th>
                        <th class="border-0 fw-semibold"><?php echo e(__('Product Name')); ?></th>
                        <th class="border-0 fw-semibold text-center"><?php echo e(__('Qty')); ?></th>
                        <th class="border-0 fw-semibold text-end"><?php echo e(__('Unit Price')); ?></th>
                        <th class="border-0 fw-semibold text-end"><?php echo e(__('Refund Given')); ?></th>
                        <th class="pe-4 border-0 fw-semibold text-center rounded-end"><?php echo e(__('Action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $query; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4"><?php echo e($item->saleReturn ? \Carbon\Carbon::parse($item->saleReturn->return_date)->format('M d, Y') : '-'); ?></td>
                        <td><span class="fw-medium text-dark"><?php echo e($item->saleReturn->reference_no ?? '-'); ?></span></td>
                        <td>
                            <div class="search-toolbar">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:32px;height:32px;flex-shrink:0;">
                                    <i class="bi bi-exclamation-circle text-danger" style="font-size:0.85rem;"></i>
                                </span>
                                <div>
                                    <div class="fw-medium text-dark"><?php echo e($item->product->name ?? __('Unknown Product')); ?></div>
                                    <?php if($item->saleItem && $item->saleItem->imei_serial_number): ?>
                                    <small class="text-muted">IMEI: <?php echo e($item->saleItem->imei_serial_number); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill fw-bold"><?php echo e($item->quantity); ?></span>
                        </td>
                        <td class="text-end text-muted"><?php echo e($item->saleItem ? number_format($item->saleItem->unit_price) . ' TSh' : '-'); ?></td>
                        <td class="text-end fw-bold text-danger"><?php echo e(number_format($item->refund_amount)); ?> TSh</td>
                        <td class="pe-4 text-center">
                            <?php if($item->saleReturn && $item->saleReturn->sale_id): ?>
                            <a href="<?php echo e(route('sales.show', $item->saleReturn->sale_id)); ?>" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;" title="<?php echo e(__('View Invoice')); ?>">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle fs-1 text-success mb-3 d-block"></i>
                            <h5><?php echo e(__('No defective items found')); ?></h5>
                            <p class="mb-0"><?php echo e(__('All returned products are in good condition.')); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            <?php echo e($query->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\returns\defective.blade.php ENDPATH**/ ?>