<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

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
            ->with('success', 'Ghala "' . $request->name . '" limeongezwa.');
    }

    public function show(Warehouse $warehouse)
    {
        $base = WarehouseStock::where('warehouse_id', $warehouse->id)->where('quantity', '>', 0);

        $stocks = (clone $base)
            ->with('product.category', 'product.unit')
            ->orderBy('quantity', 'desc')
            ->paginate(20);

        $stats = [
            'units'    => (float) (clone $base)->sum('quantity'),
            'products' => (clone $base)->count(),
            'low'      => (clone $base)->where('quantity', '<=', 5)->count(),
        ];

        $allProducts = Product::where('track_stock', true)->orderBy('name')->get();

        return view('warehouses.show', compact('warehouse', 'stocks', 'stats', 'allProducts'));
    }

    public function addStockDirect(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|numeric|min:0.01',
            'movement_date' => 'required|date',
            'notes'         => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = (float) $request->quantity;
        $shopId = Auth::user()->shop_id;

        // Ensure schema columns are up to date BEFORE starting a transaction
        // (DDL statements cause an implicit COMMIT in MySQL which breaks transactions)
        StockMovement::ensureColumnTypes();

        DB::transaction(function() use ($request, $warehouse, $product, $qty, $shopId) {
            $ws = WarehouseStock::firstOrCreate(
                ['warehouse_id' => $warehouse->id, 'product_id' => $product->id],
                ['quantity' => 0]
            );
            $ws->increment('quantity', $qty);

            try {
                StockMovement::create([
                    'shop_id'            => $shopId,
                    'product_id'         => $product->id,
                    'moved_by'           => Auth::id(),
                    'from_type'          => 'supplier',
                    'from_warehouse_id'  => null,
                    'to_type'            => 'warehouse',
                    'to_warehouse_id'    => $warehouse->id,
                    'quantity'           => $qty,
                    'notes'              => $request->notes ?: 'Upokeaji wa mzigo ghalani (Direct warehouse intake)',
                    'movement_date'      => $request->movement_date,
                ]);
            } catch (\Throwable $smEx) {
                // If legacy enum rejects 'supplier', record safely as 'shop' without breaking the transaction
                try {
                    StockMovement::create([
                        'shop_id'            => $shopId,
                        'product_id'         => $product->id,
                        'moved_by'           => Auth::id(),
                        'from_type'          => 'shop',
                        'from_warehouse_id'  => null,
                        'to_type'            => 'warehouse',
                        'to_warehouse_id'    => $warehouse->id,
                        'quantity'           => $qty,
                        'notes'              => '[Supplier] ' . ($request->notes ?: 'Upokeaji wa mzigo ghalani'),
                        'movement_date'      => $request->movement_date,
                    ]);
                } catch (\Throwable $retryEx) {
                    \Illuminate\Support\Facades\Log::warning('StockMovement recording failed: ' . $retryEx->getMessage());
                }
            }
        });

        return back()->with('success', 'Bidhaa ya "' . $product->name . '" (Vipimo ' . number_format($qty) . ') imeongezwa kwenye ghala la ' . $warehouse->name . ' kikamilifu.');
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
            ->with('success', 'Ghala limesasishwa.');
    }

    public function destroy(Warehouse $warehouse)
    {
        // Check if warehouse has stock
        $hasStock = WarehouseStock::where('warehouse_id', $warehouse->id)
            ->where('quantity', '>', 0)->exists();

        if ($hasStock) {
            return back()->with('error', 'Haiwezekani kufuta ghala lenye stock. Hamisha stock kwanza.');
        }

        $warehouse->delete();
        return redirect()->route('warehouses.index')
            ->with('success', 'Ghala limefutwa.');
    }

    // === Stock Transfer ===
    public function transferCreate()
    {
        $shopId = Auth::user()->shop_id;
        $warehouses = Warehouse::where('shop_id', $shopId)->where('is_active', true)->orderBy('name')->get();
        $branches   = Branch::where('shop_id', $shopId)->where('is_active', true)->orderBy('name')->get();

        if ($branches->isEmpty()) {
            $defaultBranch = Branch::firstOrCreate(
                ['shop_id' => $shopId, 'name' => 'Duka Kuu (Main Store)'],
                ['is_active' => true]
            );
            $branches = collect([$defaultBranch]);
        }

        $activeBranchId = $this->getActiveBranchId() ?? ($branches->first()->id ?? null);

        $products = Product::where('shop_id', $shopId)
            ->where('track_stock', true)
            ->with(['warehouseStocks', 'branches'])
            ->orderBy('name')
            ->get();

        // Build a client-friendly map for instant stock availability lookups
        $productStockMap = [];
        foreach ($products as $p) {
            $whMap = [];
            foreach ($p->warehouseStocks as $ws) {
                $whMap[$ws->warehouse_id] = (float) $ws->quantity;
            }

            $branchMap = [];
            foreach ($p->branches as $b) {
                $branchMap[$b->id] = (float) ($b->pivot->quantity ?? 0);
            }

            // Ensure active branch has an entry in branchMap
            if ($activeBranchId && !isset($branchMap[$activeBranchId])) {
                $branchMap[$activeBranchId] = (float) ($p->stock ?? 0);
            }

            $productStockMap[$p->id] = [
                'id'         => $p->id,
                'name'       => $p->name,
                'sku'        => $p->sku,
                'shop_stock' => (float) ($branchMap[$activeBranchId] ?? ($p->stock ?? 0)),
                'warehouses' => $whMap,
                'branches'   => $branchMap,
            ];
            $p->shop_stock = (float) ($branchMap[$activeBranchId] ?? ($p->stock ?? 0));
        }

        return view('warehouses.transfer', compact('warehouses', 'branches', 'activeBranchId', 'products', 'productStockMap'));
    }

    public function transferStore(Request $request)
    {
        $shopId = Auth::user()->shop_id;

        $request->validate([
            'from_type'          => 'required|in:shop,branch,warehouse',
            'from_warehouse_id'  => 'nullable|required_if:from_type,warehouse|exists:warehouses,id',
            'from_branch_id'     => 'nullable|exists:branches,id',
            'to_type'            => 'required|in:shop,branch,warehouse',
            'to_warehouse_id'    => 'nullable|required_if:to_type,warehouse|exists:warehouses,id',
            'to_branch_id'       => 'nullable|exists:branches,id',
            'movement_date'      => 'required|date',
            'notes'              => 'nullable|string',
            // Multi-product array
            'items'              => 'nullable|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.01',
            // Fallback for single product
            'product_id'         => 'nullable|exists:products,id',
            'quantity'           => 'nullable|numeric|min:0.01',
        ]);

        $fromType = in_array($request->from_type, ['shop', 'branch']) ? 'branch' : 'warehouse';
        $toType   = in_array($request->to_type, ['shop', 'branch']) ? 'branch' : 'warehouse';

        // Resolve branch IDs if shop/branch was chosen
        $fromBranchId = null;
        $toBranchId   = null;

        if ($fromType === 'branch') {
            $fromBranchId = $request->from_branch_id ?: ($this->getActiveBranchId() ?: Branch::where('shop_id', $shopId)->value('id'));
            if (!$fromBranchId) {
                $fromBranchId = Branch::firstOrCreate(['shop_id' => $shopId, 'name' => 'Duka Kuu (Main Store)'], ['is_active' => true])->id;
            }
        }

        if ($toType === 'branch') {
            $toBranchId = $request->to_branch_id ?: ($this->getActiveBranchId() ?: Branch::where('shop_id', $shopId)->value('id'));
            if (!$toBranchId) {
                $toBranchId = Branch::firstOrCreate(['shop_id' => $shopId, 'name' => 'Duka Kuu (Main Store)'], ['is_active' => true])->id;
            }
        }

        $fromWhId = $fromType === 'warehouse' ? (int) $request->from_warehouse_id : null;
        $toWhId   = $toType   === 'warehouse' ? (int) $request->to_warehouse_id   : null;

        // Disallow same source and destination
        if ($fromType === $toType) {
            if ($fromType === 'warehouse' && $fromWhId === $toWhId) {
                return back()->with('error', 'Ghala la kutolea na ghala la kupelekea hayawezi kuwa sawa.')->withInput();
            }
            if ($fromType === 'branch' && $fromBranchId === $toBranchId) {
                return back()->with('error', 'Duka/Tawi la kutolea na duka/tawi la kupelekea hayawezi kuwa sawa.')->withInput();
            }
        }

        // Strict multi-tenant verification: Ensure all entities belong to the user's shop!
        if ($fromWhId && !Warehouse::where('id', $fromWhId)->where('shop_id', $shopId)->exists()) {
            abort(403, 'Ghala hili si mali ya duka lako.');
        }
        if ($toWhId && !Warehouse::where('id', $toWhId)->where('shop_id', $shopId)->exists()) {
            abort(403, 'Ghala hili si mali ya duka lako.');
        }
        if ($fromBranchId && !Branch::where('id', $fromBranchId)->where('shop_id', $shopId)->exists()) {
            abort(403, 'Tawi hili si mali ya duka lako.');
        }
        if ($toBranchId && !Branch::where('id', $toBranchId)->where('shop_id', $shopId)->exists()) {
            abort(403, 'Tawi hili si mali ya duka lako.');
        }

        // Collect transfer items
        $items = $request->input('items', []);
        if (empty($items) && $request->filled('product_id') && $request->filled('quantity')) {
            $items[] = [
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ];
        }

        if (empty($items)) {
            return back()->with('error', 'Tafadhali chagua angalau bidhaa moja ya kuhamisha.')->withInput();
        }

        $totalItemsCount = 0;
        $totalQtyCount = 0;
        $transferredSummary = [];

        // Ensure schema columns exist before starting transaction
        StockMovement::ensureColumnTypes();

        try {
            DB::transaction(function () use ($request, $items, $shopId, $fromType, $toType, $fromWhId, $toWhId, $fromBranchId, $toBranchId, &$totalItemsCount, &$totalQtyCount, &$transferredSummary) {
                // Group quantities if same product was added in multiple rows
                $aggregated = [];
                foreach ($items as $item) {
                    $pid = (int) ($item['product_id'] ?? 0);
                    $qty = (float) ($item['quantity'] ?? 0);
                    if ($pid <= 0 || $qty <= 0) continue;
                    if (!isset($aggregated[$pid])) {
                        $aggregated[$pid] = 0;
                    }
                    $aggregated[$pid] += $qty;
                }

                if (empty($aggregated)) {
                    throw new \RuntimeException('Tafadhali jaza kiasi halali cha bidhaa.');
                }

                foreach ($aggregated as $productId => $qty) {
                    // Re-read product under lock - MUST belong to current shop
                    $product = Product::where('id', $productId)->where('shop_id', $shopId)->lockForUpdate()->first();
                    if (!$product) {
                        throw new \RuntimeException('Bidhaa namba #' . $productId . ' haikupatikana kwenye duka lako.');
                    }

                    // 1. Deduct from source
                    if ($fromType === 'warehouse') {
                        $whStock = WarehouseStock::where('warehouse_id', $fromWhId)
                            ->where('product_id', $product->id)->lockForUpdate()->first();
                        $available = $whStock ? (float) $whStock->quantity : 0;
                        if (!$whStock || $available < $qty) {
                            throw new \RuntimeException('Stock ya ghala haitoshi kwa "' . $product->name . '". Ipo ghalani: ' . $available . ', Unayotaka kuhamisha: ' . $qty);
                        }
                        $whStock->decrement('quantity', $qty);
                    } else {
                        // From Branch
                        $bp = DB::table('branch_product')
                            ->where('branch_id', $fromBranchId)
                            ->where('product_id', $product->id)
                            ->first();

                        $available = $bp ? (float) $bp->quantity : (float) ($product->stock ?? 0);
                        if ($product->track_stock && $available < $qty) {
                            $bName = Branch::where('id', $fromBranchId)->value('name') ?: 'Duka';
                            throw new \RuntimeException('Stock ya ' . $bName . ' haitoshi kwa "' . $product->name . '". Ipo: ' . $available . ', Unayotaka kuhamisha: ' . $qty);
                        }

                        if ($bp) {
                            DB::table('branch_product')
                                ->where('branch_id', $fromBranchId)
                                ->where('product_id', $product->id)
                                ->decrement('quantity', $qty);
                        } else {
                            DB::table('branch_product')->insert([
                                'branch_id'  => $fromBranchId,
                                'product_id' => $product->id,
                                'quantity'   => max(0, $available - $qty),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                        if ($product->track_stock) {
                            $product->decrement('stock', $qty);
                        }
                    }

                    // 2. Add to destination
                    if ($toType === 'warehouse') {
                        $ws = WarehouseStock::firstOrCreate(
                            ['warehouse_id' => $toWhId, 'product_id' => $product->id],
                            ['quantity' => 0]
                        );
                        $ws->increment('quantity', $qty);
                    } else {
                        // To Branch
                        $bp = DB::table('branch_product')
                            ->where('branch_id', $toBranchId)
                            ->where('product_id', $product->id)
                            ->first();

                        if ($bp) {
                            DB::table('branch_product')
                                ->where('branch_id', $toBranchId)
                                ->where('product_id', $product->id)
                                ->increment('quantity', $qty);
                            DB::table('branch_product')
                                ->where('branch_id', $toBranchId)
                                ->where('product_id', $product->id)
                                ->update(['updated_at' => now()]);
                        } else {
                            DB::table('branch_product')->insert([
                                'branch_id'  => $toBranchId,
                                'product_id' => $product->id,
                                'quantity'   => $qty,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                        // Also increment product.stock
                        $product->increment('stock', $qty);
                    }

                    // 3. Record movement
                    StockMovement::create([
                        'shop_id'            => $shopId,
                        'product_id'         => $product->id,
                        'moved_by'           => Auth::id(),
                        'from_type'          => $fromType,
                        'from_warehouse_id'  => $fromWhId,
                        'from_branch_id'     => $fromBranchId,
                        'to_type'            => $toType,
                        'to_warehouse_id'    => $toWhId,
                        'to_branch_id'       => $toBranchId,
                        'quantity'           => $qty,
                        'notes'              => $request->notes,
                        'movement_date'      => $request->movement_date,
                    ]);

                    $totalItemsCount++;
                    $totalQtyCount += $qty;
                    $transferredSummary[] = number_format($qty) . ' x ' . $product->name;
                }
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        $successMsg = $totalItemsCount === 1
            ? 'Uhamisho wa stock umekamilika: ' . $transferredSummary[0]
            : 'Uhamisho wa stock umekamilika. Aina ' . $totalItemsCount . ' za bidhaa (Jumla vipimo ' . number_format($totalQtyCount) . ') zimehamishwa kikamilifu.';

        return redirect()->route('stock.movements.index')->with('success', $successMsg);
    }

    public function movements(Request $request)
    {
        $shopId = Auth::user()->shop_id;

        $movements = StockMovement::with(['product', 'movedBy', 'fromWarehouse', 'toWarehouse', 'fromBranch', 'toBranch'])
            ->where('shop_id', $shopId)
            ->when($request->product_id, fn($q) => $q->where('product_id', $request->product_id))
            ->when($request->warehouse_id, fn($q) => $q->where(function($q2) use ($request) {
                $q2->where('from_warehouse_id', $request->warehouse_id)
                   ->orWhere('to_warehouse_id', $request->warehouse_id);
            }))
            ->when($request->branch_id, fn($q) => $q->where(function($q2) use ($request) {
                $q2->where('from_branch_id', $request->branch_id)
                   ->orWhere('to_branch_id', $request->branch_id);
            }))
            ->when($request->date_from, fn($q) => $q->whereDate('movement_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('movement_date', '<=', $request->date_to))
            ->orderBy('movement_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(25);

        $warehouses = Warehouse::where('shop_id', $shopId)->orderBy('name')->get();
        $branches   = Branch::where('shop_id', $shopId)->orderBy('name')->get();
        $products   = Product::where('shop_id', $shopId)->orderBy('name')->get();

        return view('warehouses.movements', compact('movements', 'warehouses', 'branches', 'products'));
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
