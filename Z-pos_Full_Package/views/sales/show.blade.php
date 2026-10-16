@extends('layouts.admin')

@section('title', 'Sale Receipt')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3 d-print-none">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Sale Receipt</h4>
        <span class="text-muted small">View transaction details</span>
    </div>
    <div>
        <a href="{{ route('sales.index') }}" class="btn btn-light border bg-white shadow-sm rounded-pill px-3" style="font-weight: 500; font-size: 14px;"><i class="bi bi-arrow-left me-1"></i> Back</a>
        <a href="{{ route('sales.pdf', $sale->id) }}?v={{ time() }}" class="btn btn-danger px-3 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
        <button onclick="window.print()" class="btn btn-primary px-3 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-printer me-1"></i> Print
        </button>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-5" id="receiptArea">
                
                <!-- Receipt Header -->
                <div class="text-center border-bottom pb-4 mb-4">
                    <h2 class="fw-bold text-dark mb-1">{{ $sale->shop->name ?? 'Z-POS SYSTEM' }}</h2>
                    <p class="text-muted mb-0">{{ $sale->shop->address ?? 'Dar es Salaam, Tanzania' }}</p>
                    <p class="text-muted mb-0">Tel: {{ $sale->shop->phone ?? '+255 123 456 789' }}</p>
                    @if($sale->shop && $sale->shop->tin_number)
                        <p class="text-muted mb-0">TIN: {{ $sale->shop->tin_number }}</p>
                    @endif
                </div>

                <!-- Receipt Info -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="fw-bold text-muted small text-uppercase mb-1">Billed To:</h6>
                        @if($sale->customer)
                            <h5 class="fw-bold mb-0">{{ $sale->customer->name }}</h5>
                            @if($sale->customer->phone) <p class="text-muted mb-0">{{ $sale->customer->phone }}</p> @endif
                            @if($sale->customer->address) <p class="text-muted mb-0">{{ $sale->customer->address }}</p> @endif
                        @else
                            <h5 class="fw-bold mb-0 text-muted fst-italic">Walk-in Customer</h5>
                        @endif
                    </div>
                    <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                        @if($sale->payment_status == 'proforma')
                            <h6 class="fw-bold text-info small text-uppercase mb-1">Pro-Forma Invoice:</h6>
                        @else
                            <h6 class="fw-bold text-muted small text-uppercase mb-1">Receipt Details:</h6>
                        @endif
                        <h5 class="fw-bold text-primary mb-1">{{ $sale->reference_no }}</h5>
                        <p class="text-muted mb-0">Date: {{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</p>
                        <p class="text-muted mb-0">Method: {{ $sale->payment_method }}</p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Item Description</th>
                                <th class="text-center" style="width: 100px;">Qty</th>
                                <th class="text-end" style="width: 150px;">Price</th>
                                <th class="text-end" style="width: 150px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-medium">{{ $item->product ? $item->product->name : 'Unknown Product' }}</span>
                                        @if($item->imei_serial_number)
                                            <br><small class="text-muted">IMEI/SN: {{ $item->imei_serial_number }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item->subtotal) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="row justify-content-end">
                    <div class="col-sm-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted fw-medium">Subtotal:</span>
                            <span class="fw-bold">{{ number_format($sale->total_amount) }} TSh</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-muted fw-medium">Discount:</span>
                            <span class="fw-bold">0 TSh</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fs-5 fw-bold text-dark">Grand Total:</span>
                            <span class="fs-5 fw-bold text-primary">{{ number_format($sale->total_amount) }} TSh</span>
                        </div>
                        
                        @if($sale->payment_status != 'proforma')
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted fw-medium">Amount Paid:</span>
                                <span class="fw-bold text-success">{{ number_format($sale->paid_amount) }} TSh</span>
                            </div>
                            @if($sale->total_amount - $sale->paid_amount > 0)
                                <div class="d-flex justify-content-between text-danger">
                                    <span class="fw-medium">Balance Due:</span>
                                    <span class="fw-bold">{{ number_format($sale->total_amount - $sale->paid_amount) }} TSh</span>
                                </div>
                            @endif
                        @else
                            <div class="d-flex justify-content-between text-info">
                                <span class="fw-medium">Status:</span>
                                <span class="fw-bold">PRO-FORMA</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($sale->notes)
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-bold text-muted small text-uppercase mb-2">Notes:</h6>
                    <p class="text-muted mb-0">{{ $sale->notes }}</p>
                </div>
                @endif

                <!-- Footer Advertisement -->
                <div class="text-center mt-5 pt-4 border-top">
                    <p class="text-muted small fst-italic mb-2">{{ $sale->shop->receipt_message ?? 'Thank you for your business!' }}</p>
                    <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                        <strong>Powered by Z-POS SYSTEM</strong> <br> Smart Point of Sale & Inventory Management
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white; }
    .wrapper { display: block; }
    .sidebar { display: none !important; }
    #content { margin: 0; padding: 0; width: 100%; min-height: auto; }
    .top-navbar, .d-print-none { display: none !important; }
    .card { box-shadow: none !important; border: none !important; }
    .card-body { padding: 0 !important; }
}
</style>
@endsection
