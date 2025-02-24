<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'min_cart_total',
        'active_from',
        'active_to'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeActive($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('active_from')
                ->orWhere('active_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('active_to')
                ->orWhere('active_to', '>=', $now);
        });
    }
}
