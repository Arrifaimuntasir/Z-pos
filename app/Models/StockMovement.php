<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class StockMovement extends Model
{
    use HasTenant;

    protected $fillable = [
        'shop_id', 'product_id', 'moved_by',
        'from_type', 'from_warehouse_id',
        'to_type', 'to_warehouse_id',
        'quantity', 'notes', 'movement_date'
    ];

    protected $casts = [
        'movement_date' => 'date',
        'quantity' => 'decimal:2',
    ];

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

    public function getFromLabelAttribute(): string
    {
        if ($this->from_type === 'warehouse' && $this->fromWarehouse) {
            return '🏭 ' . $this->fromWarehouse->name;
        }
        return 'Duka (Shop Floor)';
    }

    public function getToLabelAttribute(): string
    {
        if ($this->to_type === 'warehouse' && $this->toWarehouse) {
            return '🏭 ' . $this->toWarehouse->name;
        }
        return 'Duka (Shop Floor)';
    }
}
