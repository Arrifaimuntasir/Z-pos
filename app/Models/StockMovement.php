<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;
use Illuminate\Support\Facades\DB;

class StockMovement extends Model
{
    use HasTenant;

    protected $fillable = [
        'shop_id', 'product_id', 'moved_by',
        'from_type', 'from_warehouse_id', 'from_branch_id',
        'to_type', 'to_warehouse_id', 'to_branch_id',
        'quantity', 'notes', 'movement_date'
    ];

    protected $casts = [
        'movement_date' => 'date',
        'quantity' => 'decimal:2',
    ];

    protected static bool $schemaAdjusted = false;

    protected static function booted(): void
    {
        static::creating(function ($movement) {
            static::ensureColumnTypes($movement);
        });
    }

    public static function ensureColumnTypes($movement = null): void
    {
        if (static::$schemaAdjusted) {
            return;
        }

        // CRITICAL: MySQL DDL (such as ALTER TABLE) triggers an IMPLICIT COMMIT!
        // If an active transaction is in progress (DB::transactionLevel() > 0),
        // executing ALTER TABLE will destroy the active transaction and cause PDOException:
        // "There is no active transaction". Therefore, NEVER run DDL inside transactions!
        if (DB::transactionLevel() > 0) {
            if ($movement) {
                try {
                    $cols = DB::select("SHOW COLUMNS FROM `stock_movements` LIKE 'from_type'");
                    if (!empty($cols)) {
                        $type = strtolower($cols[0]->Type ?? '');
                        if (str_contains($type, 'enum') && !str_contains($type, "'" . $movement->from_type . "'")) {
                            $movement->notes = '[' . ucfirst($movement->from_type) . '] ' . ($movement->notes ?? '');
                            $movement->from_type = 'shop';
                        }
                    }
                } catch (\Throwable $t) {}
            }
            return;
        }

        try {
            $cols = DB::select("SHOW COLUMNS FROM `stock_movements` LIKE 'from_type'");
            if (!empty($cols)) {
                $type = strtolower($cols[0]->Type ?? '');
                if (str_contains($type, 'enum')) {
                    try {
                        DB::statement("ALTER TABLE `stock_movements` MODIFY COLUMN `from_type` VARCHAR(50) NOT NULL");
                        DB::statement("ALTER TABLE `stock_movements` MODIFY COLUMN `to_type` VARCHAR(50) NOT NULL");
                    } catch (\Throwable $alterErr) {
                        if ($movement && !str_contains($type, "'" . $movement->from_type . "'")) {
                            $movement->notes = '[' . ucfirst($movement->from_type) . '] ' . ($movement->notes ?? '');
                            $movement->from_type = 'shop';
                        }
                    }
                }
            }

            // Ensure branch columns exist
            $branchCol = DB::select("SHOW COLUMNS FROM `stock_movements` LIKE 'from_branch_id'");
            if (empty($branchCol)) {
                try {
                    DB::statement("ALTER TABLE `stock_movements` ADD COLUMN `from_branch_id` BIGINT UNSIGNED NULL AFTER `from_warehouse_id`");
                    DB::statement("ALTER TABLE `stock_movements` ADD COLUMN `to_branch_id` BIGINT UNSIGNED NULL AFTER `to_warehouse_id`");
                } catch (\Throwable $branchErr) {}
            }

            static::$schemaAdjusted = true;
        } catch (\Throwable $e) {
            // Table might not exist yet
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function movedBy()
    {
        return $this->belongsTo(User::class, 'moved_by');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function getFromLabelAttribute(): string
    {
        if ($this->from_type === 'warehouse' && $this->fromWarehouse) {
            return '🏭 ' . $this->fromWarehouse->name;
        }
        if ($this->from_branch_id && $this->fromBranch) {
            return '🏪 ' . $this->fromBranch->name;
        }
        if ($this->from_type === 'supplier') {
            return '🚚 ' . __('Supplier');
        }
        return '🏪 Duka (Shop Floor)';
    }

    public function getToLabelAttribute(): string
    {
        if ($this->to_type === 'warehouse' && $this->toWarehouse) {
            return '🏭 ' . $this->toWarehouse->name;
        }
        if ($this->to_branch_id && $this->toBranch) {
            return '🏪 ' . $this->toBranch->name;
        }
        return '🏪 Duka (Shop Floor)';
    }
}
