<?php $__env->startSection('title', 'Products List'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0"><?php echo e(__('Products')); ?></h4>
        <?php if(isset($activeBranch) && $activeBranch): ?>
            <span class="badge bg-primary rounded-pill mt-1 px-3 py-1">
                <i class="bi bi-shop me-1"></i> <?php echo e($activeBranch->name); ?>

            </span>
        <?php else: ?>
            <span class="badge bg-secondary rounded-pill mt-1 px-3 py-1">
                <i class="bi bi-grid me-1"></i> <?php echo e(__('All Branches')); ?>

            </span>
        <?php endif; ?>
    </div>
    <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('Add Product')); ?>

    </a>
</div>


<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <!-- Bulk Delete Controls -->
        <div class="d-flex align-items-center">
            <button type="button" id="toggleSelectBtn" class="btn btn-sm shadow-sm rounded-pill px-3 fw-bold" style="background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;">
                <i class="bi bi-check2-square"></i> <?php echo e(__('Select')); ?>

            </button>
            <form id="bulkDeleteForm" action="<?php echo e(route('products.bulk-destroy')); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" id="bulkDeleteBtn" class="btn btn-danger btn-sm shadow-sm rounded-pill px-3 ms-2 d-none" onclick="return confirm('<?php echo e(__('Are you sure you want to delete selected products?')); ?>');">
                    <i class="bi bi-trash"></i> <?php echo e(__('Delete Selected')); ?> (<span id="selectedCount">0</span>)
                </button>
            </form>
        </div>

        <!-- Search Bar -->
        <form action="<?php echo e(route('products.index')); ?>" method="GET" class="custom-search-bar d-flex align-items-center bg-white shadow-sm rounded-pill border" style="width: 100%; max-width: 350px;">
            <span class="ps-3 pe-2 text-primary"><i class="bi bi-search fs-5"></i></span>
            <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="<?php echo e(__('Search products...')); ?>" value="<?php echo e(request('search')); ?>" style="font-size: 0.95rem; height: 38px;">
        </form>
    </div>
    
    <div class="card-body p-4 pt-0">
        <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-muted table-light">
                        <tr>
                            <th class="select-column d-none" style="width: 40px; min-width: 40px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th style="min-width: 200px;"><?php echo e(__('Product')); ?></th>
                            <th style="min-width: 150px;"><?php echo e(__('Brand & Model')); ?></th>
                            <th style="min-width: 120px;"><?php echo e(__('SKU')); ?></th>
                            <th class="text-center" style="min-width: 120px;"><?php echo e(__('Stock')); ?></th>
                            <th class="text-end" style="min-width: 120px;"><?php echo e(__('Buying Price')); ?></th>
                            <th class="text-end" style="min-width: 120px;"><?php echo e(__('Selling Price')); ?></th>
                            <th class="text-end" style="min-width: 100px;"><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="select-column d-none">
                                <div class="form-check">
                                    <input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]" value="<?php echo e($product->id); ?>" form="bulkDeleteForm">
                                </div>
                            </td>
                            <td>
                                <div class="search-toolbar">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white bg-primary me-3 shadow-sm" style="width: 28px; height: 28px; font-size: 0.85rem;">
                                        <?php echo e($loop->iteration); ?>

                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?php echo e($product->name); ?></h6>
                                        <span class="text-muted small"><?php echo e($product->category->name ?? 'No Category'); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo e($product->brand->name ?? '-'); ?></div>
                                <div class="text-muted small"><?php echo e($product->model ?? '-'); ?></div>
                            </td>
                            <td><?php echo e($product->sku); ?></td>
                            <td class="text-center">
                                <?php
                                    $hasBranches = $product->branches->isNotEmpty();

                                    if ($hasBranches && isset($activeBranchId) && $activeBranchId) {
                                        $branchRow = $product->branches->firstWhere('id', $activeBranchId);
                                        $stock = $branchRow ? $branchRow->pivot->quantity : 0;
                                        $label = '';
                                    } elseif ($hasBranches) {
                                        $stock = $product->branches->sum('pivot.quantity');
                                        $label = ' <small>(Total)</small>';
                                    } else {
                                        $stock = $product->stock;
                                        $label = '';
                                    }
                                ?>
                                <?php if($stock <= $product->alert_quantity): ?>
                                    <span class="badge bg-danger rounded-pill px-3"><?php echo e($stock); ?> <?php echo e($product->unit->short_name ?? ''); ?><?php echo $label; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success rounded-pill px-3"><?php echo e($stock); ?> <?php echo e($product->unit->short_name ?? ''); ?><?php echo $label; ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-semibold text-muted"><?php echo e(number_format($product->cost_price)); ?> TSh</td>
                            <td class="text-end fw-bold"><?php echo e(number_format($product->selling_price)); ?> TSh</td>
                            <td class="text-end">
                                <div class="btn-group gap-1">
                                    <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <?php echo e(__('No products found. Add your first product to get started.')); ?>

                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 d-flex justify-content-end">
                <?php echo e($products->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');
        const toggleSelectBtn = document.getElementById('toggleSelectBtn');
        const selectColumns = document.querySelectorAll('.select-column');

        let selectMode = false;

        toggleSelectBtn.addEventListener('click', function() {
            selectMode = !selectMode;
            if (selectMode) {
                toggleSelectBtn.innerHTML = '<i class="bi bi-x-circle"></i> <?php echo e(__('Cancel')); ?>';
                selectColumns.forEach(col => col.classList.remove('d-none'));
            } else {
                toggleSelectBtn.innerHTML = '<i class="bi bi-check2-square"></i> <?php echo e(__('Select')); ?>';
                selectColumns.forEach(col => col.classList.add('d-none'));
                // Uncheck all
                selectAll.checked = false;
                checkboxes.forEach(cb => cb.checked = false);
                updateBulkDeleteBtn();
            }
        });

        function updateBulkDeleteBtn() {
            const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('d-none');
                selectedCount.textContent = checkedCount;
                toggleSelectBtn.classList.add('d-none');
            } else {
                bulkDeleteBtn.classList.add('d-none');
                toggleSelectBtn.classList.remove('d-none');
            }
            selectAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkDeleteBtn();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteBtn);
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\products\index.blade.php ENDPATH**/ ?>