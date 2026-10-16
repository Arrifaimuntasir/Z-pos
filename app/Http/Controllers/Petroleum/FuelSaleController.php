<?php

namespace App\Http\Controllers\Petroleum;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FuelNozzle;
use App\Models\FuelSale;
use App\Services\PetroleumDay;
use App\Services\PetroleumStock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

class FuelSaleController extends Controller
{
    private function branchId(): int
    {
        return PetroleumStock::stationBranchId($this->getActiveBranchId());
    }

    public function index(Request $request)
    {
        $branchId = $this->branchId();
        $date = $request->query('date', now()->toDateString());

        $sales = FuelSale::with(['nozzle', 'pump', 'product', 'attendant', 'customer'])
            ->where('branch_id', $branchId)
            ->whereDate('business_date', $date)
            ->latest('id')->get();

        $totals = [
            'litres' => (float) $sales->sum('litres'),
            'gross' => (float) $sales->sum('gross_amount'),
            'discount' => (float) $sales->sum('discount_amount'),
            'net' => (float) $sales->sum('net_amount'),
        ];
        $byMethod = $sales->groupBy('payment_method')->map(fn ($g) => (float) $g->sum('net_amount'));
        $openDay = PetroleumDay::openDay($branchId);

        return view('petroleum.sales.index', compact('sales', 'totals', 'byMethod', 'date', 'openDay'));
    }

    public function create()
    {
        $branchId = $this->branchId();
        $openDay = PetroleumDay::openDay($branchId);
        if (!$openDay) {
            return redirect()->route('petroleum.day.index')->with('error', 'Open the station day before selling fuel.');
        }

        $nozzles = FuelNozzle::with(['pump', 'tank.product'])
            ->where('branch_id', $branchId)->where('status', 'active')
            ->orderBy('fuel_pump_id')->orderBy('number')->get();
        $customers = Customer::orderBy('name')->get();

        return view('petroleum.sales.create', compact('nozzles', 'customers', 'openDay'));
    }

    public function store(Request $request)
    {
        $branchId = $this->branchId();
        $shopId = auth()->user()->shop_id;

        $data = $request->validate([
            'fuel_nozzle_id' => ['required', Rule::exists('fuel_nozzles', 'id')->where('branch_id', $branchId)->where('status', 'active')->whereNull('deleted_at')],
            'litres' => 'required|numeric|min:0.001',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => ['required', Rule::in(array_keys(PetroleumDay::PAYMENT_METHODS))],
            'customer_id' => ['nullable', Rule::exists('customers', 'id')->where('shop_id', $shopId)->whereNull('deleted_at')],
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $sale = PetroleumDay::recordSale($branchId, $data, auth()->id());
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('petroleum.sales.show', $sale)->with('success', 'Fuel sale recorded.');
    }

    public function show(FuelSale $sale)
    {
        abort_unless((int) $sale->branch_id === $this->branchId(), 404);
        $sale->load(['nozzle', 'pump', 'tank', 'product', 'attendant', 'customer']);

        return view('petroleum.sales.receipt', compact('sale'));
    }
}
