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

        return view('products.index', compact('products', 'search', 'activeBranchId', 'activeBranch'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        $branches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->get();
        $allProducts = Product::all();
        $activeBranchId = $this->getActiveBranchId();
        return view('products.create', compact('categories', 'brands', 'units', 'branches', 'allProducts', 'activeBranchId'));
    }

    public function store(Request $request)
    {
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
        ]);

        if (!isset($validated['requires_imei'])) {
            $validated['requires_imei'] = 0;
        }
        $validated['track_stock'] = $request->has('track_stock') ? 1 : 0;
        
        if (!isset($validated['cost_price'])) $validated['cost_price'] = 0;
        if (!isset($validated['stock'])) $validated['stock'] = 0;
        if (!isset($validated['unit_id'])) {
            $defaultUnit = \App\Models\Unit::first();
            if (!$defaultUnit) {
                $defaultUnit = \App\Models\Unit::create([
                    'name' => 'Pieces',
                    'short_name' => 'Pcs',
                    'allow_decimal' => false,
                    'is_active' => true
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

        $productData = collect($validated)->except(['ingredients', 'branch_id'])->toArray();
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
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        $branches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->get();
        $allProducts = Product::where('id', '!=', $product->id)->get();
        return view('products.edit', compact('product', 'categories', 'brands', 'units', 'branches', 'allProducts'));
    }

    public function update(Request $request, Product $product)
    {
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
        ]);

        $validated['requires_imei'] = $request->has('requires_imei') ? 1 : 0;
        $validated['track_stock'] = $request->has('track_stock') ? 1 : 0;
        
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

        $productData = collect($validated)->except(['ingredients', 'branch_id'])->toArray();
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
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        Product::whereIn('id', $request->product_ids)->delete();

        return redirect()->route('products.index')->with('success', 'Selected products deleted successfully.');
    }
}

