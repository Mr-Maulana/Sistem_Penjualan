<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $fillable = ['code', 'date', 'type', 'description', 'amount', 'balance', 'reference_type', 'reference_id'];
    
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];
}