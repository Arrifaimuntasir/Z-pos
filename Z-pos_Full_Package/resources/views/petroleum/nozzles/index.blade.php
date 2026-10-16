@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Nozzles') }}</h4>
        <span class="text-muted small">{{ __('Each nozzle draws fuel from one tank') }}</span>
    </div>
    @if(Auth::user()->hasRole('Administrator'))
    <a href="{{ route('petroleum.nozzles.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
        <i class="bi bi-plus-lg me-2"></i> {{ __('Add Nozzle') }}
    </a>
    @endif
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4">{{ __('Pump') }}</th>
                        <th>{{ __('Nozzle') }}</th>
                        <th>{{ __('Fuel Type') }}</th>
                        <th>{{ __('Tank') }}</th>
                        <th class="text-end">{{ __('Meter Reading') }}</th>
                        <th>{{ __('Status') }}</th>
                        @if(Auth::user()->hasRole('Administrator'))<th class="text-end pe-4">{{ __('Actions') }}</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($nozzles as $n)
                    <tr>
                        <td class="ps-4 fw-bold">{{ __('Pump') }} {{ $n->pump->number ?? '-' }}</td>
                        <td>{{ __('Nozzle') }} {{ $n->number }}</td>
                        <td>{{ $n->tank->product->name ?? '-' }}</td>
                        <td>{{ $n->tank->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($n->meter_reading, 3) }}</td>
                        <td>
                            @if($n->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3">{{ __(ucfirst($n->status)) }}</span>
                            @endif
                        </td>
                        @if(Auth::user()->hasRole('Administrator'))
                        <td class="text-end pe-4">
                            <a href="{{ route('petroleum.nozzles.edit', $n) }}" class="btn btn-sm btn-light text-dark shadow-sm rounded-3"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('petroleum.nozzles.destroy', $n) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this nozzle?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">{{ __('No nozzles yet. Add a pump first, then its nozzles.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
