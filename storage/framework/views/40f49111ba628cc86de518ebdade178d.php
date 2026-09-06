<?php $__env->startSection('title', 'Features - Z-pos'); ?>
<?php $__env->startSection('content'); ?>
<?php use App\Models\CmsSetting; ?>
<div style="padding-top: 100px;">
    <section id="features" class="py-5 bg-light" style="min-height: 80vh;">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-2 border border-success"><?php echo e(CmsSetting::get('features', 'page_badge', 'Core Features')); ?></span>
                <h2 class="fw-bold text-primary display-5"><?php echo e(CmsSetting::get('features', 'page_title', 'Everything you need to scale')); ?></h2>
                <p class="text-muted fs-5 mt-3 max-w-2xl mx-auto"><?php echo e(CmsSetting::get('features', 'page_subtitle', "From single shops to nationwide chains, we've got you covered.")); ?></p>
            </div>

            <div class="row g-4">
                <?php for($i = 1; $i <= 9; $i++): ?>
                <?php
                    $icons = ['bi-upc-scan','bi-box-seam','bi-pie-chart','bi-shield-check','bi-credit-card','bi-printer','bi-file-earmark-pdf','bi-shield-check','bi-receipt'];
                    $delays = [100,200,300,400,500,600,700,800,900];
                ?>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo e($delays[$i-1]); ?>">
                    <div class="feature-box p-4 bg-white rounded shadow-sm h-100">
                        <div class="icon-wrapper mb-3 text-primary fs-2"><i class="bi <?php echo e($icons[$i-1]); ?>"></i></div>
                        <h3><?php echo e(CmsSetting::get('features', 'feat_'.$i.'_title', 'Feature '.$i)); ?></h3>
                        <p class="text-muted"><?php echo e(CmsSetting::get('features', 'feat_'.$i.'_desc', '')); ?></p>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\pages\features.blade.php ENDPATH**/ ?>