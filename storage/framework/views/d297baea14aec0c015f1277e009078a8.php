<?php $__env->startSection('title', 'Testimonials - Z-pos'); ?>
<?php $__env->startSection('content'); ?>
<?php use App\Models\Testimonial; use App\Models\CmsSetting; ?>
<div style="padding-top: 100px;">
    <section id="testimonials" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5"><?php echo e(__('Loved by shop owners')); ?></h2>
                <p class="text-muted fs-5 mt-3"><?php echo e(__('See what our customers are saying about Z-pos.')); ?></p>
            </div>

            <div class="row g-4">
                <?php $__currentLoopData = Testimonial::active()->orderBy('sort_order')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo e(($index + 1) * 100); ?>">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex text-warning mb-3">
                                <?php for($s=1;$s<=5;$s++): ?>
                                    <i class="bi bi-star-fill<?php echo e($s > $t->rating ? ' opacity-25' : ''); ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="fst-italic text-muted mb-4">"<?php echo e($t->quote); ?>"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <div class="bg-<?php echo e($t->avatar_color); ?> text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 45px; height: 45px;"><?php echo e($t->avatar_initials); ?></div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-bold"><?php echo e($t->name); ?></h6>
                                    <span class="text-muted small"><?php echo e($t->position); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\pages\testimonials.blade.php ENDPATH**/ ?>