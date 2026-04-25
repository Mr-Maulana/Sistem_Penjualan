<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'category_id', 'distributor_id', 'price', 'stock'];
    
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }
    
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}