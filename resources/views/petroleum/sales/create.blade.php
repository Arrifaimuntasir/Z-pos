@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0 text-dark">{{ __('New Fuel Sale') }}</h4>
    <span class="text-muted small">{{ __('Station day') }}: {{ $openDay->business_date->format('d M Y') }}</span>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('petroleum.sales.store') }}" method="POST" id="fuelSaleForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">{{ __('Pump & nozzle') }} <span class="text-danger">*</span></label>
                        <select name="fuel_nozzle_id" id="nozzleSelect" class="form-select form-select-lg" required>
                            <option value="">{{ __('Select nozzle...') }}</option>
                            @foreach($nozzles as $n)
                                <option value="{{ $n->id }}" data-price="{{ $n->tank->product->selling_price ?? 0 }}" data-stock="{{ $n->tank->current_stock ?? 0 }}" {{ (int) old('fuel_nozzle_id') === $n->id ? 'selected' : '' }}>
                                    {{ __('Pump') }} {{ $n->pump->number }} · {{ __('Nozzle') }} {{ $n->number }} — {{ $n->tank->product->name ?? '' }} ({{ number_format($n->tank->current_stock ?? 0, 0) }} L)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">{{ __('Litres') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.001" min="0.001" name="litres" id="litresInput" class="form-control form-control-lg" value="{{ old('litres') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">{{ __('Price per litre') }}</label>
                            <input type="text" id="priceView" class="form-control form-control-lg" value="" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">{{ __('Discount (TSh)') }}</label>
                            <input type="number" step="0.01" min="0" name="discount_amount" id="discountInput" class="form-control form-control-lg" value="{{ old('discount_amount', 0) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">{{ __('Payment') }} <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select form-select-lg" required>
                                <option value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>{{ __('Mobile Money (M-Pesa)') }}</option>
                                <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>{{ __('Card') }}</option>
                                <option value="bank" {{ old('payment_method') === 'bank' ? 'selected' : '' }}>{{ __('Bank') }}</option>
                                <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">{{ __('Customer') }} <small class="text-muted">({{ __('optional') }})</small></label>
                            <select name="customer_id" class="form-select">
                                <option value="">{{ __('Walk-in customer') }}</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ (int) old('customer_id') === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="rounded-3 p-3 mt-4" style="background:#f8fafc;">
                        <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Gross') }}</span><span id="grossView" class="fw-bold">0.00</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Discount') }}</span><span id="discView" class="fw-bold text-danger">0.00</span></div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fs-5"><span class="fw-bold">{{ __('Total to collect') }}</span><span id="netView" class="fw-bold text-success">0.00 TSh</span></div>
                        <div id="stockWarn" class="small text-danger mt-2 d-none"></div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('petroleum.sales.index') }}" class="btn btn-light px-4">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-success btn-lg px-4 fw-bold flex-fill" id="submitBtn">{{ __('Record Sale') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const nozzle = document.getElementById('nozzleSelect');
    const litres = document.getElementById('litresInput');
    const discount = document.getElementById('discountInput');
    const priceView = document.getElementById('priceView');
    const grossView = document.getElementById('grossView');
    const discView = document.getElementById('discView');
    const netView = document.getElementById('netView');
    const warn = document.getElementById('stockWarn');
    const submit = document.getElementById('submitBtn');
    const fmt = n => (isFinite(n) ? n : 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    function recalc() {
        const opt = nozzle.options[nozzle.selectedIndex];
        const price = opt && opt.dataset.price ? parseFloat(opt.dataset.price) : 0;
        const stock = opt && opt.dataset.stock ? parseFloat(opt.dataset.stock) : 0;
        const l = parseFloat(litres.value) || 0;
        const gross = Math.round(l * price * 100) / 100;
        const disc = Math.min(Math.max(parseFloat(discount.value) || 0, 0), gross);
        priceView.value = price ? fmt(price) + ' TSh' : '';
        grossView.textContent = fmt(gross);
        discView.textContent = fmt(disc);
        netView.textContent = fmt(gross - disc) + ' TSh';
        const over = l > stock + 0.0005;
        warn.classList.toggle('d-none', !over);
        warn.textContent = over ? 'Only ' + fmt(stock) + ' L left in this tank.' : '';
        submit.disabled = !nozzle.value || l <= 0 || over;
    }
    [nozzle, litres, discount].forEach(el => el.addEventListener('input', recalc));
    nozzle.addEventListener('change', recalc);
    recalc();
})();
</script>
@endpush
@endsection
