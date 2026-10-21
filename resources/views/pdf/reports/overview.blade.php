@include('pdf.reports._layout_start', ['reportTitle' => __('Reports Overview')])

<style>
    .kpi-card-table { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 12px; }
    .kpi-cell { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; vertical-align: top; width: 25%; }
    .kpi-lbl { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: bold; margin-bottom: 4px; }
    .kpi-val { font-size: 15px; font-weight: bold; color: #0f172a; }
    .kpi-val.text-success { color: #059669; }
    .kpi-val.text-danger { color: #dc2626; }
    .kpi-val.text-primary { color: #2563eb; }

    .biz-banner { background: #f1f5f9; border-left: 4px solid #2563eb; padding: 8px 12px; margin-bottom: 15px; border-radius: 0 6px 6px 0; }
    .biz-title { font-size: 11px; font-weight: bold; color: #1e293b; }
    .biz-badge { display: inline-block; background: #2563eb; color: #ffffff; font-size: 9px; font-weight: bold; padding: 2px 8px; border-radius: 4px; margin-left: 6px; }

    .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11px; }
    .data-table th { background: #0f172a; color: #ffffff; padding: 7px 8px; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.4px; font-weight: bold; border: 1px solid #0f172a; }
    .data-table td { padding: 6px 8px; border: 1px solid #e2e8f0; }
    .data-table tr:nth-child(even) td { background: #f8fafc; }
    .data-table tfoot td { font-weight: bold; background: #e2e8f0; border-top: 2px solid #0f172a; }

    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .badge-pill { display: inline-block; padding: 2px 6px; font-size: 9px; font-weight: bold; border-radius: 4px; }
    .badge-pill-green { background: #dcfce7; color: #166534; }
    .badge-pill-blue { background: #dbeafe; color: #1e40af; }
    .badge-pill-amber { background: #fef3c7; color: #92400e; }

    .sign-table { width: 100%; margin-top: 30px; border-collapse: collapse; border: none; }
    .sign-table td { border: none; padding: 0; vertical-align: top; width: 33.3%; }
    .sign-line { border-bottom: 1px dashed #94a3b8; width: 85%; height: 40px; margin-bottom: 5px; }
    .sign-label { font-size: 9.5px; color: #64748b; font-weight: bold; text-transform: uppercase; }
</style>

{{-- TOP KPI METRICS --}}
<table class="kpi-card-table">
    <tr>
        <td class="kpi-cell">
            <div class="kpi-lbl">{{ __('Total Products') }}</div>
            <div class="kpi-val text-primary">{{ number_format($totalProducts) }}</div>
        </td>
        <td class="kpi-cell">
            <div class="kpi-lbl">{{ __('Total Sales (All Time)') }}</div>
            <div class="kpi-val text-success">{{ number_format($totalSalesReal) }} TSh</div>
        </td>
        <td class="kpi-cell">
            <div class="kpi-lbl">{{ __('Total Expenses (All Time)') }}</div>
            <div class="kpi-val text-danger">{{ number_format($totalExpenses) }} TSh</div>
        </td>
        <td class="kpi-cell">
            <div class="kpi-lbl">{{ __('Net Profit (All Time)') }}</div>
            <div class="kpi-val {{ ($grossProfit - $totalExpenses) >= 0 ? 'text-success' : 'text-danger' }}">
                {{ number_format($grossProfit - $totalExpenses) }} TSh
            </div>
        </td>
    </tr>
    <tr>
        <td class="kpi-cell">
            <div class="kpi-lbl">{{ __('Total Purchases (All Time)') }}</div>
            <div class="kpi-val">{{ number_format($totalPurchases) }} TSh</div>
        </td>
        <td class="kpi-cell">
            <div class="kpi-lbl">{{ __('Total Refunds (All Time)') }}</div>
            <div class="kpi-val">{{ number_format($totalRefunds) }} TSh</div>
        </td>
        <td class="kpi-cell">
            <div class="kpi-lbl">{{ __('Stock Value (Cost)') }}</div>
            <div class="kpi-val">{{ number_format($totalStockValue) }} TSh</div>
        </td>
        <td class="kpi-cell">
            <div class="kpi-lbl">{{ __('Total Loss (Defective & Damaged)') }}</div>
            <div class="kpi-val text-danger">-{{ number_format($totalDefectiveLoss) }} TSh</div>
        </td>
    </tr>
</table>

{{-- BUSINESS CATEGORY CONTEXT --}}
<div class="biz-banner">
    <span class="biz-title">{{ __('Business Type Insights') }}:</span>
    <span class="biz-badge">{{ $businessType }}</span>
    <span style="font-size: 10px; color: #475569; margin-left: 10px;">
        @if(in_array($businessType, ['Supermarket / Grocery', 'Mini-Market', 'Pharmacy / Chemist']) && ($expiringProductsCount > 0 || $expiredProductsCount > 0))
            | <strong>{{ $expiringProductsCount }}</strong> {{ __('Expiring in 30 Days') }}
            @if($expiredProductsCount > 0), <strong class="text-danger">{{ $expiredProductsCount }} {{ __('Expired Products') }}</strong>@endif
        @endif
        @if($imeiTrackedCount > 0)
            | <strong>{{ $imeiTrackedCount }}</strong> {{ __('IMEI / Serial Tracked Items') }}
        @endif
    </span>
</div>

{{-- CATEGORY PERFORMANCE BREAKDOWN TABLE --}}
<div class="section-title" style="margin-top: 10px;">{{ __('Category Performance Breakdown') }}</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width: 25px;" class="text-center">#</th>
            <th>{{ __('Category Name') }}</th>
            <th class="text-center" style="width: 50px;">{{ __('Products') }}</th>
            <th class="text-center" style="width: 55px;">{{ __('Stock') }}</th>
            <th class="text-right" style="width: 85px;">{{ __('Cost Value') }}</th>
            <th class="text-center" style="width: 55px;">{{ __('Units Sold') }}</th>
            <th class="text-right" style="width: 95px;">{{ __('Total Sales') }}</th>
            <th class="text-right" style="width: 85px;">{{ __('Profit') }}</th>
            <th class="text-center" style="width: 55px;">{{ __('Margin') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sumProducts = 0;
            $sumStock = 0;
            $sumCost = 0;
            $sumSold = 0;
            $sumRevenue = 0;
            $sumProfit = 0;
        @endphp
        @forelse($categoryBreakdown as $idx => $cat)
            @php
                $sumProducts += $cat->product_count;
                $sumStock += $cat->total_stock;
                $sumCost += $cat->stock_cost;
                $sumSold += $cat->sold_qty;
                $sumRevenue += $cat->revenue;
                $sumProfit += $cat->profit;
            @endphp
            <tr>
                <td class="text-center" style="color: #64748b;">{{ $idx + 1 }}</td>
                <td>
                    <strong>{{ $cat->name }}</strong>
                    @if($idx === 0 && $cat->revenue > 0)
                        <span style="font-size: 8px; color: #059669; font-weight: bold;">(TOP)</span>
                    @endif
                </td>
                <td class="text-center">{{ number_format($cat->product_count) }}</td>
                <td class="text-center">{{ number_format($cat->total_stock) }}</td>
                <td class="text-right">{{ number_format($cat->stock_cost) }}</td>
                <td class="text-center"><strong>{{ number_format($cat->sold_qty) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($cat->revenue) }}</strong></td>
                <td class="text-right" style="color: #059669; font-weight: bold;">{{ number_format($cat->profit) }}</td>
                <td class="text-center">
                    <span class="badge-pill {{ $cat->margin >= 25 ? 'badge-pill-green' : ($cat->margin >= 15 ? 'badge-pill-blue' : 'badge-pill-amber') }}">
                        {{ $cat->margin }}%
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 15px; color: #94a3b8;">{{ __('No category data found') }}</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="text-right"><strong>{{ __('Total') }}</strong></td>
            <td class="text-center"><strong>{{ number_format($sumProducts) }}</strong></td>
            <td class="text-center"><strong>{{ number_format($sumStock) }}</strong></td>
            <td class="text-right"><strong>{{ number_format($sumCost) }} TSh</strong></td>
            <td class="text-center"><strong>{{ number_format($sumSold) }}</strong></td>
            <td class="text-right"><strong>{{ number_format($sumRevenue) }} TSh</strong></td>
            <td class="text-right" style="color: #059669;"><strong>{{ number_format($sumProfit) }} TSh</strong></td>
            <td class="text-center"><strong>{{ $sumRevenue > 0 ? round(($sumProfit / $sumRevenue) * 100, 1) : 0 }}%</strong></td>
        </tr>
    </tfoot>
</table>

{{-- INVENTORY & LOSS SUMMARY --}}
<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
    <tr>
        <td style="width: 49%; vertical-align: top; border: none; padding: 0 8px 0 0;">
            <div class="section-title">{{ __('Stock Value') }}</div>
            <table class="data-table">
                <tr><th>{{ __('Total Cost Value') }}</th><td class="text-right">{{ number_format($totalStockValue) }} TSh</td></tr>
                <tr><th>{{ __('Total Selling Value') }}</th><td class="text-right">{{ number_format($totalSalesValue) }} TSh</td></tr>
                <tr style="background: #ecfdf5;"><th>{{ __('Potential Profit from Stock') }}</th><td class="text-right" style="color: #059669; font-weight: bold;">{{ number_format($potentialProfit) }} TSh</td></tr>
            </table>
        </td>
        <td style="width: 49%; vertical-align: top; border: none; padding: 0 0 0 8px;">
            <div class="section-title">{{ __('Defective & Damaged Stock') }}</div>
            <table class="data-table">
                <tr><th>{{ __('Damaged Stock (Write-offs)') }}</th><td class="text-right text-danger">-{{ number_format($damagedStockLoss) }} TSh</td></tr>
                <tr><th>{{ __('Defective Returns (Unrepaired)') }}</th><td class="text-right text-danger">-{{ number_format($defectiveLoss) }} TSh</td></tr>
                <tr style="background: #fef2f2;"><th>{{ __('Total Loss') }}</th><td class="text-right text-danger" style="font-weight: bold;">-{{ number_format($totalDefectiveLoss) }} TSh</td></tr>
            </table>
        </td>
    </tr>
</table>

{{-- SIGNATURE & VERIFICATION BLOCK --}}
<table class="sign-table">
    <tr>
        <td>
            <div class="sign-line"></div>
            <div class="sign-label">{{ __('Prepared By') }}</div>
            <div style="font-size: 10px; color: #334155; margin-top: 2px;">{{ trim(($generatedBy->first_name ?? '') . ' ' . ($generatedBy->last_name ?? '')) ?: ($generatedBy->email ?? '') }}</div>
        </td>
        <td>
            <div class="sign-line"></div>
            <div class="sign-label">{{ __('Approved By') }}</div>
            <div style="font-size: 10px; color: #334155; margin-top: 2px;">{{ $shop->name ?? 'Z-POS' }} Management</div>
        </td>
        <td>
            <div class="sign-line"></div>
            <div class="sign-label">{{ __('Date & Stamp') }}</div>
            <div style="font-size: 10px; color: #334155; margin-top: 2px;">{{ now()->format('d M Y') }}</div>
        </td>
    </tr>
</table>

@include('pdf.reports._layout_end')
