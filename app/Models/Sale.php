<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'date',
        'customer_id',
        'salesman_id',
        'payment_term',
        'down_payment',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
        'notes',
        'note',
    ];
    
    protected $casts = [
        'date' => 'date',
        'down_payment' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }
    
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}