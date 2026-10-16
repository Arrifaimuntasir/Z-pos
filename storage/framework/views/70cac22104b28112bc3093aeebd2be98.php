<?php $__env->startSection('title', 'Manage Payments'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0"><?php echo e(__('System Admin: Manage Payments')); ?></h4>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-top-start ps-4 py-3"><?php echo e(__('Date')); ?></th>
                        <th class="border-0 py-3"><?php echo e(__('Shop')); ?></th>
                        <th class="border-0 py-3"><?php echo e(__('Package & Duration')); ?></th>
                        <th class="border-0 py-3"><?php echo e(__('Amount')); ?></th>
                        <th class="border-0 py-3"><?php echo e(__('Receipt')); ?></th>
                        <th class="border-0 py-3"><?php echo e(__('Status')); ?></th>
                        <th class="border-0 rounded-top-end text-end pe-4 py-3"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 text-muted small"><?php echo e($payment->created_at ? $payment->created_at->format('M d, Y H:i') : 'N/A'); ?></td>
                        <td class="fw-bold"><?php echo e($payment->shop ? $payment->shop->name : 'Unknown Shop'); ?></td>
                        <td>
                            <?php
                                $pkg = $payment->package ?? ($payment->shop ? $payment->shop->package : 'starter') ?? 'starter';
                                $billing = $payment->billing_cycle ?? ($payment->shop ? $payment->shop->billing_cycle : 'monthly') ?? 'monthly';
                            ?>
                            <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase fw-semibold"><?php echo e($pkg); ?></span>
                            <span class="badge ms-1 <?php echo e($billing === 'yearly' ? 'bg-success text-white' : 'bg-info text-dark'); ?>">
                                <i class="bi bi-<?php echo e($billing === 'yearly' ? 'calendar-check' : 'calendar'); ?> me-1"></i><?php echo e($billing === 'yearly' ? __('Yearly') : __('Monthly')); ?>

                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">TZS <?php echo e(number_format($payment->amount ?? 0)); ?></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#receiptModal<?php echo e($payment->id); ?>">
                                <i class="bi bi-file-earmark-text me-1"></i> <?php echo e(__('View Receipt')); ?>

                            </button>
                        </td>
                        <td>
                            <?php if($payment->status === 'pending'): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i><?php echo e(__('Pending')); ?></span>
                            <?php elseif($payment->status === 'approved'): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i><?php echo e(__('Approved')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i><?php echo e(__('Rejected')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                <?php if($payment->status === 'pending'): ?>
                                    <form action="<?php echo e(route('superadmin.payments.approve', $payment)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Approve this payment?\n\nShop: <?php echo e($payment->shop ? $payment->shop->name : 'Unknown Shop'); ?>\nPackage: <?php echo e(strtoupper($pkg)); ?> (<?php echo e(ucfirst($billing)); ?>)\nAmount: TZS <?php echo e(number_format($payment->amount ?? 0)); ?>\n\nSubscription will be extended by <?php echo e($billing === 'yearly' ? '1 Year' : '1 Month'); ?>.');">
                                            <i class="bi bi-check-lg"></i> <?php echo e(__('Approve')); ?>

                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('superadmin.payments.reject', $payment)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-warning text-white"
                                            onclick="return confirm('Reject this payment from <?php echo e($payment->shop ? $payment->shop->name : 'Unknown Shop'); ?>?');">
                                            <i class="bi bi-x-lg"></i> <?php echo e(__('Reject')); ?>

                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <form action="<?php echo e(route('superadmin.payments.destroy', $payment)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('DELETE this payment record permanently?\n\nShop: <?php echo e($payment->shop ? $payment->shop->name : 'Unknown Shop'); ?>\nThis cannot be undone.');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Receipt Modal -->
                    <div class="modal fade" id="receiptModal<?php echo e($payment->id); ?>" tabindex="-1" aria-labelledby="receiptModalLabel<?php echo e($payment->id); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>
                                        <h5 class="modal-title mb-1" id="receiptModalLabel<?php echo e($payment->id); ?>">
                                            <?php echo e(__('Receipt from')); ?>: <strong class="text-primary"><?php echo e($payment->shop ? $payment->shop->name : 'Unknown Shop'); ?></strong>
                                        </h5>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase"><?php echo e($pkg); ?></span>
                                            <span class="badge <?php echo e($billing === 'yearly' ? 'bg-success text-white' : 'bg-info text-dark'); ?>">
                                                <?php echo e($billing === 'yearly' ? __('Yearly') : __('Monthly')); ?>

                                            </span>
                                            <span class="fw-bold text-dark small">TZS <?php echo e(number_format($payment->amount ?? 0)); ?></span>
                                        </div>
                                    </div>
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;"></i>
                            <?php echo e(__('No payments found.')); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views/superadmin/payments/index.blade.php ENDPATH**/ ?>