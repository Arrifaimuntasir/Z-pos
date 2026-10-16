<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\FuelStockMovement;
use App\Models\FuelTank;
use Illuminate\Support\Facades\DB;

class PetroleumStock
{
    public static function stationBranchId(?int $activeBranchId): int
    {
        if ($activeBranchId) {
            return $activeBranchId;
        }

        $shopId = auth()->user()->shop_id;
        $branch = Branch::where('shop_id', $shopId)->orderBy('id')->first();

        return $branch?->id ?? Branch::create([
            'shop_id' => $shopId,
            'name' => 'Main Branch',
            'is_active' => true,
        ])->id;
    }

    public static function record(FuelTank $tank, string $type, float $qtyIn, float $qtyOut, ?string $reference = null, ?string $notes = null): FuelStockMovement
    {
        $balance = (float) $tank->current_stock + $qtyIn - $qtyOut;
        $tank->update(['current_stock' => $balance]);

        $movement = FuelStockMovement::create([
            'branch_id' => $tank->branch_id,
            'fuel_tank_id' => $tank->id,
            'product_id' => $tank->product_id,
            'type' => $type,
            'reference' => $reference,
            'qty_in' => $qtyIn,
            'qty_out' => $qtyOut,
            'balance_after' => $balance,
            'user_id' => auth()->id(),
            'notes' => $notes,
            'created_at' => now(),
        ]);

        self::syncProductBranchStock($tank->branch_id, $tank->product_id);

        return $movement;
    }

    public static function syncProductBranchStock(int $branchId, int $productId): void
    {
        $total = (float) FuelTank::where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->sum('current_stock');

        DB::table('branch_product')->updateOrInsert(
            ['branch_id' => $branchId, 'product_id' => $productId],
            ['quantity' => $total, 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
