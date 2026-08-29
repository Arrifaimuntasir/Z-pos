<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class Warranty extends Model
{
    use HasFactory, HasTenant;

    protected $fillable = [
        'shop_id',
        'sale_id',
        'warranty_number',
        'customer_name',
        'customer_phone',
        'region',
        'gender',
        'product_name',
        'price',
        'serial_number',
        'duration',
        'start_date',
        'end_date',
        'conditions',
        'design_theme',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
