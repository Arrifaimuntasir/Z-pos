<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sales = \App\Models\Sale::with('customer')
            ->when($search, function ($query) use ($search) {
                $query->where('reference_no', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(15)
            ->appends(['search' => $search]);
            
        return view('sales.index', compact('sales', 'search'));
    }

    public function create()
    {
        $products = \App\Models\Product::where('stock', '>', 0)->get();
        $customers = \App\Models\Customer::all();
        $reference_no = 'SL-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        return view('sales.create', compact('products', 'customers', 'reference_no'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $total_amount = 0;
            
            // Calculate total first
            foreach ($request->items as $item) {
                $total_amount += ($item['quantity'] * $item['price']);
            }
            
            $payment_status = 'paid';
            if ($request->paid_amount == 0) {
                $payment_status = 'unpaid';
            } elseif ($request->paid_amount < $total_amount) {
                $payment_status = 'partial';
            }

            $sale = \App\Models\Sale::create([
                'customer_id' => $request->customer_id,
                'reference_no' => 'SL-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4)),
                'sale_date' => $request->sale_date,
                'total_amount' => $total_amount,
                'paid_amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => $payment_status,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $product = \App\Models\Product::find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }
                
                \App\Models\SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $product->cost_price,
                    'unit_price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                // Deduct stock
                $product->decrement('stock', $item['quantity']);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('sales.show', $sale->id)->with('success', 'Sale completed successfully!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $sale = \App\Models\Sale::with(['customer', 'items.product'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    public function edit(string $id)
    {
        // Edit logic usually not allowed for completed sales without voiding
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $sale = \App\Models\Sale::with('items')->findOrFail($id);

            // Restore stock for each item
            foreach ($sale->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Delete sale items and the sale
            \App\Models\SaleItem::where('sale_id', $sale->id)->delete();
            $sale->delete();

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Sale record deleted successfully. Stock has been restored.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
    }
}
