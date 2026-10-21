<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $branchId = $this->getActiveBranchId();

        $products = Product::with(['category', 'brand', 'unit', 'branches'])
            ->when($branchId, function ($query) use ($branchId) {
                // If a branch is selected, show only products in that branch
                $query->whereHas('branches', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q1) use ($search) {
                    $q1->where('name', 'like', "%{$search}%")
                          ->orWhere('sku', 'like', "%{$search}%")
                          ->orWhereHas('category', function ($q2) use ($search) {
                              $q2->where('name', 'like', "%{$search}%");
                          })
                          ->orWhereHas('brand', function ($q2) use ($search) {
                              $q2->where('name', 'like', "%{$search}%");
                          });
                });
            })
            ->latest()
            ->paginate(15)
            ->appends(['search' => $search]);

        $activeBranchId = $branchId;
        $activeBranch = $branchId ? \App\Models\Branch::find($branchId) : null;
        $shopHasBranches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->exists();

        return view('products.index', compact('products', 'search', 'activeBranchId', 'activeBranch', 'shopHasBranches'));
    }

    public function create()
    {
        if (!auth()->user()->canManageProducts()) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $branches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->get();
        $allProducts = Product::all();
        $activeBranchId = $this->getActiveBranchId();
        $warehouses = \App\Models\Warehouse::where('is_active', true)->orderBy('name')->get();
        $selectedWarehouseId = request('warehouse_id');

        return view('products.create', compact('categories', 'brands', 'units', 'branches', 'allProducts', 'activeBranchId', 'warehouses', 'selectedWarehouseId'));
    }

    protected function resolveCategoryBrandUnit(Request $request): void
    {
        $shopId = auth()->user()->shop_id;

        // 1. Category: use newly typed category if given, or fallback to 'General' if none chosen
        if ($request->filled('new_category')) {
            $catName = trim($request->input('new_category'));
            if ($catName !== '') {
                $cat = Category::firstOrCreate(
                    ['name' => $catName],
                    ['is_active' => true, 'shop_id' => $shopId]
                );
                $request->merge(['category_id' => $cat->id]);
            }
        } elseif (!$request->filled('category_id') || $request->input('category_id') === '__new__') {
            $defaultCat = Category::firstOrCreate(
                ['name' => 'General'],
                ['is_active' => true, 'shop_id' => $shopId]
            );
            $request->merge(['category_id' => $defaultCat->id]);
        }

        // 2. Brand: use newly typed brand if given
        if ($request->filled('new_brand')) {
            $brandName = trim($request->input('new_brand'));
            if ($brandName !== '') {
                $brand = Brand::firstOrCreate(
                    ['name' => $brandName],
                    ['is_active' => true, 'shop_id' => $shopId]
                );
                $request->merge(['brand_id' => $brand->id]);
            }
        } elseif ($request->input('brand_id') === '__new__') {
            $request->merge(['brand_id' => null]);
        }

        // 3. Unit: use newly typed unit if given
        if ($request->filled('new_unit')) {
            $unitName = trim($request->input('new_unit'));
            if ($unitName !== '') {
                $unit = Unit::firstOrCreate(
                    ['name' => $unitName],
                    [
                        'short_name' => mb_substr($unitName, 0, 10),
                        'allow_decimal' => false,
                        'is_active' => true,
                        'shop_id' => $shopId,
                    ]
                );
                $request->merge(['unit_id' => $unit->id]);
            }
        } elseif ($request->input('unit_id') === '__new__') {
            $request->merge(['unit_id' => null]);
        }
    }

    public function store(Request $request)
    {
        if (!auth()->user()->canManageProducts()) {
            abort(403);
        }

        $this->resolveCategoryBrandUnit($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required', 'string', 'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('shop_id', auth()->user()->shop_id);
                })
            ],
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'branch_id' => 'nullable|exists:branches,id',
            'model' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'expiry_date' => [
                (auth()->user()->shop && auth()->user()->shop->business_type == 'Pharmacy / Health') ? 'required' : 'nullable',
                'date'
            ],
            'requires_imei' => 'nullable|boolean',
            'track_stock' => 'nullable|boolean',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'nullable|exists:products,id',
            'ingredients.*.quantity' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
        ]);

        if (!isset($validated['requires_imei'])) {
            $validated['requires_imei'] = 0;
        }
        $validated['track_stock'] = $request->has('track_stock') ? 1 : 0;
        
        if (!isset($validated['cost_price'])) $validated['cost_price'] = 0;
        if (!isset($validated['stock'])) $validated['stock'] = 0;

        if (isset($validated['selling_price']) && abs($validated['selling_price'] - round($validated['selling_price'])) < 0.1) {
            $validated['selling_price'] = round($validated['selling_price']);
        }
        if (isset($validated['cost_price']) && abs($validated['cost_price'] - round($validated['cost_price'])) < 0.1) {
            $validated['cost_price'] = round($validated['cost_price']);
        }
        if (!isset($validated['unit_id'])) {
            $defaultUnit = \App\Models\Unit::first();
            if (!$defaultUnit) {
                $defaultUnit = \App\Models\Unit::create([
                    'name' => 'Pieces',
                    'short_name' => 'Pcs',
                    'allow_decimal' => false,
                    'is_active' => true,
                    'shop_id' => auth()->user()->shop_id,
                ]);
            }
            $validated['unit_id'] = $defaultUnit->id;
        }

        // Extract branch_id and stock before creating product (they don't go directly into products table)
        $branchId    = $request->input('branch_id');
        $stockQty    = (int) $request->input('stock', 0);

        // If no branch_id from form, fall back to active branch session
        if (!$branchId) {
            $branchId = $this->getActiveBranchId();
            if (auth()->user()->shop) {
                if ($branchId && !\App\Models\Branch::where('id', $branchId)->where('shop_id', auth()->user()->shop_id)->exists()) {
                    $branchId = null;
                }
                if (!$branchId) {
                    $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
                }
            } else {
                $branchId = null;
            }
        }

        // Determine destination: Shop Floor vs. Warehouse (Only Administrators can route stock to a warehouse)
        $isAdmin = auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin');
        $stockDestination = $isAdmin ? $request->input('stock_destination', 'shop') : 'shop';
        $targetWarehouseId = null;
        if (str_starts_with($stockDestination, 'warehouse_')) {
            $targetWarehouseId = (int) str_replace('warehouse_', '', $stockDestination);
        } elseif (str_starts_with($stockDestination, 'wh_')) {
            $targetWarehouseId = (int) str_replace('wh_', '', $stockDestination);
        } elseif ($request->filled('warehouse_id') && ($stockDestination === 'warehouse' || $stockDestination === 'wh')) {
            $targetWarehouseId = (int) $request->input('warehouse_id');
        }

        $productData = collect($validated)->except(['ingredients', 'branch_id', 'image'])->toArray();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'product_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $productData['image_path'] = 'uploads/products/' . $imageName;
        }

        if ($targetWarehouseId && \App\Models\Warehouse::where('id', $targetWarehouseId)->exists()) {
            // Initial stock is deposited directly into the warehouse
            $productData['stock'] = 0;
            $product = Product::create($productData);

            if ($branchId) {
                \Illuminate\Support\Facades\DB::table('branch_product')->insert([
                    'branch_id'  => $branchId,
                    'product_id' => $product->id,
                    'quantity'   => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if ($stockQty > 0) {
                \App\Models\StockMovement::ensureColumnTypes();

                $ws = \App\Models\WarehouseStock::firstOrCreate(
                    ['warehouse_id' => $targetWarehouseId, 'product_id' => $product->id],
                    ['quantity' => 0]
                );
                $ws->increment('quantity', $stockQty);

                try {
                    \App\Models\StockMovement::create([
                        'shop_id'           => auth()->user()->shop_id,
                        'product_id'        => $product->id,
                        'moved_by'          => auth()->id(),
                        'from_type'         => 'supplier',
                        'from_warehouse_id' => null,
                        'to_type'           => 'warehouse',
                        'to_warehouse_id'   => $targetWarehouseId,
                        'quantity'          => $stockQty,
                        'notes'             => 'Usajili wa awali wa bidhaa ghalani (Initial stock added directly to warehouse)',
                        'movement_date'     => now()->toDateString(),
                    ]);
                } catch (\Throwable $smEx) {
                    try {
                        \App\Models\StockMovement::create([
                            'shop_id'           => auth()->user()->shop_id,
                            'product_id'        => $product->id,
                            'moved_by'          => auth()->id(),
                            'from_type'         => 'shop',
                            'from_warehouse_id' => null,
                            'to_type'           => 'warehouse',
                            'to_warehouse_id'   => $targetWarehouseId,
                            'quantity'          => $stockQty,
                            'notes'             => '[Supplier] Usajili wa awali wa bidhaa ghalani',
                            'movement_date'     => now()->toDateString(),
                        ]);
                    } catch (\Throwable $retryEx) {
                        \Illuminate\Support\Facades\Log::warning('Initial stock movement creation failed: ' . $retryEx->getMessage());
                    }
                }
            }
        } else {
            // Standard shop floor stock
            $productData['stock'] = $stockQty;
            $product = Product::create($productData);

            // Save initial stock to branch_product pivot table
            if ($branchId) {
                \Illuminate\Support\Facades\DB::table('branch_product')->insert([
                    'branch_id'  => $branchId,
                    'product_id' => $product->id,
                    'quantity'   => $stockQty,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $ingredient) {
                if(!empty($ingredient['id']) && !empty($ingredient['quantity']) && $ingredient['quantity'] > 0) {
                    \App\Models\ProductIngredient::create([
                        'product_id' => $product->id,
                        'ingredient_id' => $ingredient['id'],
                        'quantity' => $ingredient['quantity']
                    ]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        if (!auth()->user()->canManageProducts()) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $branches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->get();
        $allProducts = Product::where('id', '!=', $product->id)->get();
        $warehouses = \App\Models\Warehouse::where('is_active', true)->orderBy('name')->get();

        // products.stock is a legacy column — real stock for branch-tracked shops lives in
        // branch_product.quantity, keyed to whichever branch this product belongs to.
        $productBranch = $product->branches->first();
        $currentStock = $productBranch ? $productBranch->pivot->quantity : $product->stock;

        return view('products.edit', compact('product', 'categories', 'brands', 'units', 'branches', 'allProducts', 'currentStock', 'warehouses'));
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->canManageProducts()) {
            abort(403);
        }

        $this->resolveCategoryBrandUnit($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required', 'string', 'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('shop_id', auth()->user()->shop_id);
                })->ignore($product->id)
            ],
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'branch_id' => 'nullable|exists:branches,id',
            'model' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'expiry_date' => [
                (auth()->user()->shop && auth()->user()->shop->business_type == 'Pharmacy / Health') ? 'required' : 'nullable',
                'date'
            ],
            'requires_imei' => 'nullable|boolean',
            'track_stock' => 'nullable|boolean',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'nullable|exists:products,id',
            'ingredients.*.quantity' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
        ]);

        $validated['requires_imei'] = $request->has('requires_imei') ? 1 : 0;
        $validated['track_stock'] = $request->has('track_stock') ? 1 : 0;

        if (isset($validated['selling_price']) && abs($validated['selling_price'] - round($validated['selling_price'])) < 0.1) {
            $validated['selling_price'] = round($validated['selling_price']);
        }
        if (isset($validated['cost_price']) && abs($validated['cost_price'] - round($validated['cost_price'])) < 0.1) {
            $validated['cost_price'] = round($validated['cost_price']);
        }
        
        if (array_key_exists('unit_id', $validated) && $validated['unit_id'] === null) {
            unset($validated['unit_id']);
        }

        // Extract branch_id and stock before updating product
        $branchId = $request->input('branch_id');
        $stockQty = (int) $request->input('stock', 0);

        // If no branch_id from form, fall back to active branch session
        if (!$branchId) {
            $branchId = $this->getActiveBranchId();
            if (auth()->user()->shop) {
                if ($branchId && !\App\Models\Branch::where('id', $branchId)->where('shop_id', auth()->user()->shop_id)->exists()) {
                    $branchId = null;
                }
                if (!$branchId) {
                    $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
                }
            } else {
                $branchId = null;
            }
        }

        $productData = collect($validated)->except(['ingredients', 'branch_id', 'image'])->toArray();

        if ($request->hasFile('image')) {
            if ($product->image_path && file_exists(public_path($product->image_path))) {
                @unlink(public_path($product->image_path));
            }
            $image = $request->file('image');
            $imageName = 'product_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $productData['image_path'] = 'uploads/products/' . $imageName;
        }

        $product->update($productData);
        
        \App\Models\ProductIngredient::where('product_id', $product->id)->delete();
        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $ingredient) {
                if(!empty($ingredient['id']) && !empty($ingredient['quantity']) && $ingredient['quantity'] > 0) {
                    \App\Models\ProductIngredient::create([
                        'product_id'    => $product->id,
                        'ingredient_id' => $ingredient['id'],
                        'quantity'      => $ingredient['quantity']
                    ]);
                }
            }
        }
        
        // Update stock in branch_product pivot table
        if ($branchId) {
            \Illuminate\Support\Facades\DB::table('branch_product')->updateOrInsert(
                ['branch_id' => $branchId, 'product_id' => $product->id],
                ['quantity' => $stockQty, 'updated_at' => now()]
            );
        }
        
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->canManageProducts()) {
            abort(403);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        if (!auth()->user()->canManageProducts()) {
            abort(403);
        }

        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        Product::whereIn('id', $request->product_ids)->delete();

        return redirect()->route('products.index')->with('success', 'Selected products deleted successfully.');
    }
}

