<?php $__env->startSection('title', 'Edit Testimonial'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h4 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-warning"></i>Hariri Testimonial</h4>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="<?php echo e(route('superadmin.testimonials.update', $testimonial)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <?php echo $__env->make('superadmin.testimonials._form', ['testimonial' => $testimonial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo e(route('superadmin.testimonials.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4">Ghairi</a>
                            <button type="submit" class="btn text-white fw-bold rounded-pill px-5" style="background-color:#64748b;">
                                <i class="bi bi-save me-1"></i> Sasisha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\superadmin\testimonials\edit.blade.php ENDPATH**/ ?>