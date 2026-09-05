@extends('layouts.admin')

@section('title', 'Manage Shops')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">{{ __('System Admin: Manage Shops') }}</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-top-start ps-4 py-3">{{ __('Shop Name') }}</th>
                        <th class="border-0 py-3">{{ __('Owner Email') }}</th>
                        <th class="border-0 py-3">{{ __('Registered At') }}</th>
                        <th class="border-0 py-3">{{ __('Valid Until') }}</th>
                        <th class="border-0 py-3">{{ __('Status') }}</th>
                        <th class="border-0 rounded-top-end text-end pe-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($shops as $shop)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($shop->logo_path)
                                    <img src="{{ asset($shop->logo_path) }}" class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-3 border text-dark fw-bold" style="width: 40px; height: 40px; font-size: 18px;">
                                        {{ strtoupper(substr(trim($shop->name), 0, 1)) }}
                                    </div>
                                @endif
                                <span class="fw-bold text-dark">{{ $shop->name }}</span>
                            </div>
                        </td>
                        <td>
                            @foreach($shop->users as $user)
                                <div class="small">{{ $user->email }}</div>
                            @endforeach
                        </td>
                        <td>{{ $shop->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($shop->valid_until)
                                <span class="badge {{ \Carbon\Carbon::parse($shop->valid_until) < now() ? 'bg-danger' : 'bg-success' }}">
                                    {{ \Carbon\Carbon::parse($shop->valid_until)->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($shop->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">{{ __('Suspended') }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('superadmin.shops.edit', $shop) }}" class="btn btn-sm btn-outline-primary me-1" title="{{ __('Edit') }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('superadmin.shops.toggle-status', $shop) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $shop->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} me-1" onclick="return confirm('Are you sure you want to change the status of this shop?');" title="{{ $shop->is_active ? 'Suspend' : 'Activate' }}">
                                    <i class="bi {{ $shop->is_active ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('superadmin.shops.destroy', $shop) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to PERMANENTLY delete this shop and all its data?');" title="{{ __('Delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none p-3">
            @forelse($shops as $shop)
            <div class="card mb-3 shadow-sm border border-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            @if($shop->logo_path)
                                <img src="{{ asset($shop->logo_path) }}" class="rounded-circle me-3 border" width="50" height="50" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-3 border text-dark fw-bold" style="width: 50px; height: 50px; font-size: 24px;">
                                    {{ strtoupper(substr(trim($shop->name), 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $shop->name }}</h6>
                                <div>
                                    @if($shop->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 small">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 small">{{ __('Suspended') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 small text-muted">
                        <div class="mb-1"><i class="bi bi-envelope me-2"></i> 
                            @foreach($shop->users as $user)
                                {{ $user->email }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                        <div class="mb-1"><i class="bi bi-calendar-check me-2"></i> Reg: {{ $shop->created_at->format('M d, Y') }}</div>
                        <div><i class="bi bi-calendar-x me-2"></i> Valid: 
                            @if($shop->valid_until)
                                <span class="{{ \Carbon\Carbon::parse($shop->valid_until) < now() ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                    {{ \Carbon\Carbon::parse($shop->valid_until)->format('M d, Y') }}
                                </span>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('superadmin.shops.edit', $shop) }}" class="btn btn-outline-primary flex-fill">
                            <i class="bi bi-pencil"></i> {{ __('Edit') }}
                        </a>
                        <form action="{{ route('superadmin.shops.toggle-status', $shop) }}" method="POST" class="d-inline flex-fill">
                            @csrf
                            <button type="submit" class="btn {{ $shop->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} w-100" onclick="return confirm('Are you sure you want to change the status of this shop?');">
                                <i class="bi {{ $shop->is_active ? 'bi-pause-circle' : 'bi-play-circle' }}"></i> {{ $shop->is_active ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('superadmin.shops.destroy', $shop) }}" method="POST" class="d-inline flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to PERMANENTLY delete this shop and all its data?');">
                                <i class="bi bi-trash"></i> {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-shop fs-1 d-block mb-3"></i>
                {{ __('No shops registered yet.') }}
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
