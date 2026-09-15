<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #3b82f6; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .stats { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .stats td { padding: 10px 0; border-bottom: 1px solid #eee; }
        .stats td:last-child { text-align: right; font-weight: bold; }
        .products { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .products th { text-align: left; font-size: 12px; color: #888; padding: 6px 0; border-bottom: 1px solid #ddd; }
        .products td { padding: 6px 0; border-bottom: 1px solid #f2f2f2; }
        .footer { font-size: 12px; text-align: center; color: #888; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ __('Daily Sales Report') }}</h2>
            <div>{{ $shop->name }} &middot; {{ $date->format('M d, Y') }}</div>
        </div>
        <div class="content">
            <p>{{ __('Hello') }} {{ $owner->first_name }},</p>
            <p>{{ __('Here is your end-of-day summary for') }} <strong>{{ $date->format('l, M d, Y') }}</strong>:</p>

            <table class="stats">
                <tr><td>{{ __('Sales Made') }}</td><td>{{ number_format($salesCount) }}</td></tr>
                <tr><td>{{ __('Total Revenue') }}</td><td>{{ number_format($totalRevenue) }} TSh</td></tr>
                <tr><td>{{ __('Items Sold') }}</td><td>{{ number_format($itemsSold) }}</td></tr>
                <tr><td>{{ __('Gross Profit') }}</td><td>{{ number_format($grossProfit) }} TSh</td></tr>
                <tr><td>{{ __('Expenses Today') }}</td><td>{{ number_format($totalExpenses) }} TSh</td></tr>
                <tr><td>{{ __('Net Profit') }}</td><td>{{ number_format($grossProfit - $totalExpenses) }} TSh</td></tr>
            </table>

            @if($topProducts->count())
            <p><strong>{{ __('Top Products Today') }}</strong></p>
            <table class="products">
                <tr><th>{{ __('Product') }}</th><th>{{ __('Qty Sold') }}</th></tr>
                @foreach($topProducts as $p)
                <tr><td>{{ $p->name }}</td><td>{{ number_format($p->qty) }}</td></tr>
                @endforeach
            </table>
            @endif

            <p>{{ __('Thank you for using Z-pos!') }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Z-pos. All rights reserved.
        </div>
    </div>
</body>
</html>
