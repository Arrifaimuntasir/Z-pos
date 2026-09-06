<?php $__env->startSection('title', 'Manage Shops'); ?>

<?php $__env->startSection('content'); ?>
<?php
use Carbon\Carbon;

$tabs = [
    'all'       => ['label' => __('All Shops'),       'count' => $counts['all'],       'color' => 'primary',   'text_color' => 'text-primary'],
    'active'    => ['label' => __('Active'),          'count' => $counts['active'],    'color' => 'success',   'text_color' => 'text-success'],
    'pending'   => ['label' => __('Pending Verify'),  'count' => $counts['pending'],   'color' => 'warning',   'text_color' => 'text-dark'],
    'expired'   => ['label' => __('Expired'),         'count' => $counts['expired'],   'color' => 'danger',    'text_color' => 'text-danger'],
    'suspended' => ['label' => __('Suspended'),       'count' => $counts['suspended'], 'color' => 'secondary', 'text_color' => 'text-secondary'],
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold text-dark"><?php echo e(__('Manage Shops')); ?></h4>
</div>

<!-- Filter Pills -->
<div class="mb-4 d-flex flex-wrap gap-2">
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($filter === $key): ?>
            <a href="<?php echo e(route('superadmin.shops.index', ['filter' => $key, 'search' => request('search')])); ?>" 
               class="btn btn-sm rounded-pill px-3 fw-bold bg-<?php echo e($tab['color']); ?> text-white shadow-sm border-0">
                <?php echo e($tab['label']); ?>

                <span class="badge bg-white <?php echo e($tab['text_color']); ?> ms-1 rounded-pill"><?php echo e($tab['count']); ?></span>
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('superadmin.shops.index', ['filter' => $key, 'search' => request('search')])); ?>" 
               class="btn btn-sm rounded-pill px-3 fw-bold bg-<?php echo e($tab['color']); ?> bg-opacity-10 <?php echo e($tab['text_color']); ?> border-0">
                <?php echo e($tab['label']); ?>

                <span class="badge bg-<?php echo e($tab['color']); ?> bg-opacity-25 <?php echo e($tab['text_color']); ?> ms-1 rounded-pill"><?php echo e($tab['count']); ?></span>
            </a>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="card shadow-sm border-0 mb-4 rounded-4">
    <!-- Search Bar Header -->
    <div class="card-header bg-white border-bottom p-3">
        <form action="<?php echo e(route('superadmin.shops.index')); ?>" method="GET" class="d-flex w-100 flex-column flex-md-row gap-2">
            <input type="hidden" name="filter" value="<?php echo e($filter); ?>">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="<?php echo e(__('Search by shop name, TIN, or owner email...')); ?>" value="<?php echo e(request('search')); ?>">
            </div>
            <button type="submit" class="btn bg-primary bg-opacity-10 text-primary fw-bold px-4 border-0 rounded-3" style="min-width: 120px;">
                <?php echo e(__('Search')); ?>

            </button>
            <?php if(request('search')): ?>
                <a href="<?php echo e(route('superadmin.shops.index', ['filter' => $filter])); ?>" class="btn bg-secondary bg-opacity-10 text-secondary fw-bold px-4 border-0 rounded-3">
                    <?php echo e(__('Clear')); ?>

                </a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card-body p-0">
        <?php if($shops->isEmpty()): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-shop display-4 opacity-50 mb-3 d-block"></i>
                <h5 class="mb-0"><?php echo e(request('search') ? __('No shops matched your search.') : __('No shops found for this filter.')); ?></h5>
            </div>
        <?php else: ?>
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-lg-block">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 border-bottom-0 text-secondary" style="font-size: 0.85rem;"><?php echo e(__('Shop Info')); ?></th>
                            <th class="border-bottom-0 text-secondary" style="font-size: 0.85rem;"><?php echo e(__('Package')); ?></th>
                            <th class="border-bottom-0 text-secondary" style="font-size: 0.85rem;"><?php echo e(__('Contact Details')); ?></th>
                            <th class="border-bottom-0 text-secondary" style="font-size: 0.85rem;"><?php echo e(__('Dates')); ?></th>
                            <th class="border-bottom-0 text-secondary" style="font-size: 0.85rem;"><?php echo e(__('Status')); ?></th>
                            <th class="text-end pe-4 border-bottom-0 text-secondary" style="font-size: 0.85rem;"><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $expired = $shop->valid_until && Carbon::parse($shop->valid_until)->isPast();
                            $hasPending = $shop->payments->where('status', 'pending')->count() > 0;
                            $owner = $shop->users->first();
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <?php if($shop->logo_path): ?>
                                        <img src="<?php echo e(asset($shop->logo_path)); ?>" class="rounded-3 border me-3" width="45" height="45" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex justify-content-center align-items-center me-3 fw-bold" style="width: 45px; height: 45px; font-size: 1.2rem;">
                                            <?php echo e(strtoupper(substr(trim($shop->name), 0, 1))); ?>

                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark"><?php echo e($shop->name); ?></h6>
                                        <small class="text-muted"><?php echo e($shop->business_type ?? 'N/A'); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($shop->package): ?>
                                    <?php
                                        $pkgColor = 'secondary';
                                        $pkgText = 'secondary';
                                        if($shop->package == 'starter') { $pkgColor = 'info'; $pkgText = 'dark'; }
                                        if($shop->package == 'professional') { $pkgColor = 'success'; $pkgText = 'success'; }
                                        if($shop->package == 'enterprise') { $pkgColor = 'primary'; $pkgText = 'primary'; }
                                    ?>
                                    <span class="badge bg-<?php echo e($pkgColor); ?> bg-opacity-10 text-<?php echo e($pkgText); ?> border-0 rounded-pill px-3 fw-bold"><?php echo e(strtoupper($shop->package)); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php $__currentLoopData = $shop->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div style="font-size: 0.85rem;" class="text-dark"><i class="bi bi-envelope-fill text-muted me-1"></i> <?php echo e($u->email); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($shop->phone): ?>
                                    <div style="font-size: 0.85rem;" class="mt-1 text-dark"><i class="bi bi-telephone-fill text-muted me-1"></i> <?php echo e($shop->phone); ?></div>
                                <?php elseif($owner && isset($owner->phone)): ?>
                                    <div style="font-size: 0.85rem;" class="mt-1 text-dark"><i class="bi bi-telephone-fill text-muted me-1"></i> <?php echo e($owner->phone); ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 0.85rem;">
                                <div class="mb-1"><span class="text-muted">Reg:</span> <span class="text-dark fw-semibold"><?php echo e($shop->created_at->format('Y-m-d')); ?></span></div>
                                <div>
                                    <span class="text-muted">Valid:</span> 
                                    <?php if($shop->valid_until): ?>
                                        <span class="fw-bold <?php echo e($expired ? 'text-danger' : 'text-success'); ?>">
                                            <?php echo e(Carbon::parse($shop->valid_until)->format('Y-m-d')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted fw-semibold">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if(!$shop->is_active): ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 py-1 fw-bold">Suspended</span>
                                <?php elseif($expired): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-2 py-1 fw-bold">Expired</span>
                                <?php elseif($hasPending): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-dark border-0 px-2 py-1 fw-bold">Pending Verify</span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1 fw-bold">Active & Verified</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <?php if($hasPending): ?>
                                        <a href="<?php echo e(route('superadmin.payments.index')); ?>" class="btn btn-sm bg-warning bg-opacity-10 text-dark fw-bold border-0 rounded-3" title="Verify Payment">
                                            <i class="bi bi-check-circle-fill me-1"></i>Verify
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('superadmin.shops.edit', $shop)); ?>" class="btn btn-sm bg-primary bg-opacity-10 text-primary fw-bold border-0 rounded-3" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="<?php echo e(route('superadmin.shops.toggle-status', $shop)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php if($shop->is_active): ?>
                                            <button type="submit" class="btn btn-sm bg-warning bg-opacity-10 text-dark fw-bold border-0 rounded-3" onclick="return confirm('Change status?');">
                                                <i class="bi bi-pause-circle-fill"></i> Suspend
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm bg-success bg-opacity-10 text-success fw-bold border-0 rounded-3" onclick="return confirm('Change status?');">
                                                <i class="bi bi-play-circle-fill"></i> Activate
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                    <form action="<?php echo e(route('superadmin.shops.destroy', $shop)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm bg-danger bg-opacity-10 text-danger fw-bold border-0 rounded-3" onclick="return confirm('Permanently delete this shop and all its data?');">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="d-block d-lg-none bg-light p-3">
                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $expired = $shop->valid_until && Carbon::parse($shop->valid_until)->isPast();
                    $hasPending = $shop->payments->where('status', 'pending')->count() > 0;
                    $owner = $shop->users->first();
                ?>
                <div class="card mb-3 border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <?php if($shop->logo_path): ?>
                                    <img src="<?php echo e(asset($shop->logo_path)); ?>" class="rounded-3 border me-3" width="55" height="55" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex justify-content-center align-items-center me-3 fw-bold" style="width: 55px; height: 55px; font-size: 1.5rem;">
                                        <?php echo e(strtoupper(substr(trim($shop->name), 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1"><?php echo e($shop->name); ?></h5>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        <?php if($shop->package): ?>
                                            <?php
                                                $pkgColor = 'secondary';
                                                $pkgText = 'secondary';
                                                if($shop->package == 'starter') { $pkgColor = 'info'; $pkgText = 'dark'; }
                                                if($shop->package == 'professional') { $pkgColor = 'success'; $pkgText = 'success'; }
                                                if($shop->package == 'enterprise') { $pkgColor = 'primary'; $pkgText = 'primary'; }
                                            ?>
                                            <span class="badge bg-<?php echo e($pkgColor); ?> bg-opacity-10 text-<?php echo e($pkgText); ?> border-0 rounded-pill px-2 fw-bold"><?php echo e(strtoupper($shop->package)); ?></span>
                                        <?php endif; ?>

                                        <?php if(!$shop->is_active): ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 fw-bold">Suspended</span>
                                        <?php elseif($expired): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-2 fw-bold">Expired</span>
                                        <?php elseif($hasPending): ?>
                                            <span class="badge bg-warning bg-opacity-10 text-dark border-0 px-2 fw-bold">Pending Verify</span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 fw-bold">Active</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded p-2 mb-3" style="font-size: 0.85rem;">
                            <div class="mb-1 text-dark">
                                <?php $__currentLoopData = $shop->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <i class="bi bi-envelope-fill text-muted me-1"></i> <?php echo e($user->email); ?><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="text-dark">
                                <?php if($shop->phone): ?>
                                    <i class="bi bi-telephone-fill text-muted me-1"></i> <?php echo e($shop->phone); ?>

                                <?php elseif($owner && isset($owner->phone)): ?>
                                    <i class="bi bi-telephone-fill text-muted me-1"></i> <?php echo e($owner->phone); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3" style="font-size: 0.85rem;">
                            <div class="col-6">
                                <span class="text-muted">Reg:</span><br>
                                <span class="text-dark fw-bold"><?php echo e($shop->created_at->format('Y-m-d')); ?></span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">Valid:</span><br>
                                <?php if($shop->valid_until): ?>
                                    <span class="fw-bold <?php echo e($expired ? 'text-danger' : 'text-success'); ?>">
                                        <?php echo e(Carbon::parse($shop->valid_until)->format('Y-m-d')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-dark fw-bold">N/A</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <?php if($hasPending): ?>
                            <a href="<?php echo e(route('superadmin.payments.index')); ?>" class="btn bg-warning bg-opacity-10 text-dark fw-bold border-0 rounded-3 flex-fill">
                                Verify
                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('superadmin.shops.edit', $shop)); ?>" class="btn bg-primary bg-opacity-10 text-primary fw-bold border-0 rounded-3 flex-fill">
                                Edit
                            </a>
                            <form action="<?php echo e(route('superadmin.shops.toggle-status', $shop)); ?>" method="POST" class="flex-fill">
                                <?php echo csrf_field(); ?>
                                <?php if($shop->is_active): ?>
                                    <button type="submit" class="btn fw-bold w-100 border-0 rounded-3 bg-warning bg-opacity-10 text-dark" onclick="return confirm('Change status?');">
                                        Suspend
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn fw-bold w-100 border-0 rounded-3 bg-success bg-opacity-10 text-success" onclick="return confirm('Change status?');">
                                        Activate
                                    </button>
                                <?php endif; ?>
                            </form>
                            <form action="<?php echo e(route('superadmin.shops.destroy', $shop)); ?>" method="POST" class="flex-fill">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn bg-danger bg-opacity-10 text-danger border-0 rounded-3 fw-bold w-100" onclick="return confirm('Permanently delete?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views\superadmin\shops\index.blade.php ENDPATH**/ ?>