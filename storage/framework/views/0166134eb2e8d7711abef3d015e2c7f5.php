

<?php $__env->startSection('title', __('Warranties')); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: #0f172a;"><?php echo e(__('Warranties')); ?></h2>
        <p class="text-muted small mb-0" style="font-size: 14px;"><?php echo e(__('Manage customer warranties')); ?></p>
    </div>
    <div>
        <a href="<?php echo e(route('warranties.create')); ?>" class="btn fw-bold shadow-sm rounded-pill px-4 py-2" style="background-color: #0f172a; color: white;">
            <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('Generate Warranty')); ?>

        </a>
    </div>
</div>

<!-- Metrics Dashboard -->
<!-- Metrics Dashboard -->
<div class="row mb-5 g-4">
    <div class="col-md-4">
        <div class="card border shadow-sm rounded-4 h-100" style="border-color: #e2e8f0 !important;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #f1f5f9; color: #475569;">
                    <i class="bi bi-shield-check fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1" style="font-size: 14px; font-weight: 500;"><?php echo e(__('Total Generated')); ?></h6>
                    <h2 class="fw-bold mb-0" style="color: #0f172a; font-size: 28px;"><?php echo e($totalWarranties); ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border shadow-sm rounded-4 h-100" style="border-color: #e2e8f0 !important;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #dcfce7; color: #16a34a;">
                    <i class="bi bi-shield-fill-check fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1" style="font-size: 14px; font-weight: 500;"><?php echo e(__('Active Warranties')); ?></h6>
                    <h2 class="fw-bold mb-0" style="color: #0f172a; font-size: 28px;"><?php echo e($activeWarranties); ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border shadow-sm rounded-4 h-100" style="border-color: #e2e8f0 !important;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #fee2e2; color: #dc2626;">
                    <i class="bi bi-shield-fill-x fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1" style="font-size: 14px; font-weight: 500;"><?php echo e(__('Expired Warranties')); ?></h6>
                    <h2 class="fw-bold mb-0" style="color: #0f172a; font-size: 28px;"><?php echo e($expiredWarranties); ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4" style="background: #fff;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h4 class="mb-0 fw-bold" style="color: #1e293b;"><?php echo e(__('Recent Warranties')); ?></h4>
        <form action="<?php echo e(route('warranties.index')); ?>" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
    <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="<?php echo e(__('Search customer or warrant')); ?>" value="<?php echo e(request('search')); ?>" style="font-size: 0.95rem; height: 42px;">
    <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
        <span class="btn-search-text"><?php echo e(__('Search')); ?></span>
        <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
    </button>
</form>
    </div>
    <div class="card-body p-0">
        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th class="px-4 py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">#</th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;"><?php echo e(__('Warranty No')); ?></th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;"><?php echo e(__('Customer')); ?></th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;"><?php echo e(__('Product')); ?></th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;"><?php echo e(__('Duration')); ?></th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;"><?php echo e(__('Valid Until')); ?></th>
                        <th class="px-4 py-3 text-end" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;">
                    <?php $__empty_1 = true; $__currentLoopData = $warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 text-muted" style="font-size: 13px;"><?php echo e($loop->iteration + $warranties->firstItem() - 1); ?></td>
                        <td class="fw-bold" style="color: #0f172a;"><?php echo e($warranty->warranty_number); ?></td>
                        <td style="color: #1e293b;"><?php echo e($warranty->customer_name ?: '-'); ?></td>
                        <td>
                            <div class="fw-bold" style="color: #0f172a;"><?php echo e($warranty->product_name); ?></div>
                            <div style="font-size: 12px; color: #94a3b8;">SN: <?php echo e($warranty->serial_number ?: 'N/A'); ?></div>
                        </td>
                        <td style="color: #1e293b;"><?php echo e($warranty->duration); ?></td>
                        <td>
                            <?php
                                $isValid = \Carbon\Carbon::now()->startOfDay()->lte($warranty->end_date);
                            ?>
                            <?php if($isValid): ?>
                                <span class="badge" style="background: #dcfce7; color: #16a34a; font-weight: 500; padding: 6px 12px; border-radius: 6px;"><?php echo e($warranty->end_date->format('d M, Y')); ?></span>
                            <?php else: ?>
                                <span class="badge" style="background: #fee2e2; color: #dc2626; font-weight: 500; padding: 6px 12px; border-radius: 6px;"><?php echo e(__('Expired')); ?> (<?php echo e($warranty->end_date->format('d M, Y')); ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 text-end">
                            <a href="<?php echo e(route('warranties.edit', $warranty->id)); ?>" class="btn btn-sm rounded-pill px-3 me-2" style="border: 1px solid #3b82f6; color: #3b82f6; background: transparent; font-weight: 500;">
                                <i class="bi bi-pencil-square me-1"></i> <?php echo e(__('Edit')); ?>

                            </a>
                            <a href="<?php echo e(route('warranties.show', $warranty->id)); ?>" target="_blank" class="btn btn-sm rounded-pill px-3 me-2" style="border: 1px solid #0f172a; color: #0f172a; background: transparent; font-weight: 500;">
                                <i class="bi bi-printer-fill me-1"></i> <?php echo e(__('Print')); ?>

                            </a>
                            <form action="<?php echo e(route('warranties.destroy', $warranty->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this warranty?')); ?>')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm rounded-circle" style="border: 1px solid #ef4444; color: #ef4444; background: transparent; width: 32px; height: 32px; padding: 0;">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-check display-4 mb-3 d-block text-black-50"></i>
                            <?php echo e(__('No warranties generated yet.')); ?><br>
                            <a href="<?php echo e(route('warranties.create')); ?>" class="btn btn-primary mt-3 rounded-pill px-4"><?php echo e(__('Generate your first warranty')); ?></a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none p-3">
            <?php $__empty_1 = true; $__currentLoopData = $warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card mb-3 shadow-sm border border-light">
                <div class="card-body">
                    <div class="search-toolbar">
                        <div class="search-toolbar">
                            <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-3 border text-dark" style="width: 45px; height: 45px;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark"><?php echo e($warranty->warranty_number); ?></h6>
                                <div class="small text-muted"><?php echo e($warranty->customer_name ?: 'No Customer Name'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 small text-muted">
                        <div class="mb-1"><i class="bi bi-box me-2"></i> <?php echo e($warranty->product_name); ?></div>
                        <div class="mb-1"><i class="bi bi-upc-scan me-2"></i> SN: <?php echo e($warranty->serial_number ?: 'N/A'); ?></div>
                        <div class="mb-1"><i class="bi bi-calendar me-2"></i> Duration: <?php echo e($warranty->duration); ?></div>
                        <div><i class="bi bi-calendar-x me-2"></i> Valid: 
                            <?php
                                $isValid = \Carbon\Carbon::now()->startOfDay()->lte($warranty->end_date);
                            ?>
                            <?php if($isValid): ?>
                                <span class="text-success fw-bold"><?php echo e($warranty->end_date->format('d M, Y')); ?></span>
                            <?php else: ?>
                                <span class="text-danger fw-bold">Expired (<?php echo e($warranty->end_date->format('d M, Y')); ?>)</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="search-toolbar">
                        <a href="<?php echo e(route('warranties.edit', $warranty->id)); ?>" class="btn btn-outline-primary flex-fill" style="font-weight: 500;">
                            <i class="bi bi-pencil-square me-1"></i> <?php echo e(__('Edit')); ?>

                        </a>
                        <a href="<?php echo e(route('warranties.show', $warranty->id)); ?>" target="_blank" class="btn btn-outline-dark flex-fill" style="font-weight: 500;">
                            <i class="bi bi-printer-fill me-1"></i> <?php echo e(__('Print')); ?>

                        </a>
                        <form action="<?php echo e(route('warranties.destroy', $warranty->id)); ?>" method="POST" class="d-inline flex-fill" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this warranty?')); ?>')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-danger w-100" style="font-weight: 500;">
                                <i class="bi bi-trash-fill"></i> <?php echo e(__('Del')); ?>

                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-shield-check display-4 mb-3 d-block text-black-50"></i>
                <?php echo e(__('No warranties generated yet.')); ?><br>
                <a href="<?php echo e(route('warranties.create')); ?>" class="btn btn-primary mt-3 rounded-pill px-4"><?php echo e(__('Generate your first warranty')); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if($warranties->hasPages()): ?>
    <div class="card-footer bg-white border-top py-3">
        <?php echo e($warranties->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\warranties\index.blade.php ENDPATH**/ ?>