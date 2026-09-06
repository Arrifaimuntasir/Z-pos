<?php $__env->startSection('title', 'Sales History'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Sales History')); ?></h4>
        <span class="text-muted small"><?php echo e(__('Manage and view all your sales records')); ?></span>
    </div>
    <div>
        <a href="<?php echo e(route('sales.create')); ?>" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-plus-lg me-2"></i> <?php echo e(__('New Sale')); ?>

        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">

        
        <form action="<?php echo e(route('sales.index')); ?>" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 450px;">
            <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
            <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="<?php echo e(__('Search...')); ?>" value="<?php echo e(request('search')); ?>" style="font-size: 0.95rem; height: 42px;">
            <button type="submit" class="btn btn-primary rounded-pill me-1 px-4 fw-semibold shadow-sm" style="height: 36px; display: flex; align-items: center;">
                <span class="btn-search-text"><?php echo e(__('Search')); ?></span>
                <i class="bi bi-arrow-right-short btn-search-icon d-none fs-5"></i>
            </button>
        </form>

        
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            
            <button type="submit" form="bulkDeleteForm"
                class="btn btn-sm btn-danger px-3 shadow-sm rounded-pill align-items-center gap-1 d-none"
                onclick="return confirm('<?php echo e(__('Are you sure? This will restore stock for selected sales.')); ?>')"
                id="deleteBtn">
                <i class="bi bi-trash3-fill me-1"></i> <span><?php echo e(__('Delete')); ?></span>
            </button>
            <button type="button" class="btn btn-sm shadow-sm rounded-pill px-3 fw-bold" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;" id="toggleSelectBtn">
                <i class="bi bi-check2-square me-1"></i> <?php echo e(__('Select')); ?>

            </button>
        </div>
    </div>

    
    <div id="selectAllBar" class="d-none px-3 px-md-4 py-2 border-bottom" style="background-color: #f1f5f9;">
        <div class="d-flex align-items-center gap-3">
            <div class="form-check mb-0">
                <input class="form-check-input border-secondary" type="checkbox" id="selectAll"
                    style="cursor:pointer; width:1.15rem; height:1.15rem;">
                <label class="form-check-label ms-1 fw-bold text-dark" for="selectAll" style="font-size:0.9rem;"><?php echo e(__('Select All')); ?></label>
            </div>
            <span class="text-muted small" id="selectedCountLabel">0 <?php echo e(__('selected')); ?></span>
        </div>
    </div>

    <form id="bulkDeleteForm" action="<?php echo e(route('sales.bulk-destroy')); ?>" method="POST">
        <?php echo csrf_field(); ?>
    </form>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-3 border-0 fw-semibold py-3 selection-th d-none" style="width: 40px;"></th>
                        <th class="ps-4 border-0 fw-semibold py-3" style="min-width: 120px;"><?php echo e(__('Date')); ?></th>
                        <th class="border-0 fw-semibold" style="min-width: 150px;"><?php echo e(__('Reference No.')); ?></th>
                        <th class="border-0 fw-semibold" style="min-width: 200px;"><?php echo e(__('Product Name')); ?></th>
                        <th class="border-0 fw-semibold" style="min-width: 150px;"><?php echo e(__('Customer')); ?></th>
                        <?php if($isAdmin): ?>
                        <th class="border-0 fw-semibold" style="min-width: 120px;"><?php echo e(__('Seller')); ?></th>
                        <?php endif; ?>
                        <th class="border-0 fw-semibold" style="min-width: 120px;"><?php echo e(__('Status')); ?></th>
                        <th class="border-0 fw-semibold text-end" style="min-width: 150px;"><?php echo e(__('Total Amount')); ?></th>
                        <th class="border-0 fw-semibold text-center" style="min-width: 80px;"><?php echo e(__('Receipt')); ?></th>
                        <?php if(auth()->user()->shop && auth()->user()->shop->business_type === 'Electronics / IT'): ?>
                        <th class="border-0 fw-semibold text-center" style="min-width: 80px;"><?php echo e(__('Warranty')); ?></th>
                        <?php endif; ?>
                        <th class="pe-4 border-0 fw-semibold text-center rounded-end" style="min-width: 120px;"><?php echo e(__('Action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-3 selection-cell d-none">
                                <input class="form-check-input sale-checkbox" type="checkbox" name="ids[]" form="bulkDeleteForm" value="<?php echo e($sale->id); ?>" style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                            </td>
                            <td class="ps-4">
                                <?php echo e(\Carbon\Carbon::parse($sale->sale_date)->format('M d, Y')); ?>

                            </td>
                            <td><span class="fw-medium"><?php echo e($sale->reference_no); ?></span></td>
                            <td>
                                <?php if($sale->items && $sale->items->count() > 0): ?>
                                    <?php
                                        $productNames = $sale->items->map(function($item) {
                                            return $item->product ? $item->product->name : 'Unknown';
                                        })->take(2)->implode(', ');
                                        
                                        if($sale->items->count() > 2) {
                                            $productNames .= ' (+'.($sale->items->count() - 2).' more)';
                                        }
                                    ?>
                                    <span class="small fw-medium text-dark" title="<?php echo e($sale->items->map(function($item) { return $item->product ? $item->product->name : 'Unknown'; })->implode(', ')); ?>">
                                        <?php echo e($productNames); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($sale->customer): ?>
                                    <?php echo e($sale->customer->name); ?>

                                <?php else: ?>
                                    <span class="text-muted fst-italic"><?php echo e(__('Walk-in Customer')); ?></span>
                                <?php endif; ?>
                            </td>
                            <?php if($isAdmin): ?>
                            <td>
                                <span class="small text-dark fw-medium"><?php echo e($sale->user ? $sale->user->name : __('Admin')); ?></span>
                            </td>
                            <?php endif; ?>
                            <td>
                                <?php if($sale->payment_status == 'paid'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill"><?php echo e(__('Paid')); ?></span>
                                <?php elseif($sale->payment_status == 'partial'): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill"><?php echo e(__('Partial')); ?></span>
                                <?php elseif($sale->payment_status == 'proforma'): ?>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1 rounded-pill"><?php echo e(__('Pro-forma')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill"><?php echo e(__('Unpaid')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold">
                                <?php if($sale->net_total_amount < $sale->total_amount): ?>
                                    <span class="text-muted text-decoration-line-through me-1" style="font-size: 0.85em;"><?php echo e(number_format($sale->total_amount)); ?></span>
                                    <span class="text-danger"><?php echo e(number_format($sale->net_total_amount)); ?> TSh</span>
                                <?php else: ?>
                                    <?php echo e(number_format($sale->total_amount)); ?> TSh
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo e(route('sales.show', $sale->id)); ?>" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;" title="<?php echo e(__('View Receipt')); ?>">
                                    <i class="bi bi-receipt"></i>
                                </a>
                            </td>
                            <?php if(auth()->user()->shop && auth()->user()->shop->business_type === 'Electronics / IT'): ?>
                            <td class="text-center">
                                <a href="<?php echo e(route('warranties.create', ['sale_id' => $sale->id])); ?>" class="btn btn-sm btn-light text-success shadow-sm" style="border-radius: 6px;" title="<?php echo e(__('Generate Warranty')); ?>">
                                    <i class="bi bi-shield-check"></i>
                                </a>
                            </td>
                            <?php endif; ?>
                            <td class="pe-4 text-center">
                                <div class="search-toolbar">
                                    <?php if($sale->payment_status == 'proforma'): ?>
                                    <form action="<?php echo e(route('sales.mark_paid', $sale->id)); ?>" method="POST" onsubmit="return confirm('Mark this Pro-forma invoice as Paid? This will deduct stock from your inventory.');">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-light text-info shadow-sm" style="border-radius: 6px;" title="<?php echo e(__('Mark as Paid')); ?>">
                                            <i class="bi bi-check2-circle"></i> <?php echo e(__('Pay')); ?>

                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('sales.destroy', $sale->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this sale? This will restore the sold items back to stock and reduce your total sales and profit.');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 6px;" title="<?php echo e(__('Delete')); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e((auth()->user()->shop && auth()->user()->shop->business_type === 'Electronics / IT') ? 9 : 8); ?>" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 text-light-secondary mb-3 d-block"></i>
                                <h5><?php echo e(__('No sales recorded yet')); ?></h5>
                                <p class="mb-4"><?php echo e(__('Start selling products by going to the POS.')); ?></p>
                                <a href="<?php echo e(route('sales.create')); ?>" class="btn btn-primary px-4"><?php echo e(__('Go to POS')); ?></a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4 pb-4 d-flex justify-content-end">
            <?php echo e($sales->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleSelectBtn    = document.getElementById('toggleSelectBtn');
    const deleteBtn          = document.getElementById('deleteBtn');
    const selectAllBar       = document.getElementById('selectAllBar');
    const selectAllInput     = document.getElementById('selectAll');
    const selectedCountLabel = document.getElementById('selectedCountLabel');
    const checkboxCells      = document.querySelectorAll('.selection-cell');
    const selectionThs       = document.querySelectorAll('.selection-th');
    const checkboxes         = document.querySelectorAll('.sale-checkbox');

    let selectMode = false;

    function updateUI() {
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;

        // Update count label
        selectedCountLabel.textContent = checkedCount + ' <?php echo e(__("selected")); ?>';

        // Show/hide delete button
        if (checkedCount > 0) {
            deleteBtn.classList.remove('d-none');
            deleteBtn.classList.add('d-flex');
        } else {
            deleteBtn.classList.add('d-none');
            deleteBtn.classList.remove('d-flex');
        }

        // Sync Select All checkbox state
        if (selectAllInput) {
            selectAllInput.checked = checkboxes.length > 0 && checkedCount === checkboxes.length;
            selectAllInput.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        }
    }

    toggleSelectBtn.addEventListener('click', function () {
        selectMode = !selectMode;
        if (selectMode) {
            // Enter select mode
            toggleSelectBtn.innerHTML = '<i class="bi bi-x-circle me-1"></i> <?php echo e(__("Cancel")); ?>';
            selectAllBar.classList.remove('d-none');
            checkboxCells.forEach(c => c.classList.remove('d-none'));
            selectionThs.forEach(c => c.classList.remove('d-none'));
        } else {
            // Exit select mode
            toggleSelectBtn.innerHTML = '<i class="bi bi-check2-square me-1"></i> <?php echo e(__("Select")); ?>';
            selectAllBar.classList.add('d-none');
            checkboxCells.forEach(c => c.classList.add('d-none'));
            selectionThs.forEach(c => c.classList.add('d-none'));
            checkboxes.forEach(cb => cb.checked = false);
            if (selectAllInput) {
                selectAllInput.checked = false;
                selectAllInput.indeterminate = false;
            }
            updateUI();
        }
    });

    if (selectAllInput) {
        selectAllInput.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateUI();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateUI();
        });
    });

    updateUI();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\sales\index.blade.php ENDPATH**/ ?>