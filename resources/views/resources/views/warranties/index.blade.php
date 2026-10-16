@extends('layouts.admin')

@section('title', __('Warranties'))

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3 pb-2">
    <div>
        <h2 class="fw-bold mb-1" style="color: #0f172a;">{{ __('Warranties') }}</h2>
        <p class="text-muted small mb-0" style="font-size: 14px;">{{ __('Manage customer warranties') }}</p>
    </div>
    <div>
        <a href="{{ route('warranties.create') }}" class="btn fw-bold shadow-sm rounded-pill px-4 py-2" style="background-color: #0f172a; color: white;">
            <i class="bi bi-plus-lg me-1"></i> {{ __('Generate Warranty') }}
        </a>
    </div>
</div>

<!-- Metrics Dashboard -->
<!-- Metrics Dashboard -->
<div class="row mb-5 g-4">
    <div class="col-md-4">
        <div class="card border shadow-sm rounded-4 h-100" style="border-color: #e2e8f0 !important;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #f1f5f9; color: #475569;">
                    <i class="bi bi-shield-check fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1" style="font-size: 14px; font-weight: 500;">{{ __('Total Generated') }}</h6>
                    <h2 class="fw-bold mb-0" style="color: #0f172a; font-size: 28px;">{{ $totalWarranties }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border shadow-sm rounded-4 h-100" style="border-color: #e2e8f0 !important;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #dcfce7; color: #16a34a;">
                    <i class="bi bi-shield-fill-check fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1" style="font-size: 14px; font-weight: 500;">{{ __('Active Warranties') }}</h6>
                    <h2 class="fw-bold mb-0" style="color: #0f172a; font-size: 28px;">{{ $activeWarranties }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border shadow-sm rounded-4 h-100" style="border-color: #e2e8f0 !important;">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background-color: #fee2e2; color: #dc2626;">
                    <i class="bi bi-shield-fill-x fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1" style="font-size: 14px; font-weight: 500;">{{ __('Expired Warranties') }}</h6>
                    <h2 class="fw-bold mb-0" style="color: #0f172a; font-size: 28px;">{{ $expiredWarranties }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4" style="background: #fff;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h4 class="mb-0 fw-bold" style="color: #1e293b;">{{ __('Recent Warranties') }}</h4>
        <form action="{{ route('warranties.index') }}" method="GET" class="d-flex align-items-center w-100 w-md-auto">
            <div class="input-group border rounded-pill w-100" style="padding: 2px;">
                <input type="text" name="search" class="form-control border-0 shadow-none rounded-pill ps-4" style="font-size: 13px;" placeholder="{{ __('Search customer or warrant') }}" value="{{ request('search') }}">
                <button type="submit" class="btn rounded-pill px-3" style="background: #0f172a; color: white;"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th class="px-4 py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">#</th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">{{ __('Warranty No') }}</th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">{{ __('Customer') }}</th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">{{ __('Product') }}</th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">{{ __('Duration') }}</th>
                        <th class="py-3" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">{{ __('Valid Until') }}</th>
                        <th class="px-4 py-3 text-end" style="color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;">
                    @forelse($warranties as $warranty)
                    <tr>
                        <td class="px-4 text-muted" style="font-size: 13px;">{{ $loop->iteration + $warranties->firstItem() - 1 }}</td>
                        <td class="fw-bold" style="color: #0f172a;">{{ $warranty->warranty_number }}</td>
                        <td style="color: #1e293b;">{{ $warranty->customer_name ?: '-' }}</td>
                        <td>
                            <div class="fw-bold" style="color: #0f172a;">{{ $warranty->product_name }}</div>
                            <div style="font-size: 12px; color: #94a3b8;">SN: {{ $warranty->serial_number ?: 'N/A' }}</div>
                        </td>
                        <td style="color: #1e293b;">{{ $warranty->duration }}</td>
                        <td>
                            @php
                                $isValid = \Carbon\Carbon::now()->startOfDay()->lte($warranty->end_date);
                            @endphp
                            @if($isValid)
                                <span class="badge" style="background: #dcfce7; color: #16a34a; font-weight: 500; padding: 6px 12px; border-radius: 6px;">{{ $warranty->end_date->format('d M, Y') }}</span>
                            @else
                                <span class="badge" style="background: #fee2e2; color: #dc2626; font-weight: 500; padding: 6px 12px; border-radius: 6px;">{{ __('Expired') }} ({{ $warranty->end_date->format('d M, Y') }})</span>
                            @endif
                        </td>
                        <td class="px-4 text-end">
                            <a href="{{ route('warranties.edit', $warranty->id) }}" class="btn btn-sm rounded-pill px-3 me-2" style="border: 1px solid #3b82f6; color: #3b82f6; background: transparent; font-weight: 500;">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                            <a href="{{ route('warranties.show', $warranty->id) }}" target="_blank" class="btn btn-sm rounded-pill px-3 me-2" style="border: 1px solid #0f172a; color: #0f172a; background: transparent; font-weight: 500;">
                                <i class="bi bi-printer-fill me-1"></i> Print
                            </a>
                            <form action="{{ route('warranties.destroy', $warranty->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this warranty?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm rounded-circle" style="border: 1px solid #ef4444; color: #ef4444; background: transparent; width: 32px; height: 32px; padding: 0;">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-check display-4 mb-3 d-block text-black-50"></i>
                            {{ __('No warranties generated yet.') }}<br>
                            <a href="{{ route('warranties.create') }}" class="btn btn-primary mt-3 rounded-pill px-4">{{ __('Generate your first warranty') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none p-3">
            @forelse($warranties as $warranty)
            <div class="card mb-3 shadow-sm border border-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-3 border text-secondary" style="width: 45px; height: 45px;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $warranty->warranty_number }}</h6>
                                <div class="small text-muted">{{ $warranty->customer_name ?: 'No Customer Name' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 small text-muted">
                        <div class="mb-1"><i class="bi bi-box me-2"></i> {{ $warranty->product_name }}</div>
                        <div class="mb-1"><i class="bi bi-upc-scan me-2"></i> SN: {{ $warranty->serial_number ?: 'N/A' }}</div>
                        <div class="mb-1"><i class="bi bi-calendar me-2"></i> Duration: {{ $warranty->duration }}</div>
                        <div><i class="bi bi-calendar-x me-2"></i> Valid: 
                            @php
                                $isValid = \Carbon\Carbon::now()->startOfDay()->lte($warranty->end_date);
                            @endphp
                            @if($isValid)
                                <span class="text-success fw-bold">{{ $warranty->end_date->format('d M, Y') }}</span>
                            @else
                                <span class="text-danger fw-bold">Expired ({{ $warranty->end_date->format('d M, Y') }})</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('warranties.edit', $warranty->id) }}" class="btn btn-outline-primary flex-fill" style="font-weight: 500;">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        <a href="{{ route('warranties.show', $warranty->id) }}" target="_blank" class="btn btn-outline-dark flex-fill" style="font-weight: 500;">
                            <i class="bi bi-printer-fill me-1"></i> Print
                        </a>
                        <form action="{{ route('warranties.destroy', $warranty->id) }}" method="POST" class="d-inline flex-fill" onsubmit="return confirm('{{ __('Are you sure you want to delete this warranty?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" style="font-weight: 500;">
                                <i class="bi bi-trash-fill"></i> Del
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-shield-check display-4 mb-3 d-block text-black-50"></i>
                {{ __('No warranties generated yet.') }}<br>
                <a href="{{ route('warranties.create') }}" class="btn btn-primary mt-3 rounded-pill px-4">{{ __('Generate your first warranty') }}</a>
            </div>
            @endforelse
        </div>
    </div>
    @if($warranties->hasPages())
    <div class="card-footer bg-white border-top py-3">
        {{ $warranties->links() }}
    </div>
    @endif
</div>
@endsection
