<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class Sale extends Model
{
    use HasTenant;
    protected $fillable = [
        'customer_id',
        'user_id',
        'reference_no',
        'sale_date',
        'total_amount',
        'paid_amount',
        'payment_method',
        'payment_status',
        'notes',
        'branch_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function returns()
    {
        return $this->hasMany(SaleReturn::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
