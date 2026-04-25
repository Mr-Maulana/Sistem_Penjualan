<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['code', 'name', 'address', 'phone', 'salesman_id', 'status'];
    
    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }
    
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}