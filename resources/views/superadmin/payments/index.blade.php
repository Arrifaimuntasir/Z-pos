@extends('layouts.admin')

@section('title', 'Manage Payments')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">{{ __('System Admin: Manage Payments') }}</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-top-start ps-4 py-3">{{ __('Date') }}</th>
                        <th class="border-0 py-3">{{ __('Shop') }}</th>
                        <th class="border-0 py-3">{{ __('Package') }}</th>
                        <th class="border-0 py-3">{{ __('Amount') }}</th>
                        <th class="border-0 py-3">{{ __('Receipt') }}</th>
                        <th class="border-0 py-3">{{ __('Status') }}</th>
                        <th class="border-0 rounded-top-end text-end pe-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($payments as $payment)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $payment->created_at ? $payment->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td class="fw-bold">{{ $payment->shop ? $payment->shop->name : 'Unknown Shop' }}</td>
                        <td>
                            @php
                                $pkg = $payment->package ?? ($payment->shop ? $payment->shop->package : 'starter') ?? 'starter';
                                $billing = $payment->billing_cycle ?? ($payment->shop ? $payment->shop->billing_cycle : 'monthly') ?? 'monthly';
                            @endphp
                            <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase fw-semibold">{{ $pkg }}</span>
                            <span class="badge ms-1 {{ $billing === 'yearly' ? 'bg-success' : 'bg-secondary' }} bg-opacity-15 {{ $billing === 'yearly' ? 'text-success' : 'text-secondary' }}">
                                <i class="bi bi-{{ $billing === 'yearly' ? 'calendar-check' : 'calendar' }} me-1"></i>{{ $billing === 'yearly' ? __('Yearly') : __('Monthly') }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">TZS {{ number_format($payment->amount ?? 0) }}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $payment->id }}">
                                <i class="bi bi-file-earmark-text me-1"></i> {{ __('View Receipt') }}
                            </button>
                        </td>
                        <td>
                            @if($payment->status === 'pending')
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>{{ __('Pending') }}</span>
                            @elseif($payment->status === 'approved')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>{{ __('Rejected') }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                @if($payment->status === 'pending')
                                    <form action="{{ route('superadmin.payments.approve', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Approve this payment?\n\nShop: {{ $payment->shop ? $payment->shop->name : 'Unknown Shop' }}\nPackage: {{ strtoupper($pkg) }} ({{ ucfirst($billing) }})\nAmount: TZS {{ number_format($payment->amount ?? 0) }}\n\nSubscription will be extended by {{ $billing === \'yearly\' ? \'1 Year\' : \'1 Month\' }}.');">
                                            <i class="bi bi-check-lg"></i> {{ __('Approve') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('superadmin.payments.reject', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning text-white"
                                            onclick="return confirm('Reject this payment from {{ $payment->shop ? $payment->shop->name : 'Unknown Shop' }}?');">
                                            <i class="bi bi-x-lg"></i> {{ __('Reject') }}
                                        </button>
                                    </form>
                                @endif
                                {{-- Delete Button (always visible) --}}
                                <form action="{{ route('superadmin.payments.destroy', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('DELETE this payment record permanently?\n\nShop: {{ $payment->shop ? $payment->shop->name : 'Unknown Shop' }}\nThis cannot be undone.');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Receipt Modal -->
                    <div class="modal fade" id="receiptModal{{ $payment->id }}" tabindex="-1" aria-labelledby="receiptModalLabel{{ $payment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>
                                        <h5 class="modal-title mb-1" id="receiptModalLabel{{ $payment->id }}">
                                            {{ __('Receipt from') }}: <strong class="text-primary">{{ $payment->shop ? $payment->shop->name : 'Unknown Shop' }}</strong>
                                        </h5>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase">{{ $pkg }}</span>
                                            <span class="badge {{ $billing === 'yearly' ? 'bg-success' : 'bg-secondary' }} bg-opacity-15 {{ $billing === 'yearly' ? 'text-success' : 'text-secondary' }}">
                                                {{ $billing === 'yearly' ? __('Yearly') : __('Monthly') }}
                                            </span>
                                            <span class="fw-bold text-dark small">TZS {{ number_format($payment->amount ?? 0) }}</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center bg-light">
                                    @php
                                        $extension = pathinfo($payment->receipt_path, PATHINFO_EXTENSION);
                                    @endphp
                                    @if(strtolower($extension) === 'pdf')
                                        <iframe src="{{ asset($payment->receipt_path) }}" width="100%" height="500px" style="border: none;"></iframe>
                                    @else
                                        <img src="{{ asset($payment->receipt_path) }}" alt="Payment Receipt" class="img-fluid rounded shadow-sm" style="max-height: 70vh; object-fit: contain;">
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ asset($payment->receipt_path) }}" target="_blank" class="btn btn-outline-primary"><i class="bi bi-box-arrow-up-right me-1"></i> {{ __('Open in New Tab') }}</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;"></i>
                            {{ __('No payments found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
