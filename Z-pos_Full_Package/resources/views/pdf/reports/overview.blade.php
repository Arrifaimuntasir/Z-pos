@include('pdf.reports._layout_start', ['reportTitle' => __('Reports Overview')])

<table class="kpi-table">
    <tr>
        <td><div class="kpi-label">{{ __('Total Products') }}</div><div class="kpi-value">{{ number_format($totalProducts) }}</div></td>
        <td><div class="kpi-label">{{ __('Total Sales (All Time)') }}</div><div class="kpi-value">{{ number_format($totalSalesReal) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Total Expenses (All Time)') }}</div><div class="kpi-value">{{ number_format($totalExpenses) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Net Profit (All Time)') }}</div><div class="kpi-value">{{ number_format($grossProfit - $totalExpenses) }} TSh</div></td>
    </tr>
    <tr>
        <td><div class="kpi-label">{{ __('Total Purchases (All Time)') }}</div><div class="kpi-value">{{ number_format($totalPurchases) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Total Refunds (All Time)') }}</div><div class="kpi-value">{{ number_format($totalRefunds) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Damaged Stock Items') }}</div><div class="kpi-value">{{ number_format($damagedStockQty) }}</div></td>
        <td><div class="kpi-label">{{ __('Total Loss (Defective & Damaged)') }}</div><div class="kpi-value text-danger">-{{ number_format($totalDefectiveLoss) }} TSh</div></td>
    </tr>
</table>

<div class="section-title">{{ __('Stock Value') }}</div>
<table>
    <tr><th>{{ __('Total Stock Value (at cost)') }}</th><th>{{ __('Total Stock Value (at selling price)') }}</th><th>{{ __('Potential Profit from Stock') }}</th></tr>
    <tr>
        <td>{{ number_format($totalStockValue) }} TSh</td>
        <td>{{ number_format($totalSalesValue) }} TSh</td>
        <td>{{ number_format($potentialProfit) }} TSh</td>
    </tr>
</table>

<div class="section-title">{{ __('Defective & Damaged Stock') }}</div>
<table>
    <tr><th>{{ __('Loss from Damaged Stock (Write-offs)') }}</th><th>{{ __('Loss from Defective Returns (Unrepaired)') }}</th><th>{{ __('Total Loss') }}</th></tr>
    <tr>
        <td class="text-danger">-{{ number_format($damagedStockLoss) }} TSh</td>
        <td class="text-danger">-{{ number_format($defectiveLoss) }} TSh</td>
        <td class="text-danger">-{{ number_format($totalDefectiveLoss) }} TSh</td>
    </tr>
</table>

@include('pdf.reports._layout_end')
