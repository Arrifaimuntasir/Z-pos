<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice <?php echo e($invoice->reference_no); ?></title>
    <style>
        @page { margin: 30px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; margin: 0; padding: 0; color: #333; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mt-4 { margin-top: 20px; }
        .mb-2 { margin-bottom: 10px; }
        .p-2 { padding: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 20px; }
        th, td { padding: 12px 10px; border: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; text-align: left; }
        
        .header-table { width: 100%; border: none; margin-bottom: 30px; }
        .header-table td { border: none; padding: 0; vertical-align: top; }
        
        .company-name { font-size: 24px; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
        .invoice-title { font-size: 28px; color: #2c3e50; font-weight: bold; text-transform: uppercase; margin-bottom: 10px;}
        
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .total-row td { font-weight: bold; border-top: 2px solid #333; background: #f8f9fa;}
        .balance-row td { font-size: 16px; font-weight: bold; color: #e74c3c; }
    </style>
</head>
<body>
    
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <?php if($shop && $shop->logo_path): ?>
                    <?php 
                        $logoPath = public_path($shop->logo_path);
                        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                        if (file_exists($logoPath)) {
                            $data = file_get_contents($logoPath);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        } else {
                            $base64 = null;
                        }
                    ?>
                    <?php if($base64): ?>
                        <img src="<?php echo e($base64); ?>" alt="Logo" style="max-height: 80px; margin-bottom: 10px;">
                    <?php else: ?>
                        <div class="company-name"><?php echo e($shop->name); ?></div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="company-name"><?php echo e($shop ? $shop->name : 'Z-POS SYSTEM'); ?></div>
                <?php endif; ?>
                
                <?php if($shop && $shop->address): ?>
                    <div><?php echo e($shop->address); ?></div>
                <?php endif; ?>
                <?php if($shop && $shop->phone): ?>
                    <div>Tel: <?php echo e($shop->phone); ?></div>
                <?php endif; ?>
                <?php if($shop && $shop->tin_number): ?>
                    <div>TIN: <?php echo e($shop->tin_number); ?></div>
                <?php endif; ?>
                <?php if($shop && $shop->email): ?>
                    <div>Email: <?php echo e($shop->email); ?></div>
                <?php endif; ?>
            </td>
            <td class="text-right" style="width: 50%;">
                <div class="invoice-title"><?php echo e(__('INVOICE')); ?></div>
                <div><span class="font-bold"><?php echo e(__('Invoice No:')); ?></span> <?php echo e($invoice->reference_no); ?></div>
                <div><span class="font-bold"><?php echo e(__('Date:')); ?></span> <?php echo e(\Carbon\Carbon::parse($invoice->sale_date)->format('d M Y')); ?></div>
                <div><span class="font-bold"><?php echo e(__('Status:')); ?></span> 
                    <?php if($invoice->payment_status == 'paid'): ?>
                        <span style="color: #27ae60;"><?php echo e(__('PAID')); ?></span>
                    <?php elseif($invoice->payment_status == 'partial'): ?>
                        <span style="color: #f39c12;"><?php echo e(__('PARTIAL')); ?></span>
                    <?php else: ?>
                        <span style="color: #e74c3c;"><?php echo e(__('UNPAID')); ?></span>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="info-box mb-2">
        <div class="font-bold" style="margin-bottom: 5px; font-size: 12px; color: #7f8c8d; text-transform: uppercase;"><?php echo e(__('Billed To:')); ?></div>
        <div style="font-size: 16px; font-weight: bold;"><?php echo e($invoice->customer ? $invoice->customer->name : 'Walk-in Customer'); ?></div>
        <?php if($invoice->customer && $invoice->customer->phone): ?>
            <div><?php echo e($invoice->customer->phone); ?></div>
        <?php endif; ?>
        <?php if($invoice->customer && $invoice->customer->address): ?>
            <div><?php echo e($invoice->customer->address); ?></div>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;"><?php echo e(__('Product Name')); ?></th>
                <th class="text-center" style="width: 15%;"><?php echo e(__('Quantity')); ?></th>
                <th class="text-right" style="width: 15%;"><?php echo e(__('Unit Price')); ?></th>
                <th class="text-right" style="width: 20%;"><?php echo e(__('Amount')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($item->net_quantity > 0): ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td>
                        <?php echo e($item->product ? $item->product->name : 'Unknown Product'); ?>

                        <?php if($item->imei_serial_number): ?>
                            <br><small style="color: #7f8c8d;">SN/IMEI: <?php echo e($item->imei_serial_number); ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?php echo e($item->net_quantity); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->unit_price)); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->net_total)); ?></td>
                </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none;"></td>
            <td style="width: 50%; border: none; padding: 0;">
                <table style="width: 100%; border: none; margin: 0;">
                    <tr>
                        <td style="border: none; padding: 5px 10px;" class="text-right font-bold"><?php echo e(__('Subtotal:')); ?></td>
                        <td style="border: none; padding: 5px 10px;" class="text-right"><?php echo e(number_format($invoice->net_total_amount)); ?> TSh</td>
                    </tr>
                    <tr class="total-row">
                        <td style="border: none; padding: 10px;" class="text-right"><?php echo e(__('Grand Total:')); ?></td>
                        <td style="border: none; padding: 10px;" class="text-right"><?php echo e(number_format($invoice->net_total_amount)); ?> TSh</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px;" class="text-right"><?php echo e(__('Amount Paid:')); ?></td>
                        <td style="border: none; padding: 5px 10px;" class="text-right"><?php echo e(number_format($invoice->paid_amount)); ?> TSh</td>
                    </tr>
                    <?php if($invoice->net_total_amount - $invoice->paid_amount > 0): ?>
                    <tr class="balance-row">
                        <td style="border: none; padding: 10px;" class="text-right"><?php echo e(__('Balance Due:')); ?></td>
                        <td style="border: none; padding: 10px;" class="text-right"><?php echo e(number_format($invoice->net_total_amount - $invoice->paid_amount)); ?> TSh</td>
                    </tr>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
    </table>

    <div class="mt-4" style="border-top: 1px solid #ddd; padding-top: 20px; font-size: 12px; color: #7f8c8d;">
        <?php if($invoice->total_amount - $invoice->paid_amount > 0): ?>
        <p><strong><?php echo e(__('Payment Terms:')); ?></strong> <?php echo e(__('Please pay the balance due within the agreed period.')); ?></p>
        <?php endif; ?>
       <div class="footer">
        <?php if($shop && $shop->receipt_message): ?>
            <p><?php echo e($shop->receipt_message); ?></p>
        <?php else: ?>
            <p><?php echo e(__('Thank you for your business!')); ?></p>
        <?php endif; ?>
        <p style="margin-top: 10px; font-size: 10px; color: #777;">
            <strong><?php echo e(__('Powered by Z-POS SYSTEM')); ?></strong> <?php echo e(__('- Smart Point of Sale & Inventory Management')); ?>

        </p>
    </div>

</body>
</html>
<?php /**PATH E:\Z-pos\resources\views\pdf\invoice.blade.php ENDPATH**/ ?>