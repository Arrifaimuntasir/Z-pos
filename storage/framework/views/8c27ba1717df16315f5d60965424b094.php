

<?php $__env->startSection('title', 'Sale Receipt'); ?>
<?php $__env->startSection('hide_back_btn', true); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3 d-print-none">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Sale Receipt')); ?></h4>
        <span class="text-muted small"><?php echo e(__('View transaction details')); ?></span>
    </div>
    <div>
        
        <?php if(Auth::user()->shop && in_array(Auth::user()->shop->business_type, ['Retail / General', 'Electronics / IT'])): ?>
        <a href="<?php echo e(route('sales.returns.create', $sale->id)); ?>" class="btn btn-warning px-3 shadow-sm text-dark" style="border-radius: 8px;">
            <i class="bi bi-arrow-return-left me-1"></i> <?php echo e(__('Return/Refund')); ?>

        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('sales.pdf', $sale->id)); ?>?v=<?php echo e(time()); ?>" class="btn btn-danger px-3 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-file-earmark-pdf me-1"></i> <?php echo e(__('PDF')); ?>

        </a>
        <button onclick="window.print()" class="btn btn-primary px-3 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-printer me-1"></i> <?php echo e(__('Print')); ?>

        </button>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-5" id="receiptArea">
                
                <!-- Receipt Header -->
                <div class="text-center border-bottom pb-4 mb-4">
                    <h2 class="fw-bold text-dark mb-1"><?php echo e($sale->shop->name ?? 'Z-POS SYSTEM'); ?></h2>
                    <p class="text-muted mb-0"><?php echo e($sale->shop->address ?? 'Dar es Salaam, Tanzania'); ?></p>
                    <p class="text-muted mb-0">Tel: <?php echo e($sale->shop->phone ?? '+255 123 456 789'); ?></p>

                </div>

                <!-- Receipt Info -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="fw-bold text-muted small text-uppercase mb-1"><?php echo e(__('Billed To:')); ?></h6>
                        <?php if($sale->customer): ?>
                            <h5 class="fw-bold mb-0"><?php echo e($sale->customer->name); ?></h5>
                            <?php if($sale->customer->phone): ?> <p class="text-muted mb-0"><?php echo e($sale->customer->phone); ?></p> <?php endif; ?>
                            <?php if($sale->customer->address): ?> <p class="text-muted mb-0"><?php echo e($sale->customer->address); ?></p> <?php endif; ?>
                        <?php else: ?>
                            <h5 class="fw-bold mb-0 text-muted fst-italic"><?php echo e(__('Walk-in Customer')); ?></h5>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                        <?php if($sale->payment_status == 'proforma'): ?>
                            <h6 class="fw-bold text-info small text-uppercase mb-1"><?php echo e(__('Pro-Forma Invoice:')); ?></h6>
                        <?php else: ?>
                            <h6 class="fw-bold text-muted small text-uppercase mb-1"><?php echo e(__('Receipt Details:')); ?></h6>
                        <?php endif; ?>
                        <h5 class="fw-bold text-primary mb-1"><?php echo e($sale->reference_no); ?></h5>
                        <p class="text-muted mb-0">Date: <?php echo e(\Carbon\Carbon::parse($sale->sale_date)->format('M d, Y')); ?></p>
                        <p class="text-muted mb-0">Method: <?php echo e($sale->payment_method); ?></p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th><?php echo e(__('Product Name')); ?></th>
                                <th class="text-center" style="width: 100px;"><?php echo e(__('Qty')); ?></th>
                                <th class="text-end" style="width: 150px;"><?php echo e(__('Price')); ?></th>
                                <th class="text-end" style="width: 150px;"><?php echo e(__('Total')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->net_quantity > 0): ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <span class="fw-medium"><?php echo e($item->product ? $item->product->name : 'Unknown Product'); ?></span>
                                        <?php if($item->imei_serial_number): ?>
                                            <br><small class="text-muted">IMEI/SN: <?php echo e($item->imei_serial_number); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo e($item->net_quantity); ?></td>
                                    <td class="text-end"><?php echo e(number_format($item->unit_price)); ?></td>
                                    <td class="text-end fw-bold"><?php echo e(number_format($item->net_total)); ?></td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="row justify-content-end">
                    <div class="col-sm-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted fw-medium"><?php echo e(__('Subtotal:')); ?></span>
                            <span class="fw-bold"><?php echo e(number_format($sale->net_total_amount)); ?> TSh</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-muted fw-medium"><?php echo e(__('Discount:')); ?></span>
                            <span class="fw-bold">0 TSh</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fs-5 fw-bold text-dark"><?php echo e(__('Grand Total:')); ?></span>
                            <span class="fs-5 fw-bold text-primary"><?php echo e(number_format($sale->net_total_amount)); ?> TSh</span>
                        </div>
                        
                        <?php if($sale->payment_status != 'proforma'): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted fw-medium"><?php echo e(__('Amount Paid:')); ?></span>
                                <span class="fw-bold text-success"><?php echo e(number_format($sale->paid_amount)); ?> TSh</span>
                            </div>
                            <?php if($sale->net_total_amount - $sale->paid_amount > 0): ?>
                                <div class="d-flex justify-content-between text-danger">
                                    <span class="fw-medium"><?php echo e(__('Balance Due:')); ?></span>
                                    <span class="fw-bold"><?php echo e(number_format($sale->net_total_amount - $sale->paid_amount)); ?> TSh</span>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="d-flex justify-content-between text-info">
                                <span class="fw-medium"><?php echo e(__('Status:')); ?></span>
                                <span class="fw-bold"><?php echo e(__('PRO-FORMA')); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($sale->notes): ?>
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-bold text-muted small text-uppercase mb-2"><?php echo e(__('Notes:')); ?></h6>
                    <p class="text-muted mb-0"><?php echo e($sale->notes); ?></p>
                </div>
                <?php endif; ?>

                <!-- Footer Advertisement -->
                <div class="text-center mt-5 pt-4 border-top">
                    <p class="text-muted small fst-italic mb-2"><?php echo e($sale->shop->receipt_message ?? 'Thank you for your business!'); ?></p>
                    <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                        <strong><?php echo e(__('Powered by Z-POS SYSTEM')); ?></strong> <br> <?php echo e(__('Smart Point of Sale & Inventory Management')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white; }
    .wrapper { display: block; }
    .sidebar { display: none !important; }
    #content { margin: 0; padding: 0; width: 100%; min-height: auto; }
    .top-navbar, .d-print-none { display: none !important; }
    .card { box-shadow: none !important; border: none !important; }
    .card-body { padding: 0 !important; }
}
</style>
<?php if(session('error')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if(typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "<?php echo e(session('error')); ?>",
            confirmButtonColor: '#d33'
        });
    }
});
</script>
<?php endif; ?>
<?php if(session('success')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if(typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "<?php echo e(session('success')); ?>",
            confirmButtonColor: '#16a34a'
        });
    }
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\sales\show.blade.php ENDPATH**/ ?>