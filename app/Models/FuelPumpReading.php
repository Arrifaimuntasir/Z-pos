<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class FuelPumpReading extends Model
{
    use HasTenant;

    public $timestamps = false;

    protected $fillable = ['branch_id', 'fuel_daily_opening_id', 'fuel_nozzle_id', 'reading_type', 'meter', 'recorded_by', 'recorded_at'];

    protected $casts = ['meter' => 'decimal:3', 'recorded_at' => 'datetime'];

    public function nozzle()
    {
        return $this->belongsTo(FuelNozzle::class, 'fuel_nozzle_id');
    }
}
