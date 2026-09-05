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
        $search   = $request->query('search');
        $staffId  = $request->query('staff_id'); // admin filter by staff
        $branchId = $this->getActiveBranchId();
        $isAdmin  = auth()->user()->hasRole('Administrator');

        $sales = \App\Models\Sale::with(['customer', 'items.product', 'user'])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when(!$isAdmin, function($q) {
                // Cashier sees ONLY their own sales (including old sales with null user_id=none)
                $q->where('user_id', auth()->id());
            })
            ->when($isAdmin && $staffId, function($q) use ($staffId) {
                // Admin filters by a specific staff member
                $q->where('user_id', $staffId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q1) use ($search) {
                    $q1->where('reference_no', 'like', "%{$search}%")
                          ->orWhereHas('customer', function ($q2) use ($search) {
                              $q2->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                          });
                });
            })
            ->latest()
            ->paginate(15)
            ->appends(['search' => $search, 'staff_id' => $staffId]);

        // Staff list for admin dropdown
        $staffList = [];
        if ($isAdmin && auth()->user()->shop_id) {
            $staffList = \App\Models\User::where('shop_id', auth()->user()->shop_id)->get();
        }
            
        return view('sales.index', compact('sales', 'search', 'staffId', 'staffList', 'isAdmin'));
    }

    public function create()
    {
        $branchId = $this->getActiveBranchId();
        $hasBranches = false;
        
        if (auth()->user()->shop) {
            $hasBranches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->exists();
            
            // Validate that the branch actually exists for this shop
            if ($branchId && !\App\Models\Branch::where('id', $branchId)->where('shop_id', auth()->user()->shop_id)->exists()) {
                $branchId = null;
            }
            
            if (!$branchId && $hasBranches) {
                $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
            }
        }
        
        if ($hasBranches && $branchId) {
            $products = \App\Models\Product::whereHas('branches', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->get();
        } else {
            $products = \App\Models\Product::all();
        }
        
        $customers = \App\Models\Customer::all();
        $reference_no = 'SL-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
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
            if ($request->action === 'proforma') {
                $payment_status = 'proforma';
            } elseif ($request->paid_amount == 0) {
                $payment_status = 'unpaid';
            } elseif ($request->paid_amount < $total_amount) {
                $payment_status = 'partial';
            }

            $branchId = $this->getActiveBranchId();
            $hasBranches = false;
            
            if (auth()->user()->shop) {
                $hasBranches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->exists();
                
                // Validate that the branch actually exists for this shop
                if ($branchId && !\App\Models\Branch::where('id', $branchId)->where('shop_id', auth()->user()->shop_id)->exists()) {
                    $branchId = null;
                }
                
                if (!$branchId && $hasBranches) {
                    $branchId = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->first()->id ?? null;
                }
            }

            $sale = \App\Models\Sale::create([
                'customer_id' => $request->customer_id,
                'user_id'     => auth()->id(),
                'reference_no' => 'SL-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4)),
                'sale_date' => $request->sale_date,
                'total_amount' => $total_amount,
                'paid_amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => $payment_status,
                'notes' => $request->notes,
                'branch_id' => $branchId,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $product = \App\Models\Product::find($item['product_id']);

                if ($request->action !== 'proforma' && (!$product->category || !$product->category->is_service) && $product->track_stock) {
                    $ingredients = \App\Models\ProductIngredient::where('product_id', $product->id)->get();
                    
                    if ($ingredients->count() > 0) {
                        // Deduct ingredients
                        foreach ($ingredients as $ing) {
                            $totalIngQty = $ing->quantity * $item['quantity'];
                            $ingProduct = \App\Models\Product::find($ing->ingredient_id);
                            
                            if ($ingProduct) {
                                if ($hasBranches && $branchId) {
                                    $branchStock = \Illuminate\Support\Facades\DB::table('branch_product')
                                        ->where('branch_id', $branchId)
                                        ->where('product_id', $ingProduct->id)
                                        ->first();

                                    $currentStock = $branchStock ? $branchStock->quantity : 0;

                                    if ($currentStock < $totalIngQty) {
                                        throw new \Exception("Not enough stock for ingredient {$ingProduct->name} in your branch.");
                                    }
                                    
                                    \Illuminate\Support\Facades\DB::table('branch_product')
                                        ->where('branch_id', $branchId)
                                        ->where('product_id', $ingProduct->id)
                                        ->decrement('quantity', $totalIngQty);
                                } else {
                                    if ($ingProduct->stock < $totalIngQty) {
                                        throw new \Exception("Not enough stock for ingredient {$ingProduct->name}");
                                    }
                                    $ingProduct->decrement('stock', $totalIngQty);
                                }
                            }
                        }
                    } else {
                        // Deduct normal product
                        if ($hasBranches && $branchId) {
                            $branchStock = \Illuminate\Support\Facades\DB::table('branch_product')
                                ->where('branch_id', $branchId)
                                ->where('product_id', $item['product_id'])
                                ->first();

                            $currentStock = $branchStock ? $branchStock->quantity : 0;

                            if ($currentStock < $item['quantity']) {
                                throw new \Exception("Not enough stock for {$product->name} in your branch.");
                            }
                            
                            \Illuminate\Support\Facades\DB::table('branch_product')
                                ->where('branch_id', $branchId)
                                ->where('product_id', $item['product_id'])
                                ->decrement('quantity', $item['quantity']);
                        } else {
                            if ($product->stock < $item['quantity']) {
                                throw new \Exception("Not enough stock for {$product->name}");
                            }
                            $product->decrement('stock', $item['quantity']);
                        }
                    }
                }
                
                \App\Models\SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $product->cost_price,
                    'unit_price' => $item['price'],
                    'subtotal' => $subtotal,
                    'imei_serial_number' => $item['imei'] ?? null,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            // Notify Shop Admins
            $admins = \App\Models\User::where('shop_id', $sale->shop_id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Administrator');
                })->get();
                
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewSaleNotification($sale));

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
            $hasBranches = false;
            if (auth()->user()->shop) {
                $hasBranches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->exists();
            }
            
            $branchId = $sale->branch_id ?? auth()->user()->branch_id;
            
            foreach ($sale->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product && (!$product->category || !$product->category->is_service) && $product->track_stock) {
                    $ingredients = \App\Models\ProductIngredient::where('product_id', $product->id)->get();
                    if ($ingredients->count() > 0) {
                        foreach ($ingredients as $ing) {
                            $totalIngQty = $ing->quantity * $item->quantity;
                            if ($hasBranches && $branchId) {
                                \Illuminate\Support\Facades\DB::table('branch_product')
                                    ->updateOrInsert(
                                        ['branch_id' => $branchId, 'product_id' => $ing->ingredient_id],
                                        ['quantity' => \Illuminate\Support\Facades\DB::raw('quantity + ' . $totalIngQty)]
                                    );
                            } else {
                                \App\Models\Product::where('id', $ing->ingredient_id)->increment('stock', $totalIngQty);
                            }
                        }
                    } else {
                        if ($hasBranches && $branchId) {
                            \Illuminate\Support\Facades\DB::table('branch_product')
                                ->updateOrInsert(
                                    ['branch_id' => $branchId, 'product_id' => $item->product_id],
                                    ['quantity' => \Illuminate\Support\Facades\DB::raw('quantity + ' . $item->quantity)]
                                );
                        } else {
                            $product->increment('stock', $item->quantity);
                        }
                    }
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

    public function markAsPaid(\Illuminate\Http\Request $request, \App\Models\Sale $sale)
    {
        if ($sale->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        if ($sale->payment_status !== 'proforma') {
            return back()->with('error', 'This invoice is already processed.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $hasBranches = false;
            if (auth()->user()->shop) {
                $hasBranches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->exists();
            }
            $branchId = $sale->branch_id;

            foreach ($sale->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if (!$product) continue;
                
                if ($product->category && $product->category->is_service) continue;
                if (!$product->track_stock) continue;

                $ingredients = \App\Models\ProductIngredient::where('product_id', $product->id)->get();
                
                if ($ingredients->count() > 0) {
                    // Deduct ingredients
                    foreach ($ingredients as $ing) {
                        $totalIngQty = $ing->quantity * $item->quantity;
                        $ingProduct = \App\Models\Product::find($ing->ingredient_id);
                        
                        if ($ingProduct) {
                            if ($hasBranches && $branchId) {
                                $branchStock = \Illuminate\Support\Facades\DB::table('branch_product')
                                    ->where('branch_id', $branchId)
                                    ->where('product_id', $ingProduct->id)
                                    ->first();

                                $currentStock = $branchStock ? $branchStock->quantity : 0;
                                if ($currentStock < $totalIngQty) {
                                    throw new \Exception("Not enough stock for ingredient {$ingProduct->name} in your branch.");
                                }
                                
                                \Illuminate\Support\Facades\DB::table('branch_product')
                                    ->where('branch_id', $branchId)
                                    ->where('product_id', $ingProduct->id)
                                    ->decrement('quantity', $totalIngQty);
                            } else {
                                if ($ingProduct->stock < $totalIngQty) {
                                    throw new \Exception("Not enough stock for ingredient {$ingProduct->name}");
                                }
                                $ingProduct->decrement('stock', $totalIngQty);
                            }
                        }
                    }
                } else {
                    if ($hasBranches && $branchId) {
                        $branchStock = \Illuminate\Support\Facades\DB::table('branch_product')
                            ->where('branch_id', $branchId)
                            ->where('product_id', $item->product_id)
                            ->first();

                        $currentStock = $branchStock ? $branchStock->quantity : 0;
                        if ($currentStock < $item->quantity) {
                            throw new \Exception("Not enough stock for {$product->name} in your branch.");
                        }
                        
                        \Illuminate\Support\Facades\DB::table('branch_product')
                            ->where('branch_id', $branchId)
                            ->where('product_id', $item->product_id)
                            ->decrement('quantity', $item->quantity);
                    } else {
                        if ($product->stock < $item->quantity) {
                            throw new \Exception("Not enough stock for {$product->name}");
                        }
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }

            $sale->update([
                'payment_status' => 'paid',
                'paid_amount' => $sale->total_amount,
                'payment_method' => $request->payment_method ?? 'Cash',
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Invoice marked as paid and stock deducted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'No sales selected.');
        }

        $sales = \App\Models\Sale::whereIn('id', $ids)
            ->where('shop_id', auth()->user()->shop_id)
            ->with('items.product')
            ->get();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($sales as $sale) {
                $branchId = $sale->branch_id;
                $hasBranches = $branchId ? true : false;

                foreach ($sale->items as $item) {
                    $product = $item->product;
                    if (!$product || ($product->category && $product->category->is_service) || !$product->track_stock) continue;

                    $ingredients = \App\Models\ProductIngredient::where('product_id', $product->id)->get();
                    if ($ingredients->count() > 0) {
                        foreach ($ingredients as $ing) {
                            $qty = $ing->quantity * $item->quantity;
                            if ($hasBranches) {
                                \Illuminate\Support\Facades\DB::table('branch_product')
                                    ->where('branch_id', $branchId)->where('product_id', $ing->ingredient_id)
                                    ->increment('quantity', $qty);
                            } else {
                                \App\Models\Product::where('id', $ing->ingredient_id)->increment('stock', $qty);
                            }
                        }
                    } else {
                        if ($hasBranches) {
                            \Illuminate\Support\Facades\DB::table('branch_product')
                                ->where('branch_id', $branchId)->where('product_id', $item->product_id)
                                ->increment('quantity', $item->quantity);
                        } else {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }
                $sale->items()->delete();
                $sale->delete();
            }
            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', count($sales) . ' sale(s) deleted and stock restored.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function downloadPdf(string $id)
    {
        $sale = \App\Models\Sale::with(['customer', 'items.product'])->findOrFail($id);
        
        if ($sale->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $shop = auth()->user()->shop;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('sale', 'shop'));
        return $pdf->download('Receipt-' . $sale->reference_no . '.pdf');
    }
}
