<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    protected $fillable = ['code', 'name', 'area', 'phone', 'target', 'status'];
    
    protected $casts = [
        'target' => 'decimal:2',
        'status' => 'string',
    ];
    
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}