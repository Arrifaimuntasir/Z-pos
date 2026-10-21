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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Sale::class,
            'id',       // Foreign key on sales table (sales.id)
            'id',       // Foreign key on users table (users.id)
            'sale_id',  // Local key on sale_returns table (sale_returns.sale_id)
            'user_id'   // Local key on sales table (sales.user_id)
        );
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }
}
