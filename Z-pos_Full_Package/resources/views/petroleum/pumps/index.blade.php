@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Fuel Pumps') }}</h4>
        <span class="text-muted small">{{ __('Each pump and the nozzles it has') }}</span>
    </div>
    @if(Auth::user()->hasRole('Administrator'))
    <a href="{{ route('petroleum.pumps.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
        <i class="bi bi-plus-lg me-2"></i> {{ __('Add Pump') }}
    </a>
    @endif
</div>

<div class="row g-3">
    @forelse($pumps as $pump)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="fw-bold mb-0">{{ __('Pump') }} {{ $pump->number }}@if($pump->name) — {{ $pump->name }}@endif</h5>
                        <small class="text-muted">{{ $pump->location ?? '' }}</small>
                    </div>
                    @if($pump->status === 'active')
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('Active') }}</span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3">{{ __(ucfirst($pump->status)) }}</span>
                    @endif
                </div>
                <ul class="list-unstyled small mb-3">
                    @forelse($pump->nozzles as $n)
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span>{{ __('Nozzle') }} {{ $n->number }} — {{ $n->tank->product->name ?? '-' }} <span class="text-muted">({{ $n->tank->name ?? '' }})</span></span>
                            <span class="text-muted">{{ number_format($n->meter_reading, 3) }}</span>
                        </li>
                    @empty
                        <li class="text-muted">{{ __('No nozzles yet') }}</li>
                    @endforelse
                </ul>
                @if(Auth::user()->hasRole('Administrator'))
                <div class="d-flex gap-2">
                    <a href="{{ route('petroleum.pumps.edit', $pump) }}" class="btn btn-sm btn-light shadow-sm rounded-3"><i class="bi bi-pencil"></i> {{ __('Edit') }}</a>
                    <form action="{{ route('petroleum.pumps.destroy', $pump) }}" method="POST" onsubmit="return confirm('{{ __('Delete this pump?') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm rounded-3"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-fuel-pump fs-1 d-block mb-3"></i>
                {{ __('No pumps yet. Add your first pump.') }}
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
