<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WarrantyController extends Controller
{
    public function index(Request $request)
    {
        $shopId        = auth()->user()->shop_id;
        $isAdmin       = auth()->user()->hasRole('Administrator');
        $search        = $request->search;
        $branchId      = $this->getActiveBranchId();
        $hasBranchCol  = \Illuminate\Support\Facades\Schema::hasColumn('warranties', 'branch_id');

        $query = Warranty::where('shop_id', $shopId)
            ->when($isAdmin && $branchId && $hasBranchCol, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when(!$isAdmin, function ($q) {
                $q->where('created_by', auth()->id());
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('customer_name', 'like', "%{$search}%")
                       ->orWhere('warranty_number', 'like', "%{$search}%")
                       ->orWhere('product_name', 'like', "%{$search}%");
                });
            });

        $warranties = $query->orderBy('created_at', 'desc')->paginate(15);
        $warranties->appends(['search' => $search]);

        // Metrics (scoped same as list)
        $metricsBase = Warranty::where('shop_id', $shopId)
            ->when($isAdmin && $branchId && $hasBranchCol, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when(!$isAdmin, function ($q) {
                $q->where('created_by', auth()->id());
            });
        $totalWarranties   = (clone $metricsBase)->count();
        $activeWarranties  = (clone $metricsBase)->where('end_date', '>=', now()->startOfDay())->count();
        $expiredWarranties = (clone $metricsBase)->where('end_date', '<', now()->startOfDay())->count();

        return view('warranties.index', compact('warranties', 'totalWarranties', 'activeWarranties', 'expiredWarranties'));
    }


    public function create(Request $request)
    {
        $sale = null;
        if ($request->has('sale_id')) {
            $sale = Sale::where('shop_id', auth()->user()->shop_id)->find($request->sale_id);
        }
        
        $sales = Sale::where('shop_id', auth()->user()->shop_id)->orderBy('created_at', 'desc')->take(50)->get();
        $products = \App\Models\Product::where('shop_id', auth()->user()->shop_id)->orderBy('name')->get();
        
        return view('warranties.create', compact('sale', 'sales', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.serial' => 'nullable|string|max:255',
            'products.*.price' => 'nullable|numeric|min:0',
            'duration' => 'required|string|max:255',
            'end_date' => 'required|date',
            'design_theme' => 'nullable|integer|min:1|max:10',
            'conditions' => 'nullable|string',
            'sale_id' => 'nullable|exists:sales,id',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('shop_logo')) {
            $path = $request->file('shop_logo')->store('logos', 'public');
            $shop = auth()->user()->shop;
            $shop->logo_path = 'storage/' . $path;
            $shop->save();
        }

        $branchId = $this->getActiveBranchId();
        // If branch not found via session, try to get from sale
        if (!$branchId && $request->sale_id) {
            $sale = Sale::find($request->sale_id);
            $branchId = $sale?->branch_id;
        }

        foreach ($request->products as $product) {
            $warrantyNumber = 'WAR-' . strtoupper(Str::random(8));

            Warranty::create([
                'shop_id'        => auth()->user()->shop_id,
                'branch_id'      => $branchId,
                'sale_id'        => $request->sale_id,
                'warranty_number'=> $warrantyNumber,
                'customer_name'  => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'region'         => $request->region,
                'gender'         => $request->gender,
                'product_name'   => $product['name'],
                'price'          => $product['price'] ?? null,
                'serial_number'  => $product['serial'] ?? null,
                'duration'       => $request->duration,
                'start_date'     => $request->start_date ?? date('Y-m-d'),
                'end_date'       => $request->end_date,
                'design_theme'   => $request->design_theme ?? 1,
                'conditions'     => $request->conditions ? strip_tags($request->conditions, '<p><br><b><i><u><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><span>') : null,
                'created_by'     => auth()->id(),
            ]);
        }

        return redirect()->route('warranties.index')->with('success', 'Warrant(s) created successfully.');
    }

    public function edit(Warranty $warranty)
    {
        if ($warranty->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $products = \App\Models\Product::where('shop_id', auth()->user()->shop_id)->orderBy('name')->get();
        return view('warranties.edit', compact('warranty', 'products'));
    }

    public function update(Request $request, Warranty $warranty)
    {
        if ($warranty->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'product_name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'serial_number' => 'nullable|string|max:255',
            'duration' => 'required|string|max:255',
            'end_date' => 'required|date',
            'design_theme' => 'nullable|integer|min:1|max:10',
            'conditions' => 'nullable|string',
        ]);

        $warranty->update([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'region' => $request->region,
            'gender' => $request->gender,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'serial_number' => $request->serial_number,
            'duration' => $request->duration,
            'end_date' => $request->end_date,
            'design_theme' => $request->design_theme ?? 1,
            'conditions' => $request->conditions ? strip_tags($request->conditions, '<p><br><b><i><u><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><span>') : null,
        ]);

        return redirect()->route('warranties.index')->with('success', 'Warranty updated successfully.');
    }

    public function show(Warranty $warranty)
    {
        if ($warranty->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }
        
        return view('warranties.show', compact('warranty'));
    }

    public function downloadPdf(Warranty $warranty)
    {
        if ($warranty->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }
        
        $shop = auth()->user()->shop ?? null;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.warranty', compact('warranty', 'shop'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Warranty_' . $warranty->warranty_number . '.pdf');
    }

    public function destroy(Warranty $warranty)
    {
        if ($warranty->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }
        
        $warranty->delete();
        return redirect()->route('warranties.index')->with('success', 'Warranty deleted successfully.');
    }
}
