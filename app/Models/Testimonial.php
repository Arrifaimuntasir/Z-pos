<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'user_id', 'shop_id', 'name', 'position', 'avatar_initials', 'avatar_color',
        'quote', 'rating', 'is_active', 'sort_order'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    protected $casts = [
        'is_active' => 'boolean',
        'rating'    => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
