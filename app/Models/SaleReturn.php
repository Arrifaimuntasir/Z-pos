<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class SaleReturn extends Model
{
    use HasTenant;

    protected $fillable = [
        'shop_id',
        'branch_id',
        'sale_id',
        'reference_no',
        'return_date',
        'total_refund',
        'reason',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }
}
