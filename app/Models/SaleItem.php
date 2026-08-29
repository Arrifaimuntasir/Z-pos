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
}
