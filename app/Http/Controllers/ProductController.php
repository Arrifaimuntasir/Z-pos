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
        $products = Product::with(['category', 'brand', 'unit'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('brand', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(15)
            ->appends(['search' => $search]);
            
        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        return view('products.create', compact('categories', 'brands', 'units'));
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
            'model' => 'nullable|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'requires_imei' => 'nullable|boolean',
        ]);

        if (!isset($validated['requires_imei'])) {
            $validated['requires_imei'] = 0;
        }

        $product = Product::create($validated);

        // Add initial stock to current user's active branch
        $branchId = auth()->user()->branch_id ?: session('active_branch_id');
        if (auth()->user()->shop) {
            // Validate that the branch actually exists for this shop
            if ($branchId && !\App\Models\Branch::where('id', $branchId)->where('shop_id', auth()->user()->shop_id)->exists()) {
                $branchId = null;
            }
            if (!$branchId) {
                $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
            }
        } else {
            $branchId = null;
        }

        if ($branchId && $request->stock > 0) {
            \Illuminate\Support\Facades\DB::table('branch_product')->insert([
                'branch_id' => $branchId,
                'product_id' => $product->id,
                'quantity' => $request->stock
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        return view('products.edit', compact('product', 'categories', 'brands', 'units'));
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
            'model' => 'nullable|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'requires_imei' => 'nullable|boolean',
        ]);

        $validated['requires_imei'] = $request->has('requires_imei') ? 1 : 0;

        $product->update($validated);
        
        // Update stock in current branch
        $branchId = auth()->user()->branch_id ?: session('active_branch_id');
        if (auth()->user()->shop) {
            // Validate that the branch actually exists for this shop
            if ($branchId && !\App\Models\Branch::where('id', $branchId)->where('shop_id', auth()->user()->shop_id)->exists()) {
                $branchId = null;
            }
            if (!$branchId) {
                $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
            }
        } else {
            $branchId = null;
        }
        
        if ($branchId) {
            \Illuminate\Support\Facades\DB::table('branch_product')->updateOrInsert(
                ['branch_id' => $branchId, 'product_id' => $product->id],
                ['quantity' => $request->stock]
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

