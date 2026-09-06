@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-1 fw-bold"><i class="bi bi-bell-fill me-2"></i>{{ __('Sales Notifications') }}</h4>
            <p class="text-muted small mb-3">{{ __('Track and manage your recent sales activity in real-time') }}</p>
            <div class="btn-group w-100 mb-3" role="group">
                <a href="{{ route('notifications.index', ['filter' => 'today']) }}" class="btn" style="{{ $filter === 'today' ? 'background-color: #64748b; color: white; border: 1px solid #64748b;' : 'background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">{{ __('Today') }}</a>
                <a href="{{ route('notifications.index', ['filter' => 'month']) }}" class="btn" style="{{ $filter === 'month' ? 'background-color: #64748b; color: white; border: 1px solid #64748b;' : 'background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">{{ __('This Month') }}</a>
                <a href="{{ route('notifications.index', ['filter' => 'year']) }}" class="btn" style="{{ $filter === 'year' ? 'background-color: #64748b; color: white; border: 1px solid #64748b;' : 'background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">{{ __('This Year') }}</a>
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}" class="btn" style="{{ $filter === 'all' ? 'background-color: #64748b; color: white; border: 1px solid #64748b;' : 'background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">{{ __('All') }}</a>
            </div>
        </div>
    </div>



    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.destroy') }}" method="POST" id="notificationsForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">{{ __('Showing') }} {{ $notifications->count() }} {{ __('of') }} {{ $notifications->total() }}</span>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm text-white px-3 shadow-sm rounded-pill fw-bold" style="background-color: #64748b;" id="toggleSelectBtn">
                                <i class="bi bi-check2-square me-1"></i> {{ __('Select') }}
                            </button>
                            <button type="submit" class="btn btn-sm btn-danger px-3 shadow-sm rounded-pill d-none" id="deleteSelectedBtn" onclick="return confirm('{{ __('Are you sure you want to delete selected notifications?') }}')">
                                <i class="bi bi-trash"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action d-flex align-items-start p-3 {{ is_null($notification->read_at) ? 'bg-light fw-bold' : '' }}">
                                <div class="form-check mt-1 me-3">
                                    <input class="form-check-input notif-check" type="checkbox" name="ids[]" value="{{ $notification->id }}">
                                </div>
                                <div class="flex-shrink-0 me-3">
                                    @if(str_contains($notification->type, 'NewSale'))
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-cart-check"></i>
                                        </div>
                                    @elseif(str_contains($notification->type, 'PaymentReminder'))
                                        <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-bell"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        @php
                                            $msg = $notification->data['message'] ?? '';
                                            if (app()->getLocale() == 'en') {
                                                $msg = str_replace(
                                                    ['Mauzo mapya yamefanyika kwa thamani ya Tsh', 'Return mpya!', 'Nzima (zimerudishwa stock):', 'Mbovu (hazijarudishwa stock):'], 
                                                    ['New sale recorded for TSh', 'New Return!', 'Good (Restocked):', 'Defective (Not restocked):'], 
                                                    $msg
                                                );
                                            } else {
                                                $msg = str_replace(
                                                    ['New sale recorded for TSh', 'New Return!', 'Good (Restocked):', 'Defective (Not restocked):'], 
                                                    ['Mauzo mapya yamefanyika kwa thamani ya Tsh', 'Return mpya!', 'Nzima (zimerudishwa stock):', 'Mbovu (hazijarudishwa stock):'], 
                                                    $msg
                                                );
                                            }
                                        @endphp
                                        @if($msg)
                                            {{ $msg }}
                                        @else
                                            {{ __('New Notification') }}
                                        @endif
                                    </h6>
                                    <p class="mb-1 text-muted small">
                                        @if(isset($notification->data['amount']))
                                            <strong>Amount:</strong> TSh {{ number_format($notification->data['amount']) }}<br>
                                        @endif
                                        @if(isset($notification->data['reference_no']))
                                            <strong>Ref:</strong> {{ $notification->data['reference_no'] }}
                                        @endif
                                    </p>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            @else
                <div class="text-center p-5">
                    <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">{{ __('No notifications found') }}</h5>
                    <p class="text-muted small">{{ __('You currently have no new updates.') }}</p>
                </div>
            @endif
        </div>
        @if($notifications->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleSelectBtn = document.getElementById('toggleSelectBtn');
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        const checkboxes = document.querySelectorAll('.notif-check');
        const checkboxContainers = document.querySelectorAll('.form-check.mt-1.me-3');
        let selectMode = false;

        // Hide checkboxes initially
        checkboxContainers.forEach(c => c.classList.add('d-none'));

        toggleSelectBtn.addEventListener('click', function () {
            selectMode = !selectMode;
            if (selectMode) {
                toggleSelectBtn.innerHTML = '<i class="bi bi-x-circle me-1"></i> {{ __("Cancel") }}';
                toggleSelectBtn.style.backgroundColor = '#94a3b8';
                checkboxContainers.forEach(c => c.classList.remove('d-none'));
            } else {
                toggleSelectBtn.innerHTML = '<i class="bi bi-check2-square me-1"></i> {{ __("Select") }}';
                toggleSelectBtn.style.backgroundColor = '#64748b';
                checkboxContainers.forEach(c => c.classList.add('d-none'));
                checkboxes.forEach(cb => cb.checked = false);
                deleteBtn.classList.add('d-none');
            }
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const checkedCount = Array.from(checkboxes).filter(c => c.checked).length;
                if (checkedCount > 0) {
                    deleteBtn.classList.remove('d-none');
                } else {
                    deleteBtn.classList.add('d-none');
                }
            });
        });
    });
</script>
@endpush
