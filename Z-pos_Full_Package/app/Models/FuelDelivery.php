<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class FuelDelivery extends Model
{
    use HasTenant;

    protected $fillable = [
        'branch_id', 'fuel_tank_id', 'product_id', 'supplier_id', 'delivery_date', 'delivery_time',
        'invoice_number', 'delivery_note_number', 'driver', 'vehicle_number', 'quantity', 'unit_cost',
        'total_cost', 'received_by', 'approved_by', 'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function tank()
    {
        return $this->belongsTo(FuelTank::class, 'fuel_tank_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
