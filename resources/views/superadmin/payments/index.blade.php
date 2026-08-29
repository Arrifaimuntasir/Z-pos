@extends('layouts.admin')

@section('title', 'Manage Payments')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <h4 class="fw-bold mb-0">System Admin: Pending Payments</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-top-start ps-4 py-3">Date</th>
                        <th class="border-0 py-3">Shop</th>
                        <th class="border-0 py-3">Receipt</th>
                        <th class="border-0 py-3">Status</th>
                        <th class="border-0 rounded-top-end text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($payments as $payment)
                    <tr>
                        <td class="ps-4 text-muted">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        <td class="fw-bold">{{ $payment->shop->name }}</td>
                        <td>
                            <a href="{{ asset($payment->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-text me-1"></i> View Receipt
                            </a>
                        </td>
                        <td>
                            @if($payment->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($payment->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($payment->status === 'pending')
                                <form action="{{ route('superadmin.payments.approve', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success me-1" onclick="return confirm('Approve this payment and extend subscription by 1 month?');">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('superadmin.payments.reject', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this payment?');">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">No actions</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
