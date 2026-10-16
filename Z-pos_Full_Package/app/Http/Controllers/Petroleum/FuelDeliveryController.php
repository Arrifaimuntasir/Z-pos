<?php

namespace App\Http\Controllers\Petroleum;

use App\Http\Controllers\Controller;
use App\Models\FuelDelivery;
use App\Models\FuelTank;
use App\Models\Supplier;
use App\Services\PetroleumDay;
use App\Services\PetroleumStock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

class FuelDeliveryController extends Controller
{
    private function branchId(): int
    {
        return PetroleumStock::stationBranchId($this->getActiveBranchId());
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()->hasRole('Administrator'), 403);
    }

    public function index()
    {
        $this->ensureAdmin();
        $deliveries = FuelDelivery::with(['tank.product', 'supplier', 'receiver'])
            ->where('branch_id', $this->branchId())
            ->latest('id')->limit(100)->get();

        return view('petroleum.deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('petroleum.deliveries.create', $this->formData());
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $branchId = $this->branchId();
        $shopId = auth()->user()->shop_id;

        $data = $request->validate([
            'fuel_tank_id' => ['required', Rule::exists('fuel_tanks', 'id')->where('branch_id', $branchId)->whereNull('deleted_at')],
            'quantity' => 'required|numeric|min:0.001',
            'unit_cost' => 'nullable|numeric|min:0',
            'supplier_id' => ['nullable', Rule::exists('suppliers', 'id')->where('shop_id', $shopId)->whereNull('deleted_at')],
            'delivery_date' => 'required|date',
            'delivery_time' => 'nullable|date_format:H:i',
            'invoice_number' => 'nullable|string|max:100',
            'delivery_note_number' => 'nullable|string|max:100',
            'driver' => 'nullable|string|max:150',
            'vehicle_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            PetroleumDay::recordDelivery($branchId, $data, auth()->id());
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('petroleum.deliveries.index')->with('success', 'Delivery recorded and tank stock updated.');
    }

    private function formData(): array
    {
        $branchId = $this->branchId();

        return [
            'tanks' => FuelTank::with('product')->where('branch_id', $branchId)->where('status', 'active')->orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ];
    }
}
