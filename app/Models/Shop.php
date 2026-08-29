<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'logo_path', 'package', 'valid_until', 'is_active', 'phone', 'address', 'tin_number', 'receipt_message'];

    protected $casts = [
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
