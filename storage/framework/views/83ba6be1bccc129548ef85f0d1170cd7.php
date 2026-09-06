<?php $__env->startSection('title', 'Manage Testimonials'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="mb-0 fw-bold"><i class="bi bi-chat-quote-fill me-2 text-warning"></i>Manage Testimonials</h4>
        </div>
        <div class="col-auto">
            <a href="<?php echo e(route('superadmin.testimonials.create')); ?>" class="btn fw-bold rounded-pill px-4 text-white" style="background-color:#64748b;">
                <i class="bi bi-plus-circle me-1"></i> Ongeza Testimonial
            </a>
        </div>
    </div>



    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <?php if($testimonials->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0">#</th>
                                <th class="px-4 py-3 border-0">Avatar</th>
                                <th class="px-4 py-3 border-0">Jina & Cheo</th>
                                <th class="px-4 py-3 border-0">Nukuu (Quote)</th>
                                <th class="px-4 py-3 border-0">Rating</th>
                                <th class="px-4 py-3 border-0">Hali</th>
                                <th class="px-4 py-3 border-0 text-center">Vitendo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4"><?php echo e($loop->iteration); ?></td>
                                <td class="px-4">
                                    <div class="bg-<?php echo e($t->avatar_color); ?> text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width:42px;height:42px;font-size:0.85rem;">
                                        <?php echo e($t->avatar_initials); ?>

                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="fw-semibold"><?php echo e($t->name); ?></div>
                                    <small class="text-muted"><?php echo e($t->position); ?></small>
                                </td>
                                <td class="px-4" style="max-width:300px;">
                                    <span class="text-muted small fst-italic">"<?php echo e(Str::limit($t->quote, 80)); ?>"</span>
                                </td>
                                <td class="px-4">
                                    <?php for($i=1;$i<=5;$i++): ?>
                                        <i class="bi bi-star-fill <?php echo e($i <= $t->rating ? 'text-warning' : 'text-muted'); ?>" style="font-size:0.75rem;"></i>
                                    <?php endfor; ?>
                                </td>
                                <td class="px-4">
                                    <?php if($t->is_active): ?>
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">Inaonyeshwa</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Imefichwa</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 text-center">
                                    <a href="<?php echo e(route('superadmin.testimonials.edit', $t)); ?>" class="btn btn-sm btn-outline-primary rounded-pill me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('superadmin.testimonials.destroy', $t)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Una uhakika unataka kufuta testimonial hii?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-chat-quote fs-1"></i>
                    <h5 class="mt-3">Hakuna testimonials bado</h5>
                    <a href="<?php echo e(route('superadmin.testimonials.create')); ?>" class="btn rounded-pill px-4 text-white mt-2" style="background-color:#64748b;">
                        <i class="bi bi-plus-circle me-1"></i> Ongeza ya Kwanza
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\superadmin\testimonials\index.blade.php ENDPATH**/ ?>