@extends('layouts.admin')

@section('content')
<div class="mb-4"><h4 class="fw-bold mb-0 text-dark">{{ __('Record Fuel Delivery') }}</h4></div>
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <form action="{{ route('petroleum.deliveries.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">{{ __('Tank') }} <span class="text-danger">*</span></label>
                    <select name="fuel_tank_id" class="form-select" required>
                        <option value="">{{ __('Select tank...') }}</option>
                        @foreach($tanks as $t)
                            <option value="{{ $t->id }}" {{ (int) old('fuel_tank_id') === $t->id ? 'selected' : '' }}>{{ $t->name }} — {{ $t->product->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">{{ __('Supplier') }}</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">{{ __('Select supplier...') }}</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ (int) old('supplier_id') === $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label fw-medium">{{ __('Delivery date') }} <span class="text-danger">*</span></label><input type="date" name="delivery_date" class="form-control" value="{{ old('delivery_date', now()->toDateString()) }}" required></div>
                <div class="col-md-3"><label class="form-label fw-medium">{{ __('Delivery time') }}</label><input type="time" name="delivery_time" class="form-control" value="{{ old('delivery_time') }}"></div>
                <div class="col-md-3"><label class="form-label fw-medium">{{ __('Quantity (Litres)') }} <span class="text-danger">*</span></label><input type="number" step="0.001" min="0.001" name="quantity" class="form-control" value="{{ old('quantity') }}" required></div>
                <div class="col-md-3"><label class="form-label fw-medium">{{ __('Unit cost (TSh/L)') }}</label><input type="number" step="0.01" min="0" name="unit_cost" class="form-control" value="{{ old('unit_cost') }}"></div>
                <div class="col-md-3"><label class="form-label fw-medium">{{ __('Invoice number') }}</label><input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number') }}"></div>
                <div class="col-md-3"><label class="form-label fw-medium">{{ __('Delivery note number') }}</label><input type="text" name="delivery_note_number" class="form-control" value="{{ old('delivery_note_number') }}"></div>
                <div class="col-md-3"><label class="form-label fw-medium">{{ __('Driver') }}</label><input type="text" name="driver" class="form-control" value="{{ old('driver') }}"></div>
                <div class="col-md-3"><label class="form-label fw-medium">{{ __('Vehicle / tanker number') }}</label><input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number') }}"></div>
                <div class="col-12"><label class="form-label fw-medium">{{ __('Notes') }}</label><input type="text" name="notes" class="form-control" value="{{ old('notes') }}" maxlength="1000"></div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('petroleum.deliveries.index') }}" class="btn btn-light px-4">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">{{ __('Save Delivery') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
