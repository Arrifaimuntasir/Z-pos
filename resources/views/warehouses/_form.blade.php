@php $isEdit = isset($warehouse); @endphp
<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-8">
        <div class="gd-card">
            <div class="gd-card-head">
                <div class="d-flex align-items-center gap-3">
                    <div class="gd-kpi-icon {{ $isEdit ? 'gd-ic-amber' : 'gd-ic-blue' }}" style="width:44px;height:44px;font-size:19px;">
                        <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-building-add' }}"></i>
                    </div>
                    <div>
                        <h5>{{ $isEdit ? __('Warehouse details') : __('New warehouse') }}</h5>
                        <div class="text-muted" style="font-size:13px;">{{ __('Fields marked * are required') }}</div>
                    </div>
                </div>
            </div>

            <form action="{{ $isEdit ? route('warehouses.update', $warehouse) : route('warehouses.store') }}" method="POST" class="p-4">
                @csrf
                @if($isEdit) @method('PUT') @endif

                <div class="mb-4">
                    <label class="gd-label" for="gdName">{{ __('Warehouse Name') }} <span class="text-danger">*</span></label>
                    <input type="text" id="gdName" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="{{ __('e.g. Main Warehouse, Kariakoo Store') }}"
                        value="{{ old('name', $isEdit ? $warehouse->name : '') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="gd-label" for="gdLocation">{{ __('Location / Address') }}</label>
                    <div class="position-relative">
                        <i class="bi bi-geo-alt position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
                        <input type="text" id="gdLocation" name="location" class="form-control" style="padding-left:40px;"
                            placeholder="{{ __('e.g. Kariakoo, Dar es Salaam') }}"
                            value="{{ old('location', $isEdit ? $warehouse->location : '') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="gd-label" for="gdDesc">{{ __('Description') }}</label>
                    <textarea id="gdDesc" name="description" class="form-control" rows="3"
                        placeholder="{{ __('Short note about this warehouse...') }}">{{ old('description', $isEdit ? $warehouse->description : '') }}</textarea>
                </div>

                @if($isEdit)
                    <div class="d-flex align-items-center justify-content-between p-3 mb-4 rounded-3 gd-stat-box">
                        <div>
                            <div class="fw-semibold gd-stat-num" style="font-size:14px;">{{ __('Active') }}</div>
                            <div class="text-muted" style="font-size:12px;">{{ __('Inactive warehouses cannot receive new transfers.') }}</div>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="isActive" value="1"
                                style="width:2.8em;height:1.45em;cursor:pointer;" {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}>
                        </div>
                    </div>
                @endif

                <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2">
                    <a href="{{ route('warehouses.index') }}" class="gd-btn gd-btn-ghost justify-content-center">{{ __('Cancel') }}</a>
                    <button type="submit" class="gd-btn gd-btn-dark justify-content-center">
                        <i class="bi {{ $isEdit ? 'bi-check2' : 'bi-plus-lg' }}"></i>
                        {{ $isEdit ? __('Save Changes') : __('Add Warehouse') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
