<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    protected $fillable = [
        'code', 'name', 'nik', 'npwp', 'email', 'photo', 'address', 
        'area', 'city', 'phone', 'target', 'status', 'level', 'supervisor_id'
    ];
    
    protected $casts = [
        'target' => 'decimal:2',
        'status' => 'string',
        'level' => 'string',
    ];
    
    public function supervisor()
    {
        return $this->belongsTo(Salesman::class, 'supervisor_id');
    }

    public function areaData()
    {
        return $this->belongsTo(Area::class, 'area', 'code');
    }

    public function getAreaDisplayAttribute()
    {
        if (empty($this->area) && empty($this->city)) {
            return 'Belum Ditentukan (Mutasi)';
        }

        if ($this->level === 'manager') {
            $provCode = \App\Models\Area::getProvinceCodeByName($this->area);
            return $this->area . ' (' . $provCode . ')';
        } elseif ($this->level === 'supervisor') {
            $area = \App\Models\Area::where('city', $this->city)->first();
            $province = $area ? $area->province : '';
            return $province ? ($province . ' - ' . $this->city) : $this->city;
        } else {
            $area = $this->areaData;
            if (!$area) {
                return ($this->city ? $this->city . ' - ' : '') . $this->area;
            }
            return $area->province . ' - ' . $area->city . ' - ' . $area->name;
        }
    }

    public function subordinates()
    {
        return $this->hasMany(Salesman::class, 'supervisor_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}