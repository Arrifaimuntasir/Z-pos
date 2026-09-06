

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Purchase Details')); ?></h4>
        <span class="text-muted small">Reference: <?php echo e($purchase->reference_no); ?></span>
    </div>
    <div>
        
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3"><?php echo e(__('Supplier Information')); ?></h6>
                <?php if($purchase->supplier): ?>
                    <p class="mb-1"><span class="text-muted"><?php echo e(__('Name:')); ?></span> <strong class="ms-2"><?php echo e($purchase->supplier->name); ?></strong></p>
                    <p class="mb-1"><span class="text-muted"><?php echo e(__('Contact:')); ?></span> <span class="ms-2"><?php echo e($purchase->supplier->contact_person ?? '-'); ?></span></p>
                    <p class="mb-1"><span class="text-muted"><?php echo e(__('Phone:')); ?></span> <span class="ms-2"><?php echo e($purchase->supplier->phone ?? '-'); ?></span></p>
                    <p class="mb-0"><span class="text-muted"><?php echo e(__('Email:')); ?></span> <span class="ms-2"><?php echo e($purchase->supplier->email ?? '-'); ?></span></p>
                <?php else: ?>
                    <p class="text-muted mb-0"><?php echo e(__('Supplier information unavailable.')); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3"><?php echo e(__('Purchase Information')); ?></h6>
                <p class="mb-1"><span class="text-muted"><?php echo e(__('Date:')); ?></span> <strong class="ms-2"><?php echo e(\Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y')); ?></strong></p>
                <p class="mb-1"><span class="text-muted"><?php echo e(__('Status:')); ?></span> 
                    <span class="ms-2 badge <?php echo e($purchase->status == 'completed' ? 'bg-success' : 'bg-warning'); ?>"><?php echo e(ucfirst($purchase->status)); ?></span>
                </p>
                <p class="mb-0 mt-3"><span class="text-muted"><?php echo e(__('Notes:')); ?></span><br>
                    <span class="small"><?php echo e($purchase->notes ?? 'No notes provided.'); ?></span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><?php echo e(__('Purchased Items')); ?></h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th><?php echo e(__('Product Name')); ?></th>
                                <th class="text-end"><?php echo e(__('Unit Cost')); ?></th>
                                <th class="text-center"><?php echo e(__('Qty')); ?></th>
                                <th class="text-end"><?php echo e(__('Subtotal')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $purchase->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->product->name ?? 'Unknown Product'); ?></td>
                                <td class="text-end"><?php echo e(number_format($item->unit_cost, 2)); ?></td>
                                <td class="text-center"><?php echo e($item->quantity); ?></td>
                                <td class="text-end fw-semibold"><?php echo e(number_format($item->subtotal, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold"><?php echo e(__('Grand Total')); ?></td>
                                <td class="text-end fw-bold fs-5 text-primary"><?php echo e(number_format($purchase->total_amount, 2)); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\purchases\show.blade.php ENDPATH**/ ?>