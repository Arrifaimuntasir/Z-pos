<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class FuelDailyClosing extends Model
{
    use HasTenant;

    protected $fillable = [
        'branch_id', 'fuel_daily_opening_id', 'business_date', 'expected_cash', 'physical_cash',
        'cash_variance', 'cash_status', 'closed_by', 'closed_at', 'notes',
    ];

    protected $casts = [
        'business_date' => 'date',
        'closed_at' => 'datetime',
        'expected_cash' => 'decimal:2',
        'physical_cash' => 'decimal:2',
        'cash_variance' => 'decimal:2',
    ];

    public function lines()
    {
        return $this->hasMany(FuelDailyClosingLine::class);
    }

    public function opening()
    {
        return $this->belongsTo(FuelDailyOpening::class, 'fuel_daily_opening_id');
    }
}
