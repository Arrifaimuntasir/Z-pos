<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefectiveStockController extends Controller
{
    /**
     * Resolve the branch to adjust stock in. Falls back to the shop's first
     * branch when no branch is active in session (e.g. single-branch shops
     * where the admin never explicitly picked one) — same pattern used by
     * PurchaseController, so writes land in branch_product like everywhere else.
     */
    private function resolveBranchId()
    {
        $branchId = $this->getActiveBranchId();
        if (!$branchId) {
            $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
        }
        return $branchId;
    }

    /**
     * Current stock of a product, in the given branch (or unbranched shop stock).
     */
    private function currentStockOf($branchId, \App\Models\Product $product)
    {
        if ($branchId) {
            return \Illuminate\Support\Facades\DB::table('branch_product')
                ->where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->value('quantity') ?? 0;
        }
        return $product->stock;
    }

    /**
     * Add (positive delta) or remove (negative delta) stock for a product.
     */
    private function adjustStock($branchId, \App\Models\Product $product, $delta)
    {
        if ($delta == 0) {
            return;
        }

        if ($branchId) {
            $row = \Illuminate\Support\Facades\DB::table('branch_product')
                ->where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->first();

            if ($row) {
                \Illuminate\Support\Facades\DB::table('branch_product')
                    ->where('branch_id', $branchId)
                    ->where('product_id', $product->id)
                    ->update(['quantity' => max(0, $row->quantity + $delta)]);
            } elseif ($delta > 0) {
                \Illuminate\Support\Facades\DB::table('branch_product')->insert([
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                    'quantity' => $delta,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            $product->update(['stock' => max(0, $product->stock + $delta)]);
        }
    }

    /**
     * Products list with current_stock attached, for the select dropdown.
     */
    private function productsWithStock($branchId)
    {
        $products = \App\Models\Product::where('is_active', true)->orderBy('name')->get();

        $stockMap = [];
        if ($branchId) {
            $stockMap = \Illuminate\Support\Facades\DB::table('branch_product')
                ->where('branch_id', $branchId)
                ->pluck('quantity', 'product_id');
        }

        foreach ($products as $product) {
            $product->current_stock = $branchId ? ($stockMap[$product->id] ?? 0) : $product->stock;
        }

        return $products;
    }

    /**
     * Display a listing of recorded defective/damaged stock write-offs.
     */
    public function index()
    {
        $defectiveStocks = \App\Models\DefectiveStock::with(['product', 'branch', 'user'])->latest()->get();
        return view('defective-stock.index', compact('defectiveStocks'));
    }

    /**
     * Show the form for recording defective stock to remove from inventory.
     */
    public function create()
    {
        $branchId = $this->resolveBranchId();
        $products = $this->productsWithStock($branchId);

        return view('defective-stock.create', compact('products'));
    }

    /**
     * Remove the defective quantity from stock and log it.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:500',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        $branchId = $this->resolveBranchId();
        $currentStock = $this->currentStockOf($branchId, $product);

        if ($request->quantity > $currentStock) {
            return back()->withInput()->with('error', "Cannot remove {$request->quantity} units of {$product->name} — only {$currentStock} in stock.");
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $this->adjustStock($branchId, $product, -$request->quantity);

            \App\Models\DefectiveStock::create([
                'branch_id' => $branchId,
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'quantity' => $request->quantity,
                'reason' => $request->reason,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('defective-stock.index')->with('success', 'Defective stock removed successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->with('error', 'Error removing defective stock: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a recorded defective stock entry.
     */
    public function edit(\App\Models\DefectiveStock $defectiveStock)
    {
        if ($defectiveStock->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $products = $this->productsWithStock($defectiveStock->branch_id);

        return view('defective-stock.edit', compact('defectiveStock', 'products'));
    }

    /**
     * Update a recorded defective stock entry, adjusting stock by the difference.
     */
    public function update(Request $request, \App\Models\DefectiveStock $defectiveStock)
    {
        if ($defectiveStock->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:500',
        ]);

        $branchId = $defectiveStock->branch_id;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Restore the old deduction first
            $oldProduct = \App\Models\Product::find($defectiveStock->product_id);
            if ($oldProduct) {
                $this->adjustStock($branchId, $oldProduct, $defectiveStock->quantity);
            }

            $newProduct = \App\Models\Product::findOrFail($request->product_id);
            $currentStock = $this->currentStockOf($branchId, $newProduct);

            if ($request->quantity > $currentStock) {
                throw new \Exception("Cannot remove {$request->quantity} units of {$newProduct->name} — only {$currentStock} in stock.");
            }

            $this->adjustStock($branchId, $newProduct, -$request->quantity);

            $defectiveStock->update([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('defective-stock.index')->with('success', 'Record updated successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->with('error', 'Error updating record: ' . $e->getMessage());
        }
    }

    /**
     * Delete a defective stock record, optionally restoring the quantity to stock.
     */
    public function destroy(Request $request, \App\Models\DefectiveStock $defectiveStock)
    {
        if ($defectiveStock->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            if ($request->boolean('restore_stock')) {
                $product = \App\Models\Product::find($defectiveStock->product_id);
                if ($product) {
                    $this->adjustStock($defectiveStock->branch_id, $product, $defectiveStock->quantity);
                }
            }

            $defectiveStock->delete();

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('defective-stock.index')->with('success', 'Record deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }
}
