

<?php $__env->startSection('title', 'Add Staff'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Add New Staff')); ?></h4>
        <span class="text-muted small"><?php echo e(__('Create an account for your shop cashier or manager')); ?></span>
    </div>
    <div>
        
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="<?php echo e(route('staff.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><?php echo e(__('Full Name')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-start-0 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" required placeholder="<?php echo e(__('e.g. arrifai muntasir')); ?>">
                        </div>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address (Used for Login)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" required placeholder="staff@example.com">
                        </div>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold"><?php echo e(__('Password')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control bg-light border-start-0 border-end-0 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required placeholder="<?php echo e(__('Minimum 8 characters')); ?>">
                            <span class="input-group-text bg-light <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="cursor: pointer;" onclick="togglePassword('password', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold"><?php echo e(__('Confirm Password')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-start-0 border-end-0" required placeholder="<?php echo e(__('Type password again')); ?>">
                            <span class="input-group-text bg-light" style="cursor: pointer;" onclick="togglePassword('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>

                    <?php if(empty($shop->package) || strtolower($shop->package) === 'starter'): ?>
                        <?php if($branches->count() > 0): ?>
                            <input type="hidden" name="branch_id" value="<?php echo e($branches->first()->id); ?>">
                        <?php endif; ?>
                    <?php else: ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><?php echo e(__('Assign to Branch')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                            <select name="branch_id" class="form-select bg-light border-start-0 <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value=""><?php echo e(__('Select Branch')); ?></option>
                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($branch->id); ?>" <?php echo e(old('branch_id') == $branch->id ? 'selected' : ''); ?>><?php echo e($branch->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php endif; ?>

                    <div class="alert alert-info border-0 bg-primary bg-opacity-10 d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                        <div>
                            <?php echo e(__('This user will automatically be assigned the')); ?> <strong><?php echo e(__('Cashier')); ?></strong> <?php echo e(__('role and will only be able to see POS and Sales related features.')); ?>

                        </div>
                    </div>

                    <div class="mt-4 pt-2 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold w-100" style="border-radius: 8px;">
                            <i class="bi bi-check2-circle me-2"></i> <?php echo e(__('Create Staff Account')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 16px;">
            <div class="card-body p-5">
                <h4 class="fw-bold mb-4"><i class="bi bi-shield-lock me-2"></i> <?php echo e(__('Security First')); ?></h4>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-cash-coin fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1"><?php echo e(__('Cashier Role')); ?></h6>
                        <p class="text-white-50 small mb-0"><?php echo e(__('Cashiers can only make sales and view their daily transactions. They cannot delete sales or view overall profit reports.')); ?></p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">User Limits (<?php echo e(ucfirst($shop->package ?? 'Starter')); ?> Plan)</h6>
                        <?php if(($shop->package ?? 'starter') === 'starter'): ?>
                        <p class="text-white-50 small mb-0">The Starter plan allows a maximum of 2 users (You + 1 Staff). Upgrade your plan to add more staff.</p>
                        <?php else: ?>
                        <p class="text-white-50 small mb-0">Your <?php echo e(ucfirst($shop->package)); ?> plan allows unlimited users! Feel free to add as many staff members as you need.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function togglePassword(inputId, iconElement) {
        const input = document.getElementById(inputId);
        const icon = iconElement.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\staff\create.blade.php ENDPATH**/ ?>