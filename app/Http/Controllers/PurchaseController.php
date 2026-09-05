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
        $branches = \App\Models\Branch::where('shop_id', \Illuminate\Support\Facades\Auth::user()->shop_id)->get();
        
        $reference_no = 'PR-' . date('Ymd') . '-' . rand(1000, 9999);
        
        return view('purchases.create', compact('suppliers', 'products', 'branches', 'reference_no'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'reference_no' => ['required', \Illuminate\Validation\Rule::unique('purchases')->where('shop_id', \Illuminate\Support\Facades\Auth::user()->shop_id)],
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

        $branchId = $request->branch_id ?? $this->getActiveBranchId();
        if (!$branchId) {
            $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
        }

        $purchase = \App\Models\Purchase::create([
            'supplier_id' => $request->supplier_id,
            'reference_no' => $request->reference_no,
            'purchase_date' => $request->purchase_date,
            'status' => $request->status,
            'notes' => $request->notes,
            'total_amount' => $total_amount,
            'branch_id' => $branchId
        ]);

        foreach ($items as $item) {
            $purchase->items()->create($item);
            
            if ($request->status == 'completed') {
                $product = \App\Models\Product::find($item['product_id']);
                if ($product) {
                    $branchId = $purchase->branch_id;
                    \Illuminate\Support\Facades\DB::table('branch_product')->updateOrInsert(
                        ['branch_id' => $branchId, 'product_id' => $product->id],
                        ['quantity' => \Illuminate\Support\Facades\DB::raw('quantity + ' . $item['quantity'])]
                    );
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
        $purchase = \App\Models\Purchase::with('items.product')->findOrFail($id);

        // Security: make sure purchase belongs to this shop
        if ($purchase->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // If completed, reverse the stock
            if ($purchase->status === 'completed') {
                $branchId = $purchase->branch_id;

                foreach ($purchase->items as $item) {
                    $product = $item->product;
                    if (!$product) continue;

                    if ($branchId) {
                        $branchStock = \Illuminate\Support\Facades\DB::table('branch_product')
                            ->where('branch_id', $branchId)
                            ->where('product_id', $product->id)
                            ->value('quantity') ?? 0;

                        $newQty = max(0, $branchStock - $item->quantity);
                        \Illuminate\Support\Facades\DB::table('branch_product')
                            ->where('branch_id', $branchId)
                            ->where('product_id', $product->id)
                            ->update(['quantity' => $newQty]);
                    } else {
                        $newStock = max(0, $product->stock - $item->quantity);
                        $product->update(['stock' => $newStock]);
                    }
                }
            }

            $purchase->items()->delete();
            $purchase->delete();

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase deleted and stock reversed successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('purchases.index')->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }
}
