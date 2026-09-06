<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo e($sale->payment_status == 'proforma' ? 'Pro-Forma Invoice' : 'Receipt'); ?> <?php echo e($sale->reference_no); ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 5px; }
        .mt-2 { margin-top: 5px; }
        .border-bottom { border-bottom: 1px dashed #000; }
        .border-top { border-top: 1px dashed #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; }
        th, td { padding: 3px 0; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        .subtitle { font-size: 10px; color: #555; }
        .item-name { display: block; font-size: 11px; }
    </style>
</head>
<body>
    <div class="text-center mb-2 border-bottom" style="padding-bottom: 10px;">
        <div class="title"><?php echo e($shop ? $shop->name : 'Z-POS SYSTEM'); ?></div>
        <?php if($shop && $shop->address): ?>
            <div class="subtitle"><?php echo e($shop->address); ?></div>
        <?php endif; ?>
        <?php if($shop && $shop->phone): ?>
            <div class="subtitle">Tel: <?php echo e($shop->phone); ?></div>
        <?php endif; ?>

    </div>

    <div style="margin-bottom: 10px;">
        <div><span class="font-bold"><?php echo e($sale->payment_status == 'proforma' ? 'Pro-Forma Invoice' : 'Receipt'); ?>:</span> <?php echo e($sale->reference_no); ?></div>
        <div><span class="font-bold"><?php echo e(__('Date:')); ?></span> <?php echo e(\Carbon\Carbon::parse($sale->sale_date)->format('d-M-Y H:i')); ?></div>
        <div><span class="font-bold"><?php echo e(__('Customer:')); ?></span> <?php echo e($sale->customer ? $sale->customer->name : 'Walk-in'); ?></div>
    </div>

    <table>
        <thead>
            <tr class="border-bottom border-top">
                <th style="width: 45%;"><?php echo e(__('Product Name')); ?></th>
                <th class="text-center" style="width: 15%;"><?php echo e(__('Qty')); ?></th>
                <th class="text-right" style="width: 35%;"><?php echo e(__('Total')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($item->net_quantity > 0): ?>
                <tr>
                    <td class="text-left">
                        <span class="item-name"><?php echo e($item->product ? $item->product->name : 'Item'); ?></span>
                        <?php if($item->imei_serial_number): ?>
                            <span style="font-size: 10px; color: #333; display: block;">SN/IMEI: <?php echo e($item->imei_serial_number); ?></span>
                        <?php endif; ?>
                        <span style="font-size: 10px; color: #555;">@ <?php echo e(number_format($item->unit_price)); ?></span>
                    </td>
                    <td class="text-center"><?php echo e($item->net_quantity); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->net_total)); ?></td>
                </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <table style="margin-top: 5px;">
        <tr class="border-top">
            <td class="text-left font-bold" style="padding-top: 5px;"><?php echo e(__('Total Amount:')); ?></td>
            <td class="text-right font-bold" style="padding-top: 5px; font-size: 14px;"><?php echo e(number_format($sale->net_total_amount)); ?> TSh</td>
        </tr>
        <?php if($sale->payment_status != 'proforma'): ?>
            <tr>
                <td class="text-left"><?php echo e(__('Amount Paid:')); ?></td>
                <td class="text-right"><?php echo e(number_format($sale->paid_amount)); ?> TSh</td>
            </tr>
            <?php if($sale->net_total_amount - $sale->paid_amount > 0): ?>
            <tr>
                <td class="text-left font-bold"><?php echo e(__('Balance:')); ?></td>
                <td class="text-right font-bold"><?php echo e(number_format($sale->net_total_amount - $sale->paid_amount)); ?> TSh</td>
            </tr>
            <?php endif; ?>
        <?php else: ?>
            <tr>
                <td class="text-left font-bold"><?php echo e(__('Status:')); ?></td>
                <td class="text-right font-bold"><?php echo e(__('PRO-FORMA')); ?></td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="text-center mt-2 border-top" style="padding-top: 10px; font-size: 10px;">
        <?php if($shop && $shop->receipt_message): ?>
            <p><?php echo e($shop->receipt_message); ?></p>
        <?php else: ?>
            <p><?php echo e(__('Thank you for your business!')); ?></p>
        <?php endif; ?>
        <p><?php echo e(__('Powered by Z-POS')); ?></p>
    </div>
</body>
</html>
<?php /**PATH E:\Z-pos\resources\views\pdf\receipt.blade.php ENDPATH**/ ?>