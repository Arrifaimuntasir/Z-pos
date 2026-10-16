@include('pdf.reports._layout_start', [
    'reportTitle' => __('Expenses Report'),
    'dateRangeLabel' => \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y'),
])

<table class="kpi-table">
    <tr>
        <td><div class="kpi-label">{{ __('Total Expenses') }}</div><div class="kpi-value text-danger">{{ number_format($totalExpenses) }} TSh</div></td>
        <td><div class="kpi-label">{{ __('Transactions') }}</div><div class="kpi-value">{{ number_format($expenses->count()) }}</div></td>
    </tr>
</table>

<div class="section-title">{{ __('Expenses') }}</div>
<table>
    <thead>
        <tr>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Category') }}</th>
            <th>{{ __('Description') }}</th>
            <th class="text-right">{{ __('Amount') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($expenses as $expense)
        <tr>
            <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
            <td>{{ $expense->category }}</td>
            <td>{{ $expense->description ?? '-' }}</td>
            <td class="text-right">{{ number_format($expense->amount) }} TSh</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center">{{ __('No expenses found for the selected period.') }}</td></tr>
        @endforelse
    </tbody>
    @if($expenses->isNotEmpty())
    <tfoot>
        <tr class="total-row">
            <td colspan="3" class="text-right">{{ __('Total') }}</td>
            <td class="text-right">{{ number_format($totalExpenses) }} TSh</td>
        </tr>
    </tfoot>
    @endif
</table>

@include('pdf.reports._layout_end')
