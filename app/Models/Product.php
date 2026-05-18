<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code', 'name', 'category_id', 'supplier_code', 'price', 'stock'];
    
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }
    
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}