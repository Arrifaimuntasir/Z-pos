<?php $__env->startSection('title', 'Contact Us - Z-pos'); ?>
<?php $__env->startSection('content'); ?>
<?php use App\Models\CmsSetting;
$phones = explode('|', CmsSetting::get('contact', 'phone', '+255 683 628 142 | +255 716 465 511'));
?>
<div style="padding-top: 100px;">
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5"><?php echo e(CmsSetting::get('contact', 'page_title', 'Get in Touch')); ?></h2>
                <p class="text-muted fs-5 mt-3"><?php echo e(CmsSetting::get('contact', 'page_subtitle', "We'd love to hear from you.")); ?></p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" data-aos="fade-up" data-aos-delay="100">
                        <div class="row g-4">
                            <div class="col-md-5 border-md-end pe-md-4">
                                <h4 class="fw-bold mb-4"><?php echo e(__('Contact Information')); ?></h4>

                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-geo-alt"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1"><?php echo e(__('Our Office')); ?></h6>
                                        <p class="text-muted mb-0"><?php echo e(CmsSetting::get('contact', 'address', 'Uhuru Plaza Kkoo, Dar es Salaam, Tanzania')); ?></p>
                                    </div>
                                </div>

                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-envelope"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1"><?php echo e(__('Email Us')); ?></h6>
                                        <p class="text-muted mb-0"><?php echo e(CmsSetting::get('contact', 'email', 'info@z-pos.co.tz')); ?></p>
                                    </div>
                                </div>

                                <div class="d-flex mb-4">
                                    <div class="text-success fs-3 me-3"><i class="bi bi-telephone"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-1"><?php echo e(__('Call Us')); ?></h6>
                                        <p class="text-muted mb-0">
                                            <?php $__currentLoopData = $phones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(trim($phone)); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7 ps-md-4">
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success rounded-3 mb-4">
                                        <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('success')); ?>

                                    </div>
                                <?php endif; ?>
                                <form action="<?php echo e(route('contact.submit')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><?php echo e(__('Full Name')); ?></label>
                                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(__('Enter your name')); ?>" value="<?php echo e(old('name')); ?>" required>
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><?php echo e(__('Email Address')); ?></label>
                                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(__('Enter your email')); ?>" value="<?php echo e(old('email')); ?>" required>
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><?php echo e(__('Phone Number')); ?></label>
                                        <input type="tel" name="phone" class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(__('Enter your phone number')); ?>" value="<?php echo e(old('phone')); ?>">
                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold"><?php echo e(__('Message')); ?></label>
                                        <textarea name="message" class="form-control bg-light border-0 <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4" placeholder="<?php echo e(__('How can we help you?')); ?>" required><?php echo e(old('message')); ?></textarea>
                                        <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <button type="submit" class="btn btn-success text-white w-100 py-3 fw-bold rounded-3"><?php echo e(__('Send Message')); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\pages\contact.blade.php ENDPATH**/ ?>