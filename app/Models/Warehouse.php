<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class Warehouse extends Model
{
    use HasTenant;

    protected $fillable = ['shop_id', 'name', 'location', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function stocks()
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'warehouse_stocks')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'from_warehouse_id')
                    ->orWhere('to_warehouse_id', $this->id);
    }

    public function getStockForProduct($productId)
    {
        $stock = $this->stocks()->where('product_id', $productId)->first();
        return $stock ? $stock->quantity : 0;
    }

    public function totalItems()
    {
        return $this->stocks()->sum('quantity');
    }
}
