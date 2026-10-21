@include('pdf.reports._layout_start', [
    'reportTitle' => __('Stock Movement'),
])

@if($search)
<p class="meta">{{ __('Filtered by') }}: "{{ $search }}"</p>
@endif

<table>
    <thead>
        <tr>
            <th>{{ __('Product') }}</th>
            <th>{{ __('Category') }}</th>
            <th class="text-right">{{ __('Opening Stock') }}</th>
            <th class="text-right">{{ __('Sold') }}</th>
            <th class="text-right">{{ __('Damaged') }}</th>
            <th class="text-right">{{ __('Remaining') }}</th>
            <th>{{ __('Movement') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $row)
        <tr>
            <td>{{ $row->product->name }}{{ $row->product->model ? ' - ' . $row->product->model : '' }}</td>
            <td>{{ $row->product->category->name ?? '-' }}</td>
            <td class="text-right">{{ number_format($row->opening) }} {{ $row->product->unit->short_name ?? '' }}</td>
            <td class="text-right">{{ number_format($row->sold) }} {{ $row->product->unit->short_name ?? '' }}</td>
            <td class="text-right" style="{{ ($row->damaged ?? 0) > 0 ? 'color: #dc2626; font-weight: bold;' : '' }}">{{ number_format($row->damaged ?? 0) }} {{ $row->product->unit->short_name ?? '' }}</td>
            <td class="text-right">{{ number_format($row->current) }} {{ $row->product->unit->short_name ?? '' }}</td>
            <td>
                @if($row->sellThrough === null)
                    {{ __('No Activity') }}
                @elseif($row->sellThrough >= 60)
                    {{ __('Fast Moving') }} ({{ $row->sellThrough }}%)
                @elseif($row->sellThrough >= 20)
                    {{ __('Moderate') }} ({{ $row->sellThrough }}%)
                @else
                    {{ __('Slow Moving') }} ({{ $row->sellThrough }}%)
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">{{ __('No products found.') }}</td></tr>
        @endforelse
    </tbody>
</table>

@include('pdf.reports._layout_end')
