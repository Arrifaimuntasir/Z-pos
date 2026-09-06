<?php $__env->startSection('title', 'Cookie Policy - Z-pos'); ?>
<?php $__env->startSection('content'); ?>
<?php use App\Models\CmsSetting; ?>
<div style="padding-top: 100px;">
    <section class="py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold text-primary display-5 mb-4"><?php echo e(CmsSetting::get('cookies', 'page_title', 'Cookie Policy')); ?></h2>
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" data-aos="fade-up">
                        <div class="text-muted lh-lg" style="white-space: pre-line;"><?php echo e(CmsSetting::get('cookies', 'content', '')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\pages\cookies.blade.php ENDPATH**/ ?>