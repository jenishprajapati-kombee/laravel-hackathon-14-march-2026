<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'description',
        'price',
        'stock',
        'sku',
        'image',
        'status',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
