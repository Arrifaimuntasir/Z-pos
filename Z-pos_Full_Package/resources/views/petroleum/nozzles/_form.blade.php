<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-medium">{{ __('Pump') }} <span class="text-danger">*</span></label>
        <select name="fuel_pump_id" class="form-select @error('fuel_pump_id') is-invalid @enderror" required>
            <option value="">{{ __('Select pump...') }}</option>
            @foreach($pumps as $p)
                <option value="{{ $p->id }}" {{ (int) old('fuel_pump_id', $nozzle->fuel_pump_id ?? 0) === $p->id ? 'selected' : '' }}>{{ __('Pump') }} {{ $p->number }}</option>
            @endforeach
        </select>
        @error('fuel_pump_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">{{ __('Tank (fuel source)') }} <span class="text-danger">*</span></label>
        <select name="fuel_tank_id" class="form-select @error('fuel_tank_id') is-invalid @enderror" required>
            <option value="">{{ __('Select tank...') }}</option>
            @foreach($tanks as $t)
                <option value="{{ $t->id }}" {{ (int) old('fuel_tank_id', $nozzle->fuel_tank_id ?? 0) === $t->id ? 'selected' : '' }}>{{ $t->name }} — {{ $t->product->name ?? '' }}</option>
            @endforeach
        </select>
        @error('fuel_tank_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Nozzle Number') }} <span class="text-danger">*</span></label>
        <input type="number" min="1" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number', $nozzle->number ?? '') }}" required>
        @error('number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Current Meter Reading') }}</label>
        <input type="number" step="0.001" min="0" name="meter_reading" class="form-control @error('meter_reading') is-invalid @enderror" value="{{ old('meter_reading', $nozzle->meter_reading ?? 0) }}">
        <small class="text-muted">{{ __('The pump counter as it reads now. It can only go up.') }}</small>
        @error('meter_reading')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">{{ __('Status') }}</label>
        <select name="status" class="form-select">
            @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'maintenance' => 'Maintenance'] as $v => $label)
                <option value="{{ $v }}" {{ old('status', $nozzle->status ?? 'active') === $v ? 'selected' : '' }}>{{ __($label) }}</option>
            @endforeach
        </select>
    </div>
</div>
