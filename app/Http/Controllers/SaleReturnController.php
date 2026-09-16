<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $allowedTypes = ['Retail / General', 'Electronics / IT'];
            if (auth()->check() && auth()->user()->shop && !in_array(auth()->user()->shop->business_type, $allowedTypes)) {
                return redirect()->route('dashboard')->with('error', 'Returns feature is not available for your business type.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $branchId = $this->getActiveBranchId();
        $isAdmin = auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin');

        $returns = SaleReturn::with(['sale.customer'])
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when(!$isAdmin, function($q) {
                $q->whereHas('sale', function($subQ) {
                    $subQ->where('user_id', auth()->id());
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q1) use ($search) {
                    $q1->where('reference_no', 'like', "%{$search}%")
                          ->orWhereHas('sale', function ($q2) use ($search) {
                              $q2->where('reference_no', 'like', "%{$search}%");
                          });
                });
            })
            ->latest()
            ->paginate(15)
            ->appends(['search' => $search]);
            
        return view('returns.index', compact('returns', 'search'));
    }

    public function create(Sale $sale)
    {
        if ($sale->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        if ($sale->payment_status === 'proforma') {
            return redirect()->route('sales.show', $sale->id)->with('error', 'Cannot process returns for Proforma Invoices because they do not deduct stock.');
        }

        $sale->load('items.product');

        // Check if there are any items left to return
        $canReturn = false;
        foreach ($sale->items as $item) {
            if ($item->quantity > $item->returned_quantity) {
                $canReturn = true;
                break;
            }
        }

        if (!$canReturn) {
            return redirect()->route('sales.show', $sale->id)->with('error', 'All items in this invoice have already been returned.');
        }

        return view('returns.create', compact('sale'));
    }

    public function store(Request $request, Sale $sale)
    {
        if ($sale->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        if ($sale->payment_status === 'proforma') {
            return redirect()->route('sales.show', $sale->id)->with('error', 'Cannot process returns for Proforma Invoices because they do not deduct stock.');
        }

        $request->validate([
            'return_date' => 'required|date',
            'reason' => 'nullable|string',
            'items' => 'required|array',
            'items.*.sale_item_id' => 'required|exists:sale_items,id',
            'items.*.return_quantity' => 'required|integer|min:0',
            'items.*.condition' => 'nullable|string|in:good,defective',
        ]);

        $sale->load('items');

        DB::beginTransaction();

        try {
            $totalRefund = 0;
            $itemsToReturn = [];

            // Validation step
            foreach ($request->items as $reqItem) {
                if ($reqItem['return_quantity'] > 0) {
                    $saleItem = $sale->items->where('id', $reqItem['sale_item_id'])->first();
                    if (!$saleItem) {
                        throw new \Exception("Invalid sale item.");
                    }

                    $availableToReturn = $saleItem->quantity - $saleItem->returned_quantity;
                    if ($reqItem['return_quantity'] > $availableToReturn) {
                        throw new \Exception("Cannot return more than purchased for product ID {$saleItem->product_id}. Available: {$availableToReturn}");
                    }

                    $refundAmount = $reqItem['return_quantity'] * $saleItem->unit_price;
                    $totalRefund += $refundAmount;

                    $itemsToReturn[] = [
                        'sale_item' => $saleItem,
                        'return_quantity' => $reqItem['return_quantity'],
                        'refund_amount' => $refundAmount,
                        'condition' => $reqItem['condition'] ?? 'good',
                    ];
                }
            }

            if (empty($itemsToReturn)) {
                throw new \Exception("No items selected for return.");
            }

            $branchId = $sale->branch_id ?? auth()->user()->branch_id;
            $hasBranches = false;
            if (auth()->user()->shop) {
                $hasBranches = \App\Models\Branch::where('shop_id', auth()->user()->shop_id)->exists();
            }

            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,
                'reference_no' => 'RET-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'return_date' => $request->return_date,
                'total_refund' => $totalRefund,
                'reason' => $request->reason,
                'branch_id' => $branchId,
                'shop_id' => auth()->user()->shop_id,
            ]);

            foreach ($itemsToReturn as $rtn) {
                $saleItem = $rtn['sale_item'];
                $qty = $rtn['return_quantity'];
                $condition = $rtn['condition'];

                // 1. Create Return Item Record
                SaleReturnItem::create([
                    'sale_return_id' => $saleReturn->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id' => $saleItem->product_id,
                    'quantity' => $qty,
                    'refund_amount' => $rtn['refund_amount'],
                    'condition' => $condition,
                ]);

                // 2. Update sale_item returned_quantity
                $saleItem->increment('returned_quantity', $qty);

                // 3. Restore Stock (ONLY if condition is 'good')
                if ($condition === 'good') {
                    $product = Product::find($saleItem->product_id);
                    if ($product && (!$product->category || !$product->category->is_service) && $product->track_stock) {
                        $ingredients = \App\Models\ProductIngredient::where('product_id', $product->id)->get();
                    if ($ingredients->count() > 0) {
                        foreach ($ingredients as $ing) {
                            $totalIngQty = $ing->quantity * $qty;
                            if ($hasBranches && $branchId) {
                            $bp = DB::table('branch_product')
                                ->where('branch_id', $branchId)
                                ->where('product_id', $ing->ingredient_id)
                                ->first();
                            if ($bp) {
                                DB::table('branch_product')
                                    ->where('id', $bp->id)
                                    ->increment('quantity', $totalIngQty);
                            } else {
                                DB::table('branch_product')->insert([
                                    'branch_id' => $branchId,
                                    'product_id' => $ing->ingredient_id,
                                    'quantity' => $totalIngQty
                                ]);
                            }
                        } else {
                            Product::where('id', $ing->ingredient_id)->increment('stock', $totalIngQty);
                        }
                    }
                } else {
                    if ($hasBranches && $branchId) {
                        $bp = DB::table('branch_product')
                            ->where('branch_id', $branchId)
                            ->where('product_id', $saleItem->product_id)
                            ->first();
                        if ($bp) {
                            DB::table('branch_product')
                                ->where('id', $bp->id)
                                ->increment('quantity', $qty);
                        } else {
                            DB::table('branch_product')->insert([
                                'branch_id' => $branchId,
                                'product_id' => $saleItem->product_id,
                                'quantity' => $qty
                            ]);
                        }
                    } else {
                        $product->increment('stock', $qty);
                    }
                    }
                    }
                }
            }

            // --- Send Notification ---
            $msgParts = [];
            $defectiveItems = [];
            $goodItems = [];
            
            foreach ($itemsToReturn as $rtn) {
                $saleItem = $rtn['sale_item'];
                $productName = $saleItem->product->name ?? 'Bidhaa';
                $qty = $rtn['return_quantity'];
                
                if ($rtn['condition'] === 'defective') {
                    $defectiveItems[] = "{$qty}x {$productName}";
                } else {
                    $goodItems[] = "{$qty}x {$productName}";
                }
            }
            
            if (count($goodItems) > 0) {
                $msgParts[] = "Nzima (zimerudishwa stock): " . implode(", ", $goodItems);
            }
            if (count($defectiveItems) > 0) {
                $msgParts[] = "Mbovu (hazijarudishwa stock): " . implode(", ", $defectiveItems);
            }
            
            $notificationMessage = "Return mpya! " . implode(" | ", $msgParts);
            
            $admins = \App\Models\User::where('shop_id', $saleReturn->shop_id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Administrator');
                })->get();
                
            if ($admins->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\SaleReturnNotification($saleReturn, $notificationMessage));
            }
            // --- End Notification ---

            // Deduct from Sale total_amount & paid_amount or just leave it for reporting? 
            // In standard POS, Sale amount remains unchanged, but net sales = Sale - Returns.
            // We will let reports handle net sales, so original invoice remains as is, but marked partially returned.
            
            DB::commit();
            return redirect()->route('sales.show', $sale->id)->with('success', 'Return processed successfully! Stock has been restored.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing return: ' . $e->getMessage())->withInput();
        }
    }

    public function downloadPdf($id)
    {
        $return = SaleReturn::with(['sale.customer', 'items.product', 'items.saleItem'])->findOrFail($id);
        
        if ($return->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $shop = auth()->user()->shop;
        
        $pdf = Pdf::loadView('pdf.return_invoice', compact('return', 'shop'));
        
        return $pdf->download('Return_Invoice_' . $return->reference_no . '.pdf');
    }

    public function defectiveItems(Request $request)
    {
        try {
            $search   = $request->query('search');
            $branchId = $this->getActiveBranchId();
            $shopId   = auth()->user()->shop_id;

            $query = SaleReturnItem::with(['product', 'saleReturn', 'saleItem'])
                ->whereHas('saleReturn', function ($q) use ($shopId, $branchId) {
                    $q->where('shop_id', $shopId);
                    if ($branchId) {
                        $q->where('branch_id', $branchId);
                    }
                })
                ->where('condition', 'defective')
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($q1) use ($search) {
                        $q1->whereHas('product', function ($p) use ($search) {
                            $p->where('name', 'like', "%{$search}%");
                        })->orWhereHas('saleReturn', function ($r) use ($search) {
                            $r->where('reference_no', 'like', "%{$search}%");
                        });
                    });
                })
                ->latest()
                ->paginate(20)
                ->appends(['search' => $search]);

            $totalDefective  = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->where('shop_id', $shopId))
                ->where('condition', 'defective')->sum('quantity');
                
            $totalLostValue  = SaleReturnItem::with('saleItem')
                ->whereHas('saleReturn', fn($q) => $q->where('shop_id', $shopId))
                ->where('condition', 'defective')->get()
                ->sum(fn($i) => $i->saleItem ? $i->quantity * $i->saleItem->unit_cost : 0);

            return view('returns.defective', compact('query', 'search', 'totalDefective', 'totalLostValue'))->render();
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Update whether a defective returned item has been repaired.
     */
    public function updateRepairStatus(Request $request, SaleReturnItem $item)
    {
        if (!$item->saleReturn || $item->saleReturn->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $request->validate([
            'repair_status' => 'required|string|in:not_repaired,repaired',
        ]);

        $oldStatus = $item->repair_status;
        $newStatus = $request->repair_status;

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Repair status updated.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // A defective item only re-enters sellable stock once it's repaired.
            // Reversing the status (repaired -> not_repaired) pulls it back out again.
            if ($item->condition === 'defective' && $item->product) {
                $branchId = $item->saleReturn->branch_id;

                if ($newStatus === 'repaired') {
                    $this->adjustStock($branchId, $item->product, $item->quantity);
                } elseif ($oldStatus === 'repaired') {
                    $this->adjustStock($branchId, $item->product, -$item->quantity);
                }
            }

            $item->update(['repair_status' => $newStatus]);

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Repair status updated.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error updating repair status: ' . $e->getMessage());
        }
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
     * Remove a defective item record from the tracking list.
     */
    public function destroyDefectiveItem(SaleReturnItem $item)
    {
        if (!$item->saleReturn || $item->saleReturn->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Defective item record deleted.');
    }
}


