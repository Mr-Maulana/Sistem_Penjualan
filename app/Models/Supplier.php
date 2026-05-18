<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'company_name',
        'npwp',
        'product_code',
        'product_type',
        'city',
        'phone',
        'address',
        'status',
    ];
    
    protected $casts = [
        'status' => 'string',
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_code', 'code');
    }
}