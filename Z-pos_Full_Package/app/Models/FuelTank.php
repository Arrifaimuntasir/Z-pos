<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class FuelTank extends Model
{
    use HasTenant, SoftDeletes;

    protected $fillable = ['branch_id', 'product_id', 'name', 'capacity', 'current_stock', 'min_level', 'max_level', 'status'];

    protected $casts = [
        'capacity' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'min_level' => 'decimal:2',
        'max_level' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function nozzles()
    {
        return $this->hasMany(FuelNozzle::class);
    }

    public function movements()
    {
        return $this->hasMany(FuelStockMovement::class)->latest('created_at');
    }
}
