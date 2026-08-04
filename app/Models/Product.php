<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'sku', 'barcode', 'category_id', 'brand_id', 'model', 'unit_id',
        'cost_price', 'selling_price', 'alert_quantity', 'stock', 'image_path', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
