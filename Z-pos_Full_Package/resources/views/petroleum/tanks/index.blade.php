@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Fuel Tanks') }}</h4>
        <span class="text-muted small">{{ __('Fuel storage tanks at this station and how much is in each') }}</span>
    </div>
    @if(Auth::user()->hasRole('Administrator'))
    <a href="{{ route('petroleum.tanks.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
        <i class="bi bi-plus-lg me-2"></i> {{ __('Add Tank') }}
    </a>
    @endif
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4">{{ __('Tank') }}</th>
                        <th>{{ __('Fuel Type') }}</th>
                        <th class="text-end">{{ __('Capacity (L)') }}</th>
                        <th class="text-end">{{ __('Current Stock (L)') }}</th>
                        <th class="text-end">{{ __('Fill %') }}</th>
                        <th>{{ __('Status') }}</th>
                        @if(Auth::user()->hasRole('Administrator'))<th class="text-end pe-4">{{ __('Actions') }}</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($tanks as $tank)
                    @php $fill = $tank->capacity > 0 ? round(($tank->current_stock / $tank->capacity) * 100) : 0; @endphp
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $tank->name }}</td>
                        <td>{{ $tank->product->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($tank->capacity) }}</td>
                        <td class="text-end fw-bold">{{ number_format($tank->current_stock, 2) }}</td>
                        <td class="text-end">
                            <span class="badge {{ $tank->current_stock <= $tank->min_level ? 'bg-danger' : ($fill >= 80 ? 'bg-success' : 'bg-primary') }} rounded-pill px-3">{{ $fill }}%</span>
                        </td>
                        <td>
                            @if($tank->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3">{{ __(ucfirst($tank->status)) }}</span>
                            @endif
                        </td>
                        @if(Auth::user()->hasRole('Administrator'))
                        <td class="text-end pe-4">
                            <a href="{{ route('petroleum.tanks.edit', $tank) }}" class="btn btn-sm btn-light text-dark shadow-sm rounded-3"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('petroleum.tanks.destroy', $tank) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this tank?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-fuel-pump fs-1 d-block mb-3"></i>
                            {{ __('No tanks yet. Add your first tank to start tracking fuel.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
