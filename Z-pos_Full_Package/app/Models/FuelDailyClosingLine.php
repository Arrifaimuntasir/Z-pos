<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelDailyClosingLine extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'fuel_daily_closing_id', 'line_type', 'fuel_tank_id', 'fuel_nozzle_id', 'label',
        'opening', 'delivered', 'sold', 'expected', 'physical', 'variance', 'status',
    ];

    protected $casts = [
        'opening' => 'decimal:3', 'delivered' => 'decimal:3', 'sold' => 'decimal:3',
        'expected' => 'decimal:3', 'physical' => 'decimal:3', 'variance' => 'decimal:3',
    ];
}
