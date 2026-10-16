<?php $__env->startSection('content'); ?>
<?php
    use App\Models\CmsSetting;

    $plans = [
        [
            'key' => 'starter',
            'name' => __(CmsSetting::get('pricing', 'starter_name', 'Starter')),
            'desc' => __(CmsSetting::get('pricing', 'starter_desc', 'Perfect for single retail shops.')),
            'monthly' => CmsSetting::monthlyPriceValue('starter'),
            'yearly' => CmsSetting::yearlyPriceValue('starter'),
            'features' => ['1 Branch', '2 Users', 'Unlimited Products', 'Inventory Management', 'Professional Invoicing', 'Custom Warranties', 'Advanced Analytics', 'Business Card'],
        ],
        [
            'key' => 'professional',
            'name' => __(CmsSetting::get('pricing', 'professional_name', 'Professional')),
            'desc' => __(CmsSetting::get('pricing', 'professional_desc', 'For growing multi-branch businesses.')),
            'monthly' => CmsSetting::monthlyPriceValue('professional'),
            'yearly' => CmsSetting::yearlyPriceValue('professional'),
            'features' => ['Up to 5 Branches', 'Unlimited Users', 'Unlimited Products', 'Inventory Management', 'Professional Invoicing', 'Custom Warranties', 'Advanced Analytics', 'Business Card'],
        ],
        [
            'key' => 'enterprise',
            'name' => __(CmsSetting::get('pricing', 'enterprise_name', 'Enterprise')),
            'desc' => __(CmsSetting::get('pricing', 'enterprise_desc', 'Custom solutions for large chains.')),
            'monthly' => CmsSetting::monthlyPriceValue('enterprise'),
            'yearly' => CmsSetting::yearlyPriceValue('enterprise'),
            'features' => ['Unlimited Branches', 'Unlimited Users', 'Unlimited Products', 'Inventory Management', 'Professional Invoicing', 'Custom Warranties', 'Advanced Analytics', 'Business Card'],
        ],
    ];
?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><?php echo e(__('Plans')); ?></h4>
        <span class="text-muted small"><?php echo e(__('All available subscription plans')); ?></span>
    </div>
</div>

<div class="d-flex justify-content-center mb-4">
    <div class="btn-group" role="group" aria-label="Billing cycle">
        <button type="button" id="billingMonthlyBtn" class="btn px-4" style="background-color: #64748b; color: white; border: 1px solid #64748b;" onclick="setPlansBilling('monthly')"><?php echo e(__('Monthly')); ?></button>
        <button type="button" id="billingYearlyBtn" class="btn px-4" style="background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;" onclick="setPlansBilling('yearly')"><?php echo e(__('Yearly')); ?></button>
    </div>
</div>

<div class="row g-4">
    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm <?php echo e($currentPackage === $plan['key'] ? 'border border-2 border-primary' : ''); ?>" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h4 class="fw-bold text-dark mb-0"><?php echo e($plan['name']); ?></h4>
                    <?php if($currentPackage === $plan['key']): ?>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><?php echo e(__('Current Plan')); ?></span>
                    <?php endif; ?>
                </div>
                <p class="text-muted small"><?php echo e($plan['desc']); ?></p>

                <div class="mb-3">
                    <span class="fs-3 fw-bold text-dark plans-price-monthly">TZS <?php echo e(number_format($plan['monthly'])); ?></span>
                    <span class="fs-3 fw-bold text-dark plans-price-yearly d-none">TZS <?php echo e(number_format($plan['yearly'])); ?></span>
                    <span class="text-muted plans-suffix-monthly">/<?php echo e(__('mo')); ?></span>
                    <span class="text-muted plans-suffix-yearly d-none">/<?php echo e(__('yr')); ?></span>
                </div>

                <ul class="list-unstyled mb-4 flex-grow-1">
                    <?php $__currentLoopData = $plan['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><?php echo e(__($feature)); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>

                <?php if($currentPackage !== $plan['key']): ?>
                <a href="<?php echo e(route('payments.expired', ['package' => $plan['key'], 'billing' => 'monthly'])); ?>"
                   class="btn btn-primary w-100 py-2 fw-semibold plans-upgrade-link"
                   data-package="<?php echo e($plan['key']); ?>"
                   style="border-radius: 8px;"><?php echo e(__('Upgrade')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<script>
function setPlansBilling(cycle) {
    document.querySelectorAll('.plans-price-monthly, .plans-suffix-monthly').forEach(function (el) {
        el.classList.toggle('d-none', cycle === 'yearly');
    });
    document.querySelectorAll('.plans-price-yearly, .plans-suffix-yearly').forEach(function (el) {
        el.classList.toggle('d-none', cycle !== 'yearly');
    });
    document.querySelectorAll('.plans-upgrade-link').forEach(function (el) {
        var url = new URL(el.href, window.location.origin);
        url.searchParams.set('billing', cycle);
        el.href = url.toString();
    });

    var activeStyle = 'background-color: #64748b; color: white; border: 1px solid #64748b;';
    var inactiveStyle = 'background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;';
    var monthlyBtn = document.getElementById('billingMonthlyBtn');
    var yearlyBtn = document.getElementById('billingYearlyBtn');
    monthlyBtn.style.cssText = cycle === 'monthly' ? activeStyle : inactiveStyle;
    yearlyBtn.style.cssText = cycle === 'yearly' ? activeStyle : inactiveStyle;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Z-pos\resources\views/plans/index.blade.php ENDPATH**/ ?>