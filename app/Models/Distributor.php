<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributor extends Model
{
    protected $fillable = ['code', 'name', 'city', 'phone', 'address', 'status'];
    
    protected $casts = [
        'status' => 'string',
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}