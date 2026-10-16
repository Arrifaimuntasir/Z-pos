<?php

namespace App\Http\Controllers\Petroleum;

use App\Http\Controllers\Controller;
use App\Models\FuelDailyClosing;
use App\Models\FuelDailyOpening;
use App\Models\FuelNozzle;
use App\Models\FuelTank;
use App\Services\PetroleumDay;
use App\Services\PetroleumStock;
use Illuminate\Http\Request;
use RuntimeException;

class FuelDayController extends Controller
{
    private function branchId(): int
    {
        return PetroleumStock::stationBranchId($this->getActiveBranchId());
    }

    public function index()
    {
        $branchId = $this->branchId();
        $open = PetroleumDay::openDay($branchId);
        $summary = $open ? PetroleumDay::reconcile($open) : null;
        $days = FuelDailyOpening::where('branch_id', $branchId)->latest('business_date')->limit(30)->get();
        $suggestedDate = $open ? null : now()->toDateString();

        return view('petroleum.day.index', compact('open', 'summary', 'days', 'suggestedDate'));
    }

    public function open(Request $request)
    {
        $data = $request->validate([
            'business_date' => 'required|date',
            'opening_cash' => 'nullable|numeric|min:0',
        ]);

        try {
            PetroleumDay::open($this->branchId(), $data['business_date'], (float) ($data['opening_cash'] ?? 0), auth()->id());
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('petroleum.day.index')->with('success', 'Station day opened. Tank levels and meter readings are recorded.');
    }

    public function closeForm()
    {
        $this->ensureAdmin();
        $open = PetroleumDay::openDay($this->branchId());
        if (!$open) {
            return redirect()->route('petroleum.day.index')->with('error', 'There is no open station day to close.');
        }

        return view('petroleum.day.close', ['open' => $open] + PetroleumDay::reconcile($open));
    }

    public function close(Request $request)
    {
        $this->ensureAdmin();
        $open = PetroleumDay::openDay($this->branchId());
        if (!$open) {
            return redirect()->route('petroleum.day.index')->with('error', 'There is no open station day to close.');
        }

        $data = $request->validate([
            'tank_physical' => 'required|array',
            'nozzle_physical' => 'required|array',
            'physical_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $closing = PetroleumDay::close(
                $open,
                $data['tank_physical'],
                $data['nozzle_physical'],
                (float) $data['physical_cash'],
                auth()->id(),
                $data['notes'] ?? null
            );
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('petroleum.day.closings.show', $closing)->with('success', 'Station day closed and reconciled.');
    }

    public function showClosing(FuelDailyClosing $closing)
    {
        $this->ensureAdmin();
        abort_unless((int) $closing->branch_id === $this->branchId(), 404);
        $closing->load('lines');

        return view('petroleum.day.closing', compact('closing'));
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()->hasRole('Administrator'), 403);
    }
}
