

<?php $__env->startSection('title', 'Manage Payments'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0"><?php echo e(__('System Admin: Pending Payments')); ?></h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-top-start ps-4 py-3"><?php echo e(__('Date')); ?></th>
                        <th class="border-0 py-3"><?php echo e(__('Shop')); ?></th>
                        <th class="border-0 py-3"><?php echo e(__('Receipt')); ?></th>
                        <th class="border-0 py-3"><?php echo e(__('Status')); ?></th>
                        <th class="border-0 rounded-top-end text-end pe-4 py-3"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 text-muted"><?php echo e($payment->created_at->format('M d, Y H:i')); ?></td>
                        <td class="fw-bold"><?php echo e($payment->shop->name); ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#receiptModal<?php echo e($payment->id); ?>">
                                <i class="bi bi-file-earmark-text me-1"></i> <?php echo e(__('View Receipt')); ?>

                            </button>
                        </td>
                        <td>
                            <?php if($payment->status === 'pending'): ?>
                                <span class="badge bg-warning text-dark"><?php echo e(__('Pending')); ?></span>
                            <?php elseif($payment->status === 'approved'): ?>
                                <span class="badge bg-success"><?php echo e(__('Approved')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger"><?php echo e(__('Rejected')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <?php if($payment->status === 'pending'): ?>
                                <form action="<?php echo e(route('superadmin.payments.approve', $payment)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success me-1" onclick="return confirm('Approve this payment and extend subscription by 1 month?');">
                                        <i class="bi bi-check-lg"></i> <?php echo e(__('Approve')); ?>

                                    </button>
                                </form>
                                <form action="<?php echo e(route('superadmin.payments.reject', $payment)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this payment?');">
                                        <i class="bi bi-x-lg"></i> <?php echo e(__('Reject')); ?>

                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small"><?php echo e(__('No actions')); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Receipt Modal -->
                    <div class="modal fade" id="receiptModal<?php echo e($payment->id); ?>" tabindex="-1" aria-labelledby="receiptModalLabel<?php echo e($payment->id); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="receiptModalLabel<?php echo e($payment->id); ?>">Receipt uploaded by: <strong class="text-primary"><?php echo e($payment->shop->name); ?></strong></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center bg-light">
                                    <?php
                                        $extension = pathinfo($payment->receipt_path, PATHINFO_EXTENSION);
                                    ?>
                                    <?php if(strtolower($extension) === 'pdf'): ?>
                                        <iframe src="<?php echo e(asset($payment->receipt_path)); ?>" width="100%" height="500px" style="border: none;"></iframe>
                                    <?php else: ?>
                                        <img src="<?php echo e(asset($payment->receipt_path)); ?>" alt="Payment Receipt" class="img-fluid rounded shadow-sm" style="max-height: 70vh; object-fit: contain;">
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <a href="<?php echo e(asset($payment->receipt_path)); ?>" target="_blank" class="btn btn-outline-primary"><i class="bi bi-box-arrow-up-right me-1"></i> <?php echo e(__('Open in New Tab')); ?></a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted"><?php echo e(__('No payments found.')); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\superadmin\payments\index.blade.php ENDPATH**/ ?>