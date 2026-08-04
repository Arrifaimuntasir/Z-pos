<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = \App\Models\Unit::latest()->get();
        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50'
        ]);

        \App\Models\Unit::create([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'allow_decimal' => $request->has('allow_decimal')
        ]);

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(\App\Models\Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, \App\Models\Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50'
        ]);

        $unit->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'allow_decimal' => $request->has('allow_decimal')
        ]);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(\App\Models\Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
