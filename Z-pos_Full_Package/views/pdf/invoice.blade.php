<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->reference_no }}</title>
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
                @if($shop && $shop->logo_path)
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
                    @if($base64)
                        <img src="{{ $base64 }}" alt="Logo" style="max-height: 80px; margin-bottom: 10px;">
                    @else
                        <div class="company-name">{{ $shop->name }}</div>
                    @endif
                @else
                    <div class="company-name">{{ $shop ? $shop->name : 'Z-POS SYSTEM' }}</div>
                @endif
                
                @if($shop && $shop->address)
                    <div>{{ $shop->address }}</div>
                @endif
                @if($shop && $shop->phone)
                    <div>Tel: {{ $shop->phone }}</div>
                @endif
                @if($shop && $shop->tin_number)
                    <div>TIN: {{ $shop->tin_number }}</div>
                @endif
                @if($shop && $shop->email)
                    <div>Email: {{ $shop->email }}</div>
                @endif
            </td>
            <td class="text-right" style="width: 50%;">
                <div class="invoice-title">INVOICE</div>
                <div><span class="font-bold">Invoice No:</span> {{ $invoice->reference_no }}</div>
                <div><span class="font-bold">Date:</span> {{ \Carbon\Carbon::parse($invoice->sale_date)->format('d M Y') }}</div>
                <div><span class="font-bold">Status:</span> 
                    @if($invoice->payment_status == 'paid')
                        <span style="color: #27ae60;">PAID</span>
                    @elseif($invoice->payment_status == 'partial')
                        <span style="color: #f39c12;">PARTIAL</span>
                    @else
                        <span style="color: #e74c3c;">UNPAID</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="info-box mb-2">
        <div class="font-bold" style="margin-bottom: 5px; font-size: 12px; color: #7f8c8d; text-transform: uppercase;">Billed To:</div>
        <div style="font-size: 16px; font-weight: bold;">{{ $invoice->customer ? $invoice->customer->name : 'Walk-in Customer' }}</div>
        @if($invoice->customer && $invoice->customer->phone)
            <div>{{ $invoice->customer->phone }}</div>
        @endif
        @if($invoice->customer && $invoice->customer->address)
            <div>{{ $invoice->customer->address }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Item Description</th>
                <th class="text-center" style="width: 15%;">Quantity</th>
                <th class="text-right" style="width: 15%;">Unit Price</th>
                <th class="text-right" style="width: 20%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $item->product ? $item->product->name : 'Unknown Product' }}
                        @if($item->imei_serial_number)
                            <br><small style="color: #7f8c8d;">SN/IMEI: {{ $item->imei_serial_number }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price) }}</td>
                    <td class="text-right">{{ number_format($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none;"></td>
            <td style="width: 50%; border: none; padding: 0;">
                <table style="width: 100%; border: none; margin: 0;">
                    <tr>
                        <td style="border: none; padding: 5px 10px;" class="text-right font-bold">Subtotal:</td>
                        <td style="border: none; padding: 5px 10px;" class="text-right">{{ number_format($invoice->total_amount) }} TSh</td>
                    </tr>
                    <tr class="total-row">
                        <td style="border: none; padding: 10px;" class="text-right">Grand Total:</td>
                        <td style="border: none; padding: 10px;" class="text-right">{{ number_format($invoice->total_amount) }} TSh</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px;" class="text-right">Amount Paid:</td>
                        <td style="border: none; padding: 5px 10px;" class="text-right">{{ number_format($invoice->paid_amount) }} TSh</td>
                    </tr>
                    @if($invoice->total_amount - $invoice->paid_amount > 0)
                    <tr class="balance-row">
                        <td style="border: none; padding: 10px;" class="text-right">Balance Due:</td>
                        <td style="border: none; padding: 10px;" class="text-right">{{ number_format($invoice->total_amount - $invoice->paid_amount) }} TSh</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <div class="mt-4" style="border-top: 1px solid #ddd; padding-top: 20px; font-size: 12px; color: #7f8c8d;">
        @if($invoice->total_amount - $invoice->paid_amount > 0)
        <p><strong>Payment Terms:</strong> Please pay the balance due within the agreed period.</p>
        @endif
       <div class="footer">
        @if($shop && $shop->receipt_message)
            <p>{{ $shop->receipt_message }}</p>
        @else
            <p>Thank you for your business!</p>
        @endif
        <p style="margin-top: 10px; font-size: 10px; color: #777;">
            <strong>Powered by Z-POS SYSTEM</strong> - Smart Point of Sale & Inventory Management
        </p>
    </div>

</body>
</html>
