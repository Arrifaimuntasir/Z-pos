<?php

namespace App\Http\Controllers\Petroleum;

use App\Http\Controllers\Controller;
use App\Models\FuelPump;
use App\Services\PetroleumStock;
use Illuminate\Http\Request;

class FuelPumpController extends Controller
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
        $pumps = FuelPump::with('nozzles.tank.product')->where('branch_id', $this->branchId())->orderBy('number')->get();

        return view('petroleum.pumps.index', compact('pumps'));
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('petroleum.pumps.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $this->validated($request);

        FuelPump::create($data + ['branch_id' => $this->branchId()]);

        return redirect()->route('petroleum.pumps.index')->with('success', 'Pump added successfully.');
    }

    public function edit(FuelPump $pump)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($pump);

        return view('petroleum.pumps.edit', compact('pump'));
    }

    public function update(Request $request, FuelPump $pump)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($pump);
        $pump->update($this->validated($request, $pump));

        return redirect()->route('petroleum.pumps.index')->with('success', 'Pump updated successfully.');
    }

    public function destroy(FuelPump $pump)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($pump);

        if ($pump->nozzles()->exists()) {
            return back()->with('error', 'Delete this pump\'s nozzles first.');
        }

        $pump->delete();

        return redirect()->route('petroleum.pumps.index')->with('success', 'Pump deleted.');
    }

    private function validated(Request $request, ?FuelPump $pump = null): array
    {
        $branchId = $this->branchId();

        return $request->validate([
            'number' => ['required', 'integer', 'min:1', \Illuminate\Validation\Rule::unique('fuel_pumps', 'number')
                ->where('branch_id', $branchId)
                ->ignore($pump?->id)
                ->whereNull('deleted_at')],
            'name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,maintenance',
        ]);
    }

    private function ensureSameStation(FuelPump $pump): void
    {
        abort_unless((int) $pump->branch_id === $this->branchId(), 404);
    }
}
