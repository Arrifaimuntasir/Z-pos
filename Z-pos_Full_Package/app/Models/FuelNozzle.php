<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class FuelNozzle extends Model
{
    use HasTenant, SoftDeletes;

    protected $fillable = ['branch_id', 'fuel_pump_id', 'fuel_tank_id', 'number', 'meter_reading', 'status'];

    protected $casts = [
        'meter_reading' => 'decimal:3',
    ];

    public function pump()
    {
        return $this->belongsTo(FuelPump::class, 'fuel_pump_id');
    }

    public function tank()
    {
        return $this->belongsTo(FuelTank::class, 'fuel_tank_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
