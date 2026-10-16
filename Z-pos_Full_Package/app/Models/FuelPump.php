<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class FuelPump extends Model
{
    use HasTenant, SoftDeletes;

    protected $fillable = ['branch_id', 'number', 'name', 'location', 'status'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function nozzles()
    {
        return $this->hasMany(FuelNozzle::class)->orderBy('number');
    }
}
