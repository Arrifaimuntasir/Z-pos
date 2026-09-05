@extends('layouts.admin')

@section('title', 'Manage Payments')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">{{ __('System Admin: Pending Payments') }}</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-top-start ps-4 py-3">{{ __('Date') }}</th>
                        <th class="border-0 py-3">{{ __('Shop') }}</th>
                        <th class="border-0 py-3">{{ __('Receipt') }}</th>
                        <th class="border-0 py-3">{{ __('Status') }}</th>
                        <th class="border-0 rounded-top-end text-end pe-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($payments as $payment)
                    <tr>
                        <td class="ps-4 text-muted">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        <td class="fw-bold">{{ $payment->shop->name }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $payment->id }}">
                                <i class="bi bi-file-earmark-text me-1"></i> {{ __('View Receipt') }}
                            </button>
                        </td>
                        <td>
                            @if($payment->status === 'pending')
                                <span class="badge bg-warning text-dark">{{ __('Pending') }}</span>
                            @elseif($payment->status === 'approved')
                                <span class="badge bg-success">{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Rejected') }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($payment->status === 'pending')
                                <form action="{{ route('superadmin.payments.approve', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success me-1" onclick="return confirm('Approve this payment and extend subscription by 1 month?');">
                                        <i class="bi bi-check-lg"></i> {{ __('Approve') }}
                                    </button>
                                </form>
                                <form action="{{ route('superadmin.payments.reject', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this payment?');">
                                        <i class="bi bi-x-lg"></i> {{ __('Reject') }}
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">{{ __('No actions') }}</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Receipt Modal -->
                    <div class="modal fade" id="receiptModal{{ $payment->id }}" tabindex="-1" aria-labelledby="receiptModalLabel{{ $payment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="receiptModalLabel{{ $payment->id }}">Receipt uploaded by: <strong class="text-primary">{{ $payment->shop->name }}</strong></h5>
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
                        <td colspan="5" class="text-center py-4 text-muted">{{ __('No payments found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
