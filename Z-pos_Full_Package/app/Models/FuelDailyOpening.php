<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class FuelDailyOpening extends Model
{
    use HasTenant;

    protected $fillable = ['branch_id', 'business_date', 'opening_cash', 'opened_by', 'opened_at', 'closed_at', 'closed_by'];

    protected $casts = [
        'business_date' => 'date',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_cash' => 'decimal:2',
    ];

    public function tanks()
    {
        return $this->hasMany(FuelDailyOpeningTank::class);
    }

    public function readings()
    {
        return $this->hasMany(FuelPumpReading::class);
    }

    public function sales()
    {
        return $this->hasMany(FuelSale::class);
    }

    public function closing()
    {
        return $this->hasOne(FuelDailyClosing::class);
    }

    public function isOpen(): bool
    {
        return $this->closed_at === null;
    }
}
