<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'price', 'discount', 'bonus', 'subtotal'];
    
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'bonus' => 'integer',
        'subtotal' => 'decimal:2',
    ];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}