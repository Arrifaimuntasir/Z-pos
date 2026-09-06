<?php $__env->startSection('title', __('Shop Settings')); ?>
<?php $__env->startSection('hide_back_btn', true); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <a href="<?php echo e(route('home')); ?>" class="btn btn-light border bg-white shadow-sm rounded-pill px-3 fw-bold" style="color: #475569; transition: all 0.2s ease;">
        <i class="bi bi-arrow-left me-1"></i> <?php echo e(__('Back')); ?>

    </a>
</div>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0 text-dark">
        <i class="bi bi-gear-fill text-primary me-2"></i><?php echo e(__('Shop Settings')); ?>

    </h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 p-md-5">
                <form action="<?php echo e(route('shop.settings.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row g-5">
                        <!-- Left Column: Logo & Visuals -->
                        <div class="col-lg-4 text-center border-end-lg">
                            <h6 class="fw-bold text-muted mb-4"><?php echo e(__('Shop Identity')); ?></h6>
                            
                            <div class="position-relative d-inline-block mb-4">
                                <div class="p-2 bg-white rounded-circle shadow-sm" style="width: 160px; height: 160px; margin: 0 auto;">
                                    <?php if($shop->logo_path): ?>
                                        <img src="<?php echo e(asset($shop->logo_path)); ?>" alt="Shop Logo" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle w-100 h-100 d-flex justify-content-center align-items-center bg-light text-primary" style="font-size: 4rem;">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <label for="logo" class="position-absolute bottom-0 end-0 btn btn-primary rounded-circle shadow" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; cursor: pointer; transform: translate(-10px, -10px);">
                                    <i class="bi bi-camera-fill fs-5"></i>
                                </label>
                                <input type="file" id="logo" name="logo" class="d-none" accept="image/*">
                            </div>
                            
                            <p class="text-muted small mb-0"><?php echo e(__('Upload New Logo')); ?></p>
                            <p class="text-muted" style="font-size: 0.75rem;">(JPG, PNG, GIF, Max 2MB)</p>
                            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="alert alert-danger mt-2 py-2 px-3 small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Right Column: Settings Form -->
                        <div class="col-lg-8">
                            <h6 class="fw-bold text-muted mb-4"><?php echo e(__('Basic Information')); ?></h6>
                            
                            <div class="row g-4">
                                <!-- Shop Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark"><?php echo e(__('Shop Name')); ?> <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-building"></i></span>
                                        <input type="text" name="name" class="form-control bg-white border-0" value="<?php echo e(old('name', $shop->name)); ?>" required>
                                    </div>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger mt-1 d-block"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark"><?php echo e(__('Phone Number')); ?></label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-telephone"></i></span>
                                        <input type="text" name="phone" class="form-control bg-white border-0" value="<?php echo e(old('phone', $shop->phone)); ?>" placeholder="<?php echo e(__('e.g. 0700 000 000')); ?>">
                                    </div>
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger mt-1 d-block"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Address -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark"><?php echo e(__('Address / Location')); ?></label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" name="address" class="form-control bg-white border-0" value="<?php echo e(old('address', $shop->address)); ?>" placeholder="<?php echo e(__('e.g. Kariakoo, DSM')); ?>">
                                    </div>
                                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger mt-1 d-block"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Business Category -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark"><?php echo e(__('Business Category')); ?> <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-briefcase"></i></span>
                                        <select id="business_type" name="business_type" class="form-control bg-white border-0" required style="cursor: pointer;">
                                            <option value="Retail / General" <?php echo e(old('business_type', $shop->business_type) == 'Retail / General' ? 'selected' : ''); ?>><?php echo e(__('Retail / General')); ?></option>
                                            <option value="Electronics / IT" <?php echo e(old('business_type', $shop->business_type) == 'Electronics / IT' ? 'selected' : ''); ?>><?php echo e(__('Electronics & IT (Phones, Computers, etc)')); ?></option>
                                            <option value="Pharmacy / Health" <?php echo e(old('business_type', $shop->business_type) == 'Pharmacy / Health' ? 'selected' : ''); ?>><?php echo e(__('Pharmacy / Health & Beauty')); ?></option>
                                            <option value="Supermarket / Grocery" <?php echo e(old('business_type', $shop->business_type) == 'Supermarket / Grocery' ? 'selected' : ''); ?>><?php echo e(__('Supermarket / Grocery')); ?></option>
                                            <option value="Restaurant / Food" <?php echo e(old('business_type', $shop->business_type) == 'Restaurant / Food' ? 'selected' : ''); ?>><?php echo e(__('Restaurant / Cafe / Food')); ?></option>
                                            <option value="Hardware / Construction" <?php echo e(old('business_type', $shop->business_type) == 'Hardware / Construction' ? 'selected' : ''); ?>><?php echo e(__('Hardware / Construction')); ?></option>
                                            <option value="Clothing / Boutique" <?php echo e(old('business_type', $shop->business_type) == 'Clothing / Boutique' ? 'selected' : ''); ?>><?php echo e(__('Clothing / Boutique')); ?></option>
                                            <option value="Services / Consulting" <?php echo e(old('business_type', $shop->business_type) == 'Services / Consulting' ? 'selected' : ''); ?>><?php echo e(__('Services / Consulting')); ?></option>
                                        </select>
                                    </div>
                                    <?php $__errorArgs = ['business_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger mt-1 d-block"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Receipt Footer Message -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark"><?php echo e(__('Receipt Footer Message')); ?></label>
                                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-muted align-items-start pt-3"><i class="bi bi-receipt"></i></span>
                                        <textarea name="receipt_message" class="form-control bg-white border-0" rows="3" placeholder="<?php echo e(__('e.g. Thank you for your business! Karibu tena.')); ?>"><?php echo e(old('receipt_message', $shop->receipt_message)); ?></textarea>
                                    </div>
                                    <?php $__errorArgs = ['receipt_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger mt-1 d-block"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-check-circle-fill"></i> <?php echo e(__('Save Changes')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling adjustments for professional look */
    .border-end-lg {
        border-right: 1px solid #eaeaea;
    }
    @media (max-width: 991.98px) {
        .border-end-lg {
            border-right: none;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
    }
    .input-group:focus-within {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
        transform: translateY(-1px);
        transition: all 0.2s ease-in-out;
    }
    .input-group-text {
        padding-right: 0;
    }
    .form-control:focus {
        box-shadow: none;
    }
    body {
        background-color: #f8f9fc;
    }
</style>

<!-- Coming Soon Overlay for Services/Consulting -->
<div id="coming-soon-overlay" class="position-fixed w-100 h-100 top-0 start-0 d-none align-items-center justify-content-center" style="background: rgba(248, 250, 252, 0.85); z-index: 9999; backdrop-filter: blur(4px); opacity: 0; transition: opacity 0.3s ease;">
    <div class="bg-white rounded-4 shadow-lg p-5 text-center" style="max-width: 450px; border: 1px solid rgba(0,0,0,0.08); transform: translateY(20px); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); opacity: 0;" id="coming-soon-modal">
        <div class="mb-4 d-flex justify-content-center">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="bi bi-briefcase text-dark" style="font-size: 2.5rem;"></i>
            </div>
        </div>
        <h2 class="fw-bold text-dark mb-2" style="font-size: 1.7rem;"><?php echo e(__('Services & Consulting')); ?></h2>
        <div class="mb-4">
            <span class="badge bg-dark px-3 py-2 rounded-pill fw-semibold" style="letter-spacing: 0.5px; font-size: 0.75rem;"><?php echo e(__('COMING SOON')); ?></span>
        </div>
        <p class="text-muted mb-4" style="font-size: 0.95rem; line-height: 1.6;">
            <?php echo e(__('A dedicated system for managing service and consulting businesses is in the final stages of development. Please select another business category for now.')); ?>

        </p>
        <button type="button" class="btn btn-dark rounded-pill px-5 py-3 fw-semibold w-100" onclick="closeComingSoon()">
            <?php echo e(__('Okay, Select Another')); ?>

        </button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const businessTypeSelect = document.getElementById('business_type');
        const comingSoonOverlay = document.getElementById('coming-soon-overlay');
        const comingSoonModal = document.getElementById('coming-soon-modal');
        
        function showOverlay() {
            comingSoonOverlay.classList.remove('d-none');
            comingSoonOverlay.classList.add('d-flex');
            void comingSoonOverlay.offsetWidth;
            comingSoonOverlay.style.opacity = '1';
            setTimeout(() => {
                comingSoonModal.style.transform = 'translateY(0)';
                comingSoonModal.style.opacity = '1';
            }, 50);
        }

        let previousValue = businessTypeSelect.value === 'Services / Consulting' ? '' : businessTypeSelect.value;

        // Show immediately if loaded with this value (e.g. from old validation or DB)
        if (businessTypeSelect.value === 'Services / Consulting') {
            showOverlay();
        }

        businessTypeSelect.addEventListener('change', function() {
            if (this.value === 'Services / Consulting') {
                showOverlay();
                this.value = previousValue;
            } else {
                previousValue = this.value;
            }
        });

        window.closeComingSoon = function() {
            comingSoonModal.style.transform = 'translateY(20px)';
            comingSoonModal.style.opacity = '0';
            setTimeout(() => {
                comingSoonOverlay.style.opacity = '0';
                setTimeout(() => {
                    comingSoonOverlay.classList.remove('d-flex');
                    comingSoonOverlay.classList.add('d-none');
                }, 300);
            }, 150);
        };
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\shop\settings.blade.php ENDPATH**/ ?>