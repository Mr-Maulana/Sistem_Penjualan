<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'product_id',
        'customer_group',
        'price_large',
        'price_small',
        'discount',
        'tax',
        'effective_date',
    ];

    protected $casts = [
        'price_large' => 'decimal:2',
        'price_small' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'effective_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

