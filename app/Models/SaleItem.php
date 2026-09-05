<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class SaleItem extends Model
{
    use HasTenant;
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'returned_quantity',
        'unit_cost',
        'unit_price',
        'subtotal',
        'imei_serial_number',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function returns()
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    public function getNetQuantityAttribute()
    {
        return max(0, $this->quantity - $this->returned_quantity);
    }

    public function getNetTotalAttribute()
    {
        return $this->net_quantity * $this->unit_price;
    }
}
