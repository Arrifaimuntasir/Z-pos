<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #3b82f6; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; text-align: center; color: #888; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo e(__('Subscription Expiring Soon')); ?></h2>
        </div>
        <div class="content">
            <p><?php echo e(__('Hello,')); ?></p>
            <p><?php echo e(__('This is a friendly reminder that the subscription for your shop,')); ?> <strong><?php echo e($shop->name); ?></strong><?php echo e(__(', is expiring in')); ?> <strong><?php echo e($daysLeft); ?> days</strong> (on <?php echo e(\Carbon\Carbon::parse($shop->valid_until)->format('M d, Y')); ?>).</p>
            <p><?php echo e(__('To avoid any interruption to your service, please make a payment and upload your receipt in the system.')); ?></p>
            <p><?php echo e(__('Thank you for choosing Z-pos!')); ?></p>
        </div>
        <div class="footer">
            &copy; <?php echo e(date('Y')); ?> Zamar Store / Z-pos. All rights reserved.
        </div>
    </div>
</body>
</html>
<?php /**PATH E:\Z-pos\resources\views\emails\subscription_reminder.blade.php ENDPATH**/ ?>