@include('pdf.reports._layout_start', [
    'reportTitle' => __('Profit & Loss Report'),
    'dateRangeLabel' => \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y'),
])

<table class="kpi-table">
    <tr>
        <td><div class="kpi-label">{{ __('Gross Profit') }}</div><div class="kpi-value">{{ number_format($grossProfit) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Total Expenses') }}</div><div class="kpi-value">{{ number_format($totalExpenses) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Net Profit / Loss') }}</div><div class="kpi-value {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($netProfit) }} TSh</div></td>
    </tr>
</table>

@if($defectiveLoss > 0 || $damagedStockLoss > 0)
<div class="section-title">{{ __('Losses already included in Gross Profit above') }}</div>
<table>
    <tr><th>{{ __('Defective Stock Loss (Unrepaired)') }}</th><th>{{ __('Damaged Stock Loss (Write-offs)') }}</th></tr>
    <tr>
        <td class="text-danger">-{{ number_format($defectiveLoss) }} TSh</td>
        <td class="text-danger">-{{ number_format($damagedStockLoss) }} TSh</td>
    </tr>
</table>
@endif

@include('pdf.reports._layout_end')
