<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['invoice_number', 'date', 'customer_id', 'salesman_id', 'subtotal', 'discount', 'total', 'status', 'notes'];
    
    protected $casts = [
        'date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
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