<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $sale->payment_status == 'proforma' ? 'Pro-Forma Invoice' : 'Receipt' }} {{ $sale->reference_no }}</title>
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
        <div class="title">{{ $shop ? $shop->name : 'Z-POS SYSTEM' }}</div>
        @if($shop && $shop->address)
            <div class="subtitle">{{ $shop->address }}</div>
        @endif
        @if($shop && $shop->phone)
            <div class="subtitle">Tel: {{ $shop->phone }}</div>
        @endif
        @if($shop && $shop->tin_number)
            <div class="subtitle">TIN: {{ $shop->tin_number }}</div>
        @endif
    </div>

    <div style="margin-bottom: 10px;">
        <div><span class="font-bold">{{ $sale->payment_status == 'proforma' ? 'Pro-Forma Invoice' : 'Receipt' }}:</span> {{ $sale->reference_no }}</div>
        <div><span class="font-bold">Date:</span> {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-M-Y H:i') }}</div>
        <div><span class="font-bold">Customer:</span> {{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</div>
    </div>

    <table>
        <thead>
            <tr class="border-bottom border-top">
                <th class="text-left" style="width: 50%;">Item</th>
                <th class="text-center" style="width: 15%;">Qty</th>
                <th class="text-right" style="width: 35%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td class="text-left">
                        <span class="item-name">{{ $item->product ? $item->product->name : 'Item' }}</span>
                        @if($item->imei_serial_number)
                            <span style="font-size: 10px; color: #333; display: block;">SN/IMEI: {{ $item->imei_serial_number }}</span>
                        @endif
                        <span style="font-size: 10px; color: #555;">@ {{ number_format($item->unit_price) }}</span>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="margin-top: 5px;">
        <tr class="border-top">
            <td class="text-left font-bold" style="padding-top: 5px;">Total Amount:</td>
            <td class="text-right font-bold" style="padding-top: 5px; font-size: 14px;">{{ number_format($sale->total_amount) }} TSh</td>
        </tr>
        @if($sale->payment_status != 'proforma')
            <tr>
                <td class="text-left">Amount Paid:</td>
                <td class="text-right">{{ number_format($sale->paid_amount) }} TSh</td>
            </tr>
            @if($sale->total_amount - $sale->paid_amount > 0)
            <tr>
                <td class="text-left font-bold">Balance:</td>
                <td class="text-right font-bold">{{ number_format($sale->total_amount - $sale->paid_amount) }} TSh</td>
            </tr>
            @endif
        @else
            <tr>
                <td class="text-left font-bold">Status:</td>
                <td class="text-right font-bold">PRO-FORMA</td>
            </tr>
        @endif
    </table>

    <div class="text-center mt-2 border-top" style="padding-top: 10px; font-size: 10px;">
        @if($shop && $shop->receipt_message)
            <p>{{ $shop->receipt_message }}</p>
        @else
            <p>Thank you for your business!</p>
        @endif
        <p>Powered by Z-POS</p>
    </div>
</body>
</html>
