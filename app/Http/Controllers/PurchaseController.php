<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = \App\Models\Purchase::with('supplier')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = \App\Models\Supplier::all();
        $products = \App\Models\Product::all();
        
        $reference_no = 'PR-' . date('Ymd') . '-' . rand(1000, 9999);
        
        return view('purchases.create', compact('suppliers', 'products', 'reference_no'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'reference_no' => 'required|unique:purchases',
            'purchase_date' => 'required|date',
            'status' => 'required|string',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'unit_cost' => 'required|array'
        ]);

        $total_amount = 0;
        $items = [];
        
        for ($i = 0; $i < count($request->product_id); $i++) {
            $qty = $request->quantity[$i];
            $cost = $request->unit_cost[$i];
            $subtotal = $qty * $cost;
            $total_amount += $subtotal;
            
            $items[] = [
                'product_id' => $request->product_id[$i],
                'quantity' => $qty,
                'unit_cost' => $cost,
                'subtotal' => $subtotal
            ];
        }

        $purchase = \App\Models\Purchase::create([
            'supplier_id' => $request->supplier_id,
            'reference_no' => $request->reference_no,
            'purchase_date' => $request->purchase_date,
            'status' => $request->status,
            'notes' => $request->notes,
            'total_amount' => $total_amount
        ]);

        foreach ($items as $item) {
            $purchase->items()->create($item);
            
            if ($request->status == 'completed') {
                $product = \App\Models\Product::find($item['product_id']);
                if ($product) {
                    $product->stock_quantity += $item['quantity'];
                    $product->save();
                }
            }
        }

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = \App\Models\Purchase::with(['supplier', 'items.product'])->findOrFail($id);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // For simplicity, we won't allow editing completed purchases in this basic setup
        return redirect()->route('purchases.index')->with('error', 'Editing purchases is not supported in this version.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = \App\Models\Purchase::findOrFail($id);
        
        // If it was completed, we should ideally reverse the stock, but for simplicity we just delete it or prevent deletion
        if ($purchase->status == 'completed') {
            return redirect()->route('purchases.index')->with('error', 'Cannot delete a completed purchase. Stock has already been updated.');
        }
        
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
