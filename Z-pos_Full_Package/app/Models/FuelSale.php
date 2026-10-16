<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class FuelSale extends Model
{
    use HasTenant;

    protected $fillable = [
        'branch_id', 'fuel_daily_opening_id', 'receipt_number', 'business_date', 'fuel_pump_id', 'fuel_nozzle_id',
        'fuel_tank_id', 'product_id', 'user_id', 'customer_id', 'sale_id', 'litres', 'price_per_litre',
        'gross_amount', 'discount_amount', 'net_amount', 'payment_method', 'notes',
    ];

    protected $casts = [
        'business_date' => 'date',
        'litres' => 'decimal:3',
        'price_per_litre' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    public function pump()
    {
        return $this->belongsTo(FuelPump::class, 'fuel_pump_id');
    }

    public function nozzle()
    {
        return $this->belongsTo(FuelNozzle::class, 'fuel_nozzle_id');
    }

    public function tank()
    {
        return $this->belongsTo(FuelTank::class, 'fuel_tank_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attendant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
