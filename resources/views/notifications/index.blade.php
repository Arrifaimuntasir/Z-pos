@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">{{ __('App Notifications') }}</h4>
            <div class="btn-group">
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}" class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">{{ __('All') }}</a>
                <a href="{{ route('notifications.index', ['filter' => 'today']) }}" class="btn btn-sm {{ $filter === 'today' ? 'btn-primary' : 'btn-outline-primary' }}">{{ __('Today') }}</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.destroy') }}" method="POST" id="notificationsForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                {{ __('Select All') }}
                            </label>
                        </div>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete selected notifications?') }}')">
                            <i class="bi bi-trash"></i> {{ __('Delete Selected') }}
                        </button>
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
                                        @if(isset($notification->data['message']))
                                            {{ $notification->data['message'] }}
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
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.notif-check');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush
