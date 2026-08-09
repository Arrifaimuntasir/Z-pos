<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait HasTenant
{
    /**
     * Boot the trait to apply scope and set shop_id automatically.
     */
    protected static function bootHasTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->shop_id && empty($model->shop_id)) {
                $model->shop_id = Auth::user()->shop_id;
            }
        });
    }

    public function shop()
    {
        return $this->belongsTo(\App\Models\Shop::class);
    }
}
