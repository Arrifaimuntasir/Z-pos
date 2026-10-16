<?php

namespace App\Http\Controllers\Petroleum;

use App\Http\Controllers\Controller;
use App\Models\FuelTank;
use App\Models\Product;
use App\Services\PetroleumStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelTankController extends Controller
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
        $branchId = $this->branchId();
        $tanks = FuelTank::with('product')->where('branch_id', $branchId)->orderBy('name')->get();

        return view('petroleum.tanks.index', compact('tanks'));
    }

    public function create()
    {
        $this->ensureAdmin();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('petroleum.tanks.create', compact('products'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $this->validated($request);
        $branchId = $this->branchId();

        DB::transaction(function () use ($data, $branchId) {
            $tank = FuelTank::create([
                'branch_id' => $branchId,
                'product_id' => $data['product_id'],
                'name' => $data['name'],
                'capacity' => $data['capacity'],
                'current_stock' => 0,
                'min_level' => $data['min_level'] ?? 0,
                'max_level' => $data['max_level'] ?? $data['capacity'],
                'status' => $data['status'],
            ]);

            if ((float) $data['opening_stock'] > 0) {
                PetroleumStock::record($tank, 'opening', (float) $data['opening_stock'], 0, 'OPENING-' . $tank->id, 'Initial stock when tank was set up');
            }
        });

        return redirect()->route('petroleum.tanks.index')->with('success', 'Tank added successfully.');
    }

    public function edit(FuelTank $tank)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($tank);
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('petroleum.tanks.edit', compact('tank', 'products'));
    }

    public function update(Request $request, FuelTank $tank)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($tank);
        $data = $this->validated($request, $tank);

        if ((int) $data['product_id'] !== (int) $tank->product_id && (float) $tank->current_stock > 0) {
            return back()->withInput()->with('error', 'Fuel type cannot be changed while the tank has stock.');
        }

        $tank->update([
            'product_id' => $data['product_id'],
            'name' => $data['name'],
            'capacity' => $data['capacity'],
            'min_level' => $data['min_level'] ?? 0,
            'max_level' => $data['max_level'] ?? $data['capacity'],
            'status' => $data['status'],
        ]);

        return redirect()->route('petroleum.tanks.index')->with('success', 'Tank updated successfully.');
    }

    public function destroy(FuelTank $tank)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($tank);

        if ((float) $tank->current_stock > 0) {
            return back()->with('error', 'This tank still has fuel in it. Empty it before deleting.');
        }
        if ($tank->nozzles()->exists()) {
            return back()->with('error', 'Remove the nozzles connected to this tank first.');
        }

        $tank->delete();

        return redirect()->route('petroleum.tanks.index')->with('success', 'Tank deleted.');
    }

    private function validated(Request $request, ?FuelTank $tank = null): array
    {
        $branchId = $this->branchId();
        $shopId = auth()->user()->shop_id;

        $rules = [
            'product_id' => ['required', \Illuminate\Validation\Rule::exists('products', 'id')->where('shop_id', $shopId)->whereNull('deleted_at')],
            'name' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('fuel_tanks', 'name')
                ->where('branch_id', $branchId)
                ->ignore($tank?->id)
                ->whereNull('deleted_at')],
            'capacity' => 'required|numeric|min:1',
            'min_level' => 'nullable|numeric|min:0',
            'max_level' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,maintenance',
        ];
        if (!$tank) {
            $rules['opening_stock'] = 'nullable|numeric|min:0';
        }

        $data = $request->validate($rules);
        $data['opening_stock'] = $data['opening_stock'] ?? 0;

        if (!empty($data['max_level']) && $data['max_level'] > $data['capacity']) {
            throw \Illuminate\Validation\ValidationException::withMessages(['max_level' => 'Maximum level cannot be more than the tank capacity.']);
        }
        if ((float) $data['opening_stock'] > (float) $data['capacity']) {
            throw \Illuminate\Validation\ValidationException::withMessages(['opening_stock' => 'Opening stock cannot be more than the tank capacity.']);
        }

        return $data;
    }

    private function ensureSameStation(FuelTank $tank): void
    {
        abort_unless((int) $tank->branch_id === $this->branchId(), 404);
    }
}
