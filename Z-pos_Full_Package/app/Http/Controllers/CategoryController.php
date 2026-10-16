<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categories = Category::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })->paginate(15)->appends(['search' => $search]);
        
        return view('categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_service'] = $request->has('is_service');

        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(string $id)
    {
        // Not used for now
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_service'] = $request->has('is_service');

        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete category because it has products.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    /**
     * Remove multiple selected categories at once, skipping any that still have products.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'No categories selected.');
        }

        $categories = Category::whereIn('id', $ids)
            ->where('shop_id', auth()->user()->shop_id)
            ->withCount('products')
            ->get();

        $deletable = $categories->where('products_count', 0);
        $skipped = $categories->where('products_count', '>', 0);

        Category::whereIn('id', $deletable->pluck('id'))->delete();

        if ($skipped->isNotEmpty() && $deletable->isNotEmpty()) {
            return redirect()->route('categories.index')->with('success', $deletable->count() . ' categories deleted. ' . $skipped->count() . ' skipped because they still have products.');
        } elseif ($skipped->isNotEmpty()) {
            return redirect()->route('categories.index')->with('error', 'None deleted — all selected categories still have products.');
        }

        return redirect()->route('categories.index')->with('success', 'Selected categories deleted successfully.');
    }
}
