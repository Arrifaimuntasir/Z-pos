<?php

namespace App\Http\Controllers\Petroleum;

use App\Http\Controllers\Controller;
use App\Models\FuelNozzle;
use App\Models\FuelPump;
use App\Models\FuelTank;
use App\Services\PetroleumStock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FuelNozzleController extends Controller
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
        $nozzles = FuelNozzle::with(['pump', 'tank.product'])->where('branch_id', $this->branchId())
            ->orderBy('fuel_pump_id')->orderBy('number')->get();

        return view('petroleum.nozzles.index', compact('nozzles'));
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('petroleum.nozzles.create', $this->formData());
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $this->validated($request);

        FuelNozzle::create($data + ['branch_id' => $this->branchId(), 'meter_reading' => $data['meter_reading'] ?? 0]);

        return redirect()->route('petroleum.nozzles.index')->with('success', 'Nozzle added successfully.');
    }

    public function edit(FuelNozzle $nozzle)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($nozzle);

        return view('petroleum.nozzles.edit', $this->formData() + ['nozzle' => $nozzle]);
    }

    public function update(Request $request, FuelNozzle $nozzle)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($nozzle);
        $nozzle->update($this->validated($request, $nozzle));

        return redirect()->route('petroleum.nozzles.index')->with('success', 'Nozzle updated successfully.');
    }

    public function destroy(FuelNozzle $nozzle)
    {
        $this->ensureAdmin();
        $this->ensureSameStation($nozzle);
        $nozzle->delete();

        return redirect()->route('petroleum.nozzles.index')->with('success', 'Nozzle deleted.');
    }

    private function formData(): array
    {
        $branchId = $this->branchId();

        return [
            'pumps' => FuelPump::where('branch_id', $branchId)->orderBy('number')->get(),
            'tanks' => FuelTank::with('product')->where('branch_id', $branchId)->where('status', 'active')->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, ?FuelNozzle $nozzle = null): array
    {
        $branchId = $this->branchId();

        $data = $request->validate([
            'fuel_pump_id' => ['required', Rule::exists('fuel_pumps', 'id')->where('branch_id', $branchId)->whereNull('deleted_at')],
            'fuel_tank_id' => ['required', Rule::exists('fuel_tanks', 'id')->where('branch_id', $branchId)->whereNull('deleted_at')],
            'number' => ['required', 'integer', 'min:1', Rule::unique('fuel_nozzles', 'number')
                ->where('fuel_pump_id', $request->input('fuel_pump_id'))
                ->ignore($nozzle?->id)
                ->whereNull('deleted_at')],
            'meter_reading' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        if (!empty($data['meter_reading']) && $nozzle && (float) $data['meter_reading'] < (float) $nozzle->meter_reading) {
            throw \Illuminate\Validation\ValidationException::withMessages(['meter_reading' => 'A meter reading cannot go backwards.']);
        }

        return $data;
    }

    private function ensureSameStation(FuelNozzle $nozzle): void
    {
        abort_unless((int) $nozzle->branch_id === $this->branchId(), 404);
    }
}
