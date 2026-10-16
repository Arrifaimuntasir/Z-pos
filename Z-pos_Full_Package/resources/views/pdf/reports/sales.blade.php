@include('pdf.reports._layout_start', [
    'reportTitle' => __('Sales Report'),
    'dateRangeLabel' => \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y'),
])

<table class="kpi-table">
    <tr>
        <td><div class="kpi-label">{{ __('Total Sales') }}</div><div class="kpi-value">{{ number_format($totalSales) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Refunds') }}</div><div class="kpi-value text-danger">-{{ number_format($totalRefunds) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Transactions') }}</div><div class="kpi-value">{{ number_format($sales->count()) }}</div></td>
    </tr>
</table>

<div class="section-title">{{ __('Sales') }}</div>
<table>
    <thead>
        <tr>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Reference') }}</th>
            <th>{{ __('Customer') }}</th>
            <th>{{ __('Status') }}</th>
            <th class="text-right">{{ __('Amount') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
        <tr>
            <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
            <td>{{ $sale->reference_no }}</td>
            <td>{{ $sale->customer->name ?? __('Walk-in Customer') }}</td>
            <td>{{ ucfirst($sale->payment_status) }}</td>
            <td class="text-right">{{ number_format($sale->total_amount) }} TSh</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">{{ __('No sales found for the selected period.') }}</td></tr>
        @endforelse
    </tbody>
    @if($sales->isNotEmpty())
    <tfoot>
        <tr class="total-row">
            <td colspan="4" class="text-right">{{ __('Total') }}</td>
            <td class="text-right">{{ number_format($sales->sum('total_amount')) }} TSh</td>
        </tr>
    </tfoot>
    @endif
</table>

@include('pdf.reports._layout_end')
