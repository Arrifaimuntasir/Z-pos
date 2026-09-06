<?php $__env->startSection('title', 'Add Product'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center mb-4">
    
    <h4 class="fw-bold mb-0"><?php echo e(__('Add New Product')); ?></h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="<?php echo e(route('products.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Product Name')); ?> <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('SKU (Barcode/ID)')); ?> <span class="text-danger">*</span></label>
                    <input type="text" name="sku" class="form-control <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('sku', 'PRD-' . rand(100000, 999999))); ?>" required>
                    <small class="text-muted"><?php echo e(__('You can leave this auto-generated SKU or scan a barcode.')); ?></small>
                    <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Category')); ?> <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value=""><?php echo e(__('Select Category')); ?></option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Brand')); ?> (Optional)</label>
                    <select name="brand_id" class="form-select <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value=""><?php echo e(__('Select Brand')); ?></option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id') == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <?php if(empty(auth()->user()->shop->package) || strtolower(auth()->user()->shop->package) === 'starter'): ?>
                    <?php if(isset($branches) && $branches->count() > 0): ?>
                        <input type="hidden" name="branch_id" value="<?php echo e($activeBranchId ?? $branches->first()->id); ?>">
                    <?php endif; ?>
                <?php else: ?>
                    <?php if(isset($branches) && $branches->count() > 1): ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Branch')); ?> <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value=""><?php echo e(__('Select Branch')); ?></option>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch->id); ?>" <?php echo e((old('branch_id', $activeBranchId ?? '') == $branch->id) ? 'selected' : ''); ?>><?php echo e($branch->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php else: ?>
                        <?php if(isset($branches) && $branches->count() > 0): ?>
                            <input type="hidden" name="branch_id" value="<?php echo e($activeBranchId ?? $branches->first()->id); ?>">
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Model')); ?></label>
                    <input type="text" name="model" class="form-control <?php $__errorArgs = ['model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('model')); ?>">
                    <?php $__errorArgs = ['model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Unit')); ?> (Optional)</label>
                    <select name="unit_id" class="form-select <?php $__errorArgs = ['unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value=""><?php echo e(__('Select Unit')); ?></option>
                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($unit->id); ?>" <?php echo e(old('unit_id') == $unit->id ? 'selected' : ''); ?>><?php echo e($unit->name); ?> (<?php echo e($unit->short_name); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Stock Quantity')); ?> (Optional)</label>
                    <input type="number" name="stock" class="form-control <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('stock', 0)); ?>" min="0" id="stock_input">
                    <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Buying Price')); ?> (Optional)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">TSh</span>
                        <input type="number" step="0.01" name="cost_price" class="form-control border-start-0 <?php $__errorArgs = ['cost_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('cost_price')); ?>" min="0">
                    </div>
                    <?php $__errorArgs = ['cost_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Selling Price')); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">TSh</span>
                        <input type="number" step="0.01" name="selling_price" class="form-control border-start-0 <?php $__errorArgs = ['selling_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('selling_price')); ?>" min="0" required>
                    </div>
                    <?php $__errorArgs = ['selling_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <?php
                    $shopCategory = Auth::user()->shop->business_type;
                    $showExpiry = in_array($shopCategory, ['Pharmacy / Health', 'Supermarket / Grocery', 'Restaurant / Food']);
                    $showImei = in_array($shopCategory, ['Electronics / IT']);
                    $isMandatoryStock = in_array($shopCategory, ['Electronics / IT', 'Pharmacy / Health']);
                ?>
                
                <?php if($isMandatoryStock ?? false): ?>
                    <input type="hidden" id="track_stock" name="track_stock" value="1">
                <?php else: ?>
                    <div class="col-md-12 mt-4">
                        <hr>
                        <?php if(auth()->check() && auth()->user()->shop && auth()->user()->shop->business_type == 'Restaurant / Food'): ?>
                            <h6 class="fw-bold mb-3"><?php echo e(__('Inventory & Recipe Settings')); ?></h6>
                        <?php else: ?>
                            <h6 class="fw-bold mb-3"><?php echo e(__('Inventory Settings')); ?></h6>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" role="switch" id="track_stock" name="track_stock" value="1" <?php echo e(old('track_stock', true) ? 'checked' : ''); ?> onchange="toggleRecipeSection()">
                            <label class="form-check-label ms-2" for="track_stock">
                                <span class="fw-semibold">Track Stock (Advanced Stock Mode)</span><br>
                                <?php if(auth()->check() && auth()->user()->shop && auth()->user()->shop->business_type == 'Restaurant / Food'): ?>
                                    <small class="text-muted">Turn off for Simple Mode (e.g. cooked meals with no stock tracking). If ON, you can also add a recipe below.</small>
                                <?php else: ?>
                                    <small class="text-muted">Turn off if you do not want to track stock for this product.</small>
                                <?php endif; ?>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(auth()->check() && auth()->user()->shop && auth()->user()->shop->business_type == 'Restaurant / Food'): ?>
                <div class="col-md-12" id="recipe_section" style="display: <?php echo e(old('track_stock', true) ? 'block' : 'none'); ?>;">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Recipe / Ingredients (Optional)</h6>
                            <p class="text-muted small mb-3">If this product is made from other products (like Mchele, Nyama), add them here. When you sell this product, the stock of these ingredients will be deducted automatically.</p>
                            
                            <div id="ingredients_container">
                                <!-- Ingredients will be appended here -->
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addIngredient()">
                                <i class="bi bi-plus-circle"></i> Add Ingredient
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($showExpiry || $showImei): ?>
                <div class="col-md-12 mt-4">
                    <hr>
                    <h6 class="fw-bold mb-3"><?php echo e(__('Additional Business Settings')); ?></h6>
                </div>
                <?php endif; ?>
                
                <?php if($showExpiry): ?>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase"><?php echo e(__('Expiry Date')); ?> <?php if($shopCategory == 'Pharmacy / Health'): ?> <span class="text-danger">*</span> <?php endif; ?></label>
                    <input type="date" name="expiry_date" class="form-control <?php $__errorArgs = ['expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('expiry_date')); ?>" <?php if($shopCategory == 'Pharmacy / Health'): ?> required <?php endif; ?>>
                    <?php if($shopCategory != 'Pharmacy / Health'): ?>
                    <small class="text-muted"><?php echo e(__('Leave blank if product does not expire.')); ?></small>
                    <?php endif; ?>
                    <?php $__errorArgs = ['expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php endif; ?>
                
                <?php if($showImei): ?>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="requires_imei" name="requires_imei" value="1" <?php echo e(old('requires_imei') ? 'checked' : ''); ?>>
                        <label class="form-check-label ms-2" for="requires_imei">
                            <span class="fw-semibold">Requires IMEI / Serial Number</span><br>
                            <small class="text-muted">For Electronics/Mobile shops. Cashier will be asked to scan IMEI during sale.</small>
                        </label>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end pt-3 border-top">
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-light me-2"><?php echo e(__('Cancel')); ?></a>
                <button type="submit" class="btn btn-primary px-4"><?php echo e(__('Save Product')); ?></button>
            </div>
        </form>
    </div>
</div>

<script>
    let ingredientIndex = 0;
    function addIngredient() {
        let container = document.getElementById('ingredients_container');
        let html = `
            <div class="row g-2 mb-2 ingredient-row" id="ingredient_row_${ingredientIndex}">
                <div class="col-md-7">
                    <select name="ingredients[${ingredientIndex}][id]" class="form-select form-select-sm" required>
                        <option value="">Select Ingredient...</option>
                        <?php $__currentLoopData = $allProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?> (Stock: <?php echo e($p->stock); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.001" name="ingredients[${ingredientIndex}][quantity]" class="form-control form-control-sm" placeholder="Quantity (e.g. 0.25)" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="document.getElementById('ingredient_row_${ingredientIndex}').remove()">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        ingredientIndex++;
    }

    function toggleRecipeSection() {
        let tsEl = document.getElementById('track_stock');
        let trackStock = tsEl ? (tsEl.type === 'checkbox' ? tsEl.checked : (tsEl.value == 1)) : true;
        
        let rs = document.getElementById('recipe_section');
        if (rs) rs.style.display = trackStock ? 'block' : 'none';
        
        let stockInput = document.getElementById('stock_input');
        if(stockInput) {
            if(!trackStock) {
                stockInput.value = 0;
                stockInput.setAttribute('readonly', 'readonly');
                stockInput.style.backgroundColor = '#e9ecef';
            } else {
                stockInput.removeAttribute('readonly');
                stockInput.style.backgroundColor = '';
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        toggleRecipeSection();
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\products\create.blade.php ENDPATH**/ ?>