<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::withCount('stocks')
            ->with('stocks')
            ->orderBy('name')
            ->get();

        // Total stock per warehouse
        $warehouses->each(function ($wh) {
            $wh->total_qty = $wh->stocks->sum('quantity');
            $wh->total_products = $wh->stocks->count();
        });

        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Warehouse::create([
            'name'        => $request->name,
            'location'    => $request->location,
            'description' => $request->description,
            'is_active'   => true,
        ]);

        return redirect()->route('warehouses.index')
            ->with('success', 'Godown "' . $request->name . '" imeongezwa.');
    }

    public function show(Warehouse $warehouse)
    {
        $stocks = WarehouseStock::with('product.category', 'product.unit')
            ->where('warehouse_id', $warehouse->id)
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'desc')
            ->paginate(20);

        $allProducts = Product::where('track_stock', true)
            ->orderBy('name')
            ->get();

        return view('warehouses.show', compact('warehouse', 'stocks', 'allProducts'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $warehouse->update([
            'name'        => $request->name,
            'location'    => $request->location,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('warehouses.index')
            ->with('success', 'Godown imesasishwa.');
    }

    public function destroy(Warehouse $warehouse)
    {
        // Check if warehouse has stock
        $hasStock = WarehouseStock::where('warehouse_id', $warehouse->id)
            ->where('quantity', '>', 0)->exists();

        if ($hasStock) {
            return back()->with('error', 'Haiwezekani kufuta godown yenye stock. Hamisha stock kwanza.');
        }

        $warehouse->delete();
        return redirect()->route('warehouses.index')
            ->with('success', 'Godown imefutwa.');
    }

    // === Stock Transfer ===
    public function transferCreate()
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $products   = Product::where('track_stock', true)->orderBy('name')->get();

        // Attach shop stock and warehouse stock
        $products->each(function ($p) {
            $p->shop_stock = $p->stock ?? 0;
        });

        return view('warehouses.transfer', compact('warehouses', 'products'));
    }

    public function transferStore(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'from_type'        => 'required|in:shop,warehouse',
            'from_warehouse_id'=> 'nullable|required_if:from_type,warehouse|exists:warehouses,id',
            'to_type'          => 'required|in:shop,warehouse',
            'to_warehouse_id'  => 'nullable|required_if:to_type,warehouse|exists:warehouses,id',
            'quantity'         => 'required|numeric|min:0.01',
            'notes'            => 'nullable|string',
            'movement_date'    => 'required|date',
        ]);

        if ($request->from_type === $request->to_type &&
            $request->from_warehouse_id == $request->to_warehouse_id) {
            return back()->with('error', 'Chanzo na marudio haziwezi kuwa sawa.')->withInput();
        }

        $product  = Product::findOrFail($request->product_id);
        $qty      = (float) $request->quantity;
        $shopId   = Auth::user()->shop_id;

        DB::transaction(function () use ($request, $product, $qty, $shopId) {
            // Deduct from source
            if ($request->from_type === 'shop') {
                if ($product->track_stock && $product->stock < $qty) {
                    throw new \Exception('Stock ya duka haitoshi. Ipo: ' . $product->stock);
                }
                if ($product->track_stock) {
                    $product->decrement('stock', $qty);
                }
            } else {
                $whStock = WarehouseStock::where('warehouse_id', $request->from_warehouse_id)
                    ->where('product_id', $product->id)->first();
                $available = $whStock ? $whStock->quantity : 0;
                if ($available < $qty) {
                    throw new \Exception('Stock ya godown haitoshi. Ipo: ' . $available);
                }
                $whStock->decrement('quantity', $qty);
            }

            // Add to destination
            if ($request->to_type === 'shop') {
                if ($product->track_stock) {
                    $product->increment('stock', $qty);
                }
            } else {
                WarehouseStock::updateOrCreate(
                    ['warehouse_id' => $request->to_warehouse_id, 'product_id' => $product->id],
                    ['quantity' => 0]
                );
                WarehouseStock::where('warehouse_id', $request->to_warehouse_id)
                    ->where('product_id', $product->id)
                    ->increment('quantity', $qty);
            }

            // Record movement
            StockMovement::create([
                'shop_id'            => $shopId,
                'product_id'         => $product->id,
                'moved_by'           => Auth::id(),
                'from_type'          => $request->from_type,
                'from_warehouse_id'  => $request->from_type === 'warehouse' ? $request->from_warehouse_id : null,
                'to_type'            => $request->to_type,
                'to_warehouse_id'    => $request->to_type === 'warehouse' ? $request->to_warehouse_id : null,
                'quantity'           => $qty,
                'notes'              => $request->notes,
                'movement_date'      => $request->movement_date,
            ]);
        });

        return redirect()->route('stock.movements.index')
            ->with('success', 'Uhamisho wa stock umefanyika. ' . number_format($qty) . ' x ' . $product->name);
    }

    public function movements(Request $request)
    {
        $movements = StockMovement::with('product', 'movedBy', 'fromWarehouse', 'toWarehouse')
            ->when($request->product_id, fn($q) => $q->where('product_id', $request->product_id))
            ->when($request->warehouse_id, fn($q) => $q->where(function($q2) use ($request) {
                $q2->where('from_warehouse_id', $request->warehouse_id)
                   ->orWhere('to_warehouse_id', $request->warehouse_id);
            }))
            ->when($request->date_from, fn($q) => $q->whereDate('movement_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('movement_date', '<=', $request->date_to))
            ->orderBy('movement_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(25);

        $warehouses = Warehouse::orderBy('name')->get();
        $products   = Product::orderBy('name')->get();

        return view('warehouses.movements', compact('movements', 'warehouses', 'products'));
    }

    // AJAX: get stock info for a product
    public function getProductStock(Request $request)
    {
        $product = Product::find($request->product_id);
        if (!$product) return response()->json([]);

        $warehouseStocks = WarehouseStock::with('warehouse')
            ->where('product_id', $product->id)
            ->where('quantity', '>', 0)
            ->get()
            ->map(fn($ws) => [
                'warehouse_id'   => $ws->warehouse_id,
                'warehouse_name' => $ws->warehouse->name,
                'quantity'       => $ws->quantity,
            ]);

        return response()->json([
            'shop_stock'       => $product->stock,
            'warehouse_stocks' => $warehouseStocks,
        ]);
    }
}
