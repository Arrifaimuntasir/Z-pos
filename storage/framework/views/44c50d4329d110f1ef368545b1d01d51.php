<?php $__env->startSection('title', 'Edit Content - ' . $pageInfo['label']); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">

    
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="mb-0 fw-bold">
                <i class="bi <?php echo e($pageInfo['icon']); ?> me-2 text-<?php echo e($pageInfo['color']); ?>"></i>
                Hariri: <?php echo e($pageInfo['label']); ?>

            </h4>
        </div>
        <div class="col-auto">
            <a href="<?php echo e(url('/' . ($page === 'about' ? 'about' : $page))); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="bi bi-box-arrow-up-right me-1"></i> Angalia Ukurasa
            </a>
        </div>
    </div>



    <form action="<?php echo e(route('superadmin.cms.update', $page)); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom rounded-top-4 p-4">
                <h6 class="fw-bold mb-0 text-muted">
                    <i class="bi bi-sliders me-2"></i>Maudhui ya Ukurasa — <?php echo e(count($settings)); ?> Sehemu
                </h6>
            </div>
            <div class="card-body p-4">
                <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark" for="field_<?php echo e($setting->key); ?>">
                            <?php echo e($setting->label); ?>

                            <span class="badge bg-light text-muted border ms-2 fw-normal" style="font-size:0.7rem;"><?php echo e($setting->key); ?></span>
                        </label>

                        <?php if($setting->type === 'textarea'): ?>
                            <textarea
                                id="field_<?php echo e($setting->key); ?>"
                                name="<?php echo e($setting->key); ?>"
                                class="form-control <?php $__errorArgs = [$setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                rows="4"
                                style="border-radius:12px; border:1px solid #e2e8f0; background:#f8fafc; resize:vertical;"
                            ><?php echo e(old($setting->key, $setting->value)); ?></textarea>
                        <?php else: ?>
                            <input
                                type="text"
                                id="field_<?php echo e($setting->key); ?>"
                                name="<?php echo e($setting->key); ?>"
                                class="form-control <?php $__errorArgs = [$setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old($setting->key, $setting->value)); ?>"
                                style="border-radius:12px; border:1px solid #e2e8f0; background:#f8fafc;"
                            >
                        <?php endif; ?>

                        <?php $__errorArgs = [$setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php if(!$loop->last): ?><hr style="border-color:#f1f5f9;"><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-3">Hakuna fields kwa ukurasa huu bado.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white border-top p-4 rounded-bottom-4">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?php echo e(route('superadmin.cms.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Ghairi
                    </a>
                    <button type="submit" class="btn text-white fw-bold rounded-pill px-5" style="background-color:#64748b;">
                        <i class="bi bi-save me-1"></i> Hifadhi Mabadiliko
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\superadmin\cms\edit.blade.php ENDPATH**/ ?>