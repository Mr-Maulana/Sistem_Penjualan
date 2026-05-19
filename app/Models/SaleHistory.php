<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleHistory extends Model
{
    protected $fillable = [
        'sale_id',
        'invoice_number',
        'date',
        'customer_id',
        'salesman_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }
}
