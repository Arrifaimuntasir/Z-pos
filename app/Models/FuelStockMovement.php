<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class FuelStockMovement extends Model
{
    use HasTenant;

    public $timestamps = false;

    protected $fillable = ['branch_id', 'fuel_tank_id', 'product_id', 'type', 'reference', 'qty_in', 'qty_out', 'balance_after', 'user_id', 'notes', 'created_at'];

    protected $casts = [
        'qty_in' => 'decimal:2',
        'qty_out' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function tank()
    {
        return $this->belongsTo(FuelTank::class, 'fuel_tank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
