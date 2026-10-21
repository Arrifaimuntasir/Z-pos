@include('pdf.reports._layout_start', [
    'reportTitle' => __('Damaged Stock Report'),
    'dateRangeLabel' => \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y'),
])

<table class="kpi-table">
    <tr>
        <td>
            <div class="kpi-label">{{ __('Total Loss') }}</div>
            <div class="kpi-value text-danger">-{{ number_format($totalLoss) }} TSh</div>
        </td>
        <td>
            <div class="kpi-label">{{ __('Damaged Units') }}</div>
            <div class="kpi-value">{{ number_format($totalQty) }}</div>
        </td>
        <td>
            <div class="kpi-label">{{ __('Incidents Recorded') }}</div>
            <div class="kpi-value">{{ number_format($records->count()) }}</div>
        </td>
    </tr>
</table>

@if($search)
<p class="meta" style="font-size: 10px; color: #64748b; margin-bottom: 10px;">{{ __('Filtered by') }}: "{{ $search }}"</p>
@endif

<div class="section-title">{{ __('Damaged & Defective Stock Write-offs') }}</div>
<table>
    <thead>
        <tr>
            <th style="width: 75px;">{{ __('Date') }}</th>
            <th>{{ __('Product') }}</th>
            <th class="text-right" style="width: 70px;">{{ __('Cost Price') }}</th>
            <th class="text-right" style="width: 60px;">{{ __('Quantity') }}</th>
            <th class="text-right" style="width: 80px;">{{ __('Total Loss') }}</th>
            <th>{{ __('Reason') }}</th>
            <th style="width: 80px;">{{ __('Recorded By') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($records as $rec)
        @php
            $cost = $rec->cost_price ?? ($rec->product->cost_price ?? 0);
            $loss = $rec->loss ?? ($rec->quantity * $cost);
            $dateStr = \Carbon\Carbon::parse($rec->created_at)->format('d M Y');
        @endphp
        <tr>
            <td>
                {{ $dateStr }}
                @if(isset($rec->source_label))
                    <br><span style="font-size: 8px; color: #dc2626; font-weight: bold;">[{{ $rec->source_label }}]</span>
                @endif
            </td>
            <td>
                <strong>{{ $rec->product->name ?? 'Unknown' }}</strong>
                @if($rec->product && $rec->product->sku)
                    <br><span style="font-size: 8.5px; color: #64748b;">SKU: {{ $rec->product->sku }}</span>
                @endif
            </td>
            <td class="text-right">{{ number_format($cost) }} TSh</td>
            <td class="text-right" style="color: #dc2626; font-weight: bold;">-{{ number_format($rec->quantity) }} {{ $rec->product->unit->short_name ?? '' }}</td>
            <td class="text-right" style="color: #dc2626; font-weight: bold;">-{{ number_format($loss) }} TSh</td>
            <td>{{ $rec->reason ?: '-' }}</td>
            <td>{{ $rec->user->name ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">{{ __('No damaged stock recorded for the selected period.') }}</td></tr>
        @endforelse
    </tbody>
    @if($records->isNotEmpty())
    <tfoot>
        <tr class="total-row">
            <td colspan="3" class="text-right">{{ __('Total') }}</td>
            <td class="text-right" style="color: #dc2626;">{{ number_format($totalQty) }}</td>
            <td class="text-right" style="color: #dc2626;">-{{ number_format($totalLoss) }} TSh</td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
    @endif
</table>

@include('pdf.reports._layout_end')
