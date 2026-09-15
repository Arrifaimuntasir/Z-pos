<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class DefectiveStock extends Model
{
    use HasTenant;

    protected $fillable = ['branch_id', 'product_id', 'user_id', 'quantity', 'reason'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
