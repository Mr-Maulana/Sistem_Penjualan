<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['code', 'name', 'city', 'province'];

    public function salesmen()
    {
        return $this->hasMany(Salesman::class, 'area', 'code');
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'area_code', 'code');
    }

    // Accessors for clean codes
    public function getProvinceCodeAttribute()
    {
        $parts = explode('-', $this->code);
        if (count($parts) === 3) {
            return strtoupper($parts[0]);
        }
        $cleaned = preg_replace('/[^A-Za-z]/', '', $this->province);
        return strtoupper(substr($cleaned, 0, 3));
    }

    public function getCityCodeAttribute()
    {
        $parts = explode('-', $this->code);
        if (count($parts) === 3) {
            return strtoupper($parts[1]);
        }
        if (count($parts) === 2) {
            return strtoupper($parts[0]);
        }
        $cleaned = preg_replace('/[^A-Za-z]/', '', $this->city);
        return strtoupper(substr($cleaned, 0, 3));
    }

    public function getKecamatanCodeAttribute()
    {
        $parts = explode('-', $this->code);
        if (count($parts) === 3) {
            return strtoupper($parts[2]);
        }
        if (count($parts) === 2) {
            return strtoupper($parts[1]);
        }
        return strtoupper($this->code);
    }

    // Static Helpers
    public static function getProvinceCodeByName($province)
    {
        $area = self::where('province', $province)->where('code', 'like', '%-%-%')->first();
        if ($area) {
            $parts = explode('-', $area->code);
            return strtoupper($parts[0]);
        }
        $firstArea = self::where('province', $province)->first();
        if ($firstArea) {
            $parts = explode('-', $firstArea->code);
            if (count($parts) === 2) {
                // If it is 2-part code (like MDN-BRU), there's no province code. 
                // Return fallback:
                $cleaned = preg_replace('/[^A-Za-z]/', '', $province);
                return strtoupper(substr($cleaned, 0, 3));
            }
        }
        $cleaned = preg_replace('/[^A-Za-z]/', '', $province);
        return strtoupper(substr($cleaned, 0, 3));
    }

    public static function getCityCodeByName($province, $city)
    {
        $area = self::where('province', $province)->where('city', $city)->first();
        if ($area) {
            return $area->city_code;
        }
        $cleaned = preg_replace('/[^A-Za-z]/', '', $city);
        return strtoupper(substr($cleaned, 0, 3));
    }
}
