@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0 text-dark">{{ __('Edit Tank') }}: {{ $tank->name }}</h4>
    <span class="text-muted small">{{ __('Stock changes only through deliveries and sales, not here.') }} ({{ number_format($tank->current_stock, 2) }} L)</span>
</div>
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <form action="{{ route('petroleum.tanks.update', $tank) }}" method="POST">
            @csrf @method('PUT')
            @include('petroleum.tanks._form')
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('petroleum.tanks.index') }}" class="btn btn-light px-4">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">{{ __('Update Tank') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
