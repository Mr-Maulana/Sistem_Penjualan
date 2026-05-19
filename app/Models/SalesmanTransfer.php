<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesmanTransfer extends Model
{
    protected $fillable = [
        'salesman_id',
        'from_supervisor_id',
        'to_supervisor_id',
        'requested_by',
        'approved_by',
        'reason',
        'status',
    ];

    public function salesman()
    {
        return $this->belongsTo(Salesman::class, 'salesman_id');
    }

    public function fromSupervisor()
    {
        return $this->belongsTo(Salesman::class, 'from_supervisor_id');
    }

    public function toSupervisor()
    {
        return $this->belongsTo(Salesman::class, 'to_supervisor_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
