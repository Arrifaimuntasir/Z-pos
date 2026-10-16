<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelDailyOpeningTank extends Model
{
    public $timestamps = false;

    protected $fillable = ['fuel_daily_opening_id', 'fuel_tank_id', 'litres'];

    protected $casts = ['litres' => 'decimal:3'];

    public function tank()
    {
        return $this->belongsTo(FuelTank::class, 'fuel_tank_id');
    }
}
