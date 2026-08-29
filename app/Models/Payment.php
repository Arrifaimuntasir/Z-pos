<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class Payment extends Model
{
    use HasFactory, HasTenant;

    protected $fillable = ['shop_id', 'amount', 'receipt_path', 'status'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
