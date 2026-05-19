<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        // Set maximum execution times for large seeding operations
        ini_set('memory_limit', '512M');
        set_time_limit(600);

        // Name Normalizer
        $normalizeName = function($name) {
            $clean = preg_replace('/^(KABUPATEN|KOTA|KAB\.)\s+/i', '', $name);
            return ucwords(strtolower(trim($clean)));
        };

        // Province Code Generator
        $getProvinceCode = function($provName) {
            $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', $provName));
            if ($provName === 'DKI Jakarta') return 'JKT';
            if ($provName === 'DI Yogyakarta') return 'DIY';
            if ($provName === 'Aceh') return 'ACE';
            return str_pad(substr($clean, 0, 3), 3, 'X');
        };

        // City Code Generator
        $getCityCode = function($cityName) {
            $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', $cityName));
            return str_pad(substr($clean, 0, 3), 3, 'X');
        };

        try {
            // Attempt to seed dynamically from the highly available official API
            $provincesJson = @file_get_contents('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
            if ($provincesJson) {
                $provinces = json_decode($provincesJson, true);
                
                foreach ($provinces as $p) {
                    $pId = $p['id'];
                    $rawProvName = $p['name'];
                    
                    if ($rawProvName === 'DAERAH KHUSUS IBUKOTA JAKARTA') {
                        $provName = 'DKI Jakarta';
                    } elseif ($rawProvName === 'DAERAH ISTIMEWA YOGYAKARTA') {
                        $provName = 'DI Yogyakarta';
                    } else {
                        $provName = $normalizeName($rawProvName);
                    }
                    
                    $provCode = $getProvinceCode($provName);
                    
                    $regenciesJson = @file_get_contents("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$pId}.json");
                    if ($regenciesJson) {
                        $regencies = json_decode($regenciesJson, true);
                        foreach ($regencies as $r) {
                            $cityName = $normalizeName($r['name']);
                            $cityCode = $getCityCode($cityName);
                            $areaCode = "{$provCode}-{$cityCode}-01";
                            
                            Area::updateOrCreate(
                                ['code' => $areaCode],
                                [
                                    'province' => $provName,
                                    'city' => $cityName,
                                    'name' => "Kecamatan " . $cityName
                                ]
                            );
                        }
                    }
                }
                return; // Seeding succeeded from API
            }
        } catch (\Exception $e) {
            // Silently swallow and fall back to local seed data
        }

        // Fallback Core Operational Cities if offline
        $fallbackAreas = [
            ['province' => 'Aceh', 'city' => 'Sabang', 'name' => 'Sukakarya', 'code' => 'SBG-SKK'],
            ['province' => 'Aceh', 'city' => 'Sabang', 'name' => 'Sukajaya', 'code' => 'SBG-SKJ'],
            ['province' => 'Aceh', 'city' => 'Lhokseumawe', 'name' => 'Kuta Blang', 'code' => 'LHK-KBL'],
            ['province' => 'Aceh', 'city' => 'Lhokseumawe', 'name' => 'Banda Sakti', 'code' => 'LHK-BSK'],
            ['province' => 'Aceh', 'city' => 'Banda Aceh', 'name' => 'Baiturrahman', 'code' => 'BNA-BTR'],
            ['province' => 'Aceh', 'city' => 'Langsa', 'name' => 'Langsa Kota', 'code' => 'LGS-KTA'],
            ['province' => 'Aceh', 'city' => 'Bireuen', 'name' => 'Kota Juang', 'code' => 'BIR-KJG'],
            
            ['province' => 'Sumatera Utara', 'city' => 'Medan', 'name' => 'Medan Baru', 'code' => 'MDN-BRU'],
            ['province' => 'Sumatera Utara', 'city' => 'Binjai', 'name' => 'Binjai Kota', 'code' => 'BNJ-KTA'],
            
            ['province' => 'DKI Jakarta', 'city' => 'Jakarta Pusat', 'name' => 'Gambir', 'code' => 'JKT-GMB'],
            ['province' => 'DKI Jakarta', 'city' => 'Jakarta Barat', 'name' => 'Cengkareng', 'code' => 'JKT-CKR'],
            ['province' => 'DKI Jakarta', 'city' => 'Jakarta Selatan', 'name' => 'Kebayoran Baru', 'code' => 'JKT-KBY'],
            ['province' => 'DKI Jakarta', 'city' => 'Jakarta Timur', 'name' => 'Cakung', 'code' => 'JKT-CKG'],
            ['province' => 'DKI Jakarta', 'city' => 'Jakarta Utara', 'name' => 'Tanjung Priok', 'code' => 'JKT-TPR'],
            
            ['province' => 'Jawa Barat', 'city' => 'Bandung', 'name' => 'Coblong', 'code' => 'BDG-CBL'],
            ['province' => 'Jawa Barat', 'city' => 'Bogor', 'name' => 'Bogor Timur', 'code' => 'BGR-TMR'],
            ['province' => 'Jawa Barat', 'city' => 'Tangerang', 'name' => 'Cipondoh', 'code' => 'TNG-CPD'],
            
            ['province' => 'Jawa Timur', 'city' => 'Surabaya', 'name' => 'Wonokromo', 'code' => 'SUB-WNK'],
            ['province' => 'Jawa Timur', 'city' => 'Malang', 'name' => 'Lowokwaru', 'code' => 'MLG-LWK'],
            
            ['province' => 'Bali', 'city' => 'Denpasar', 'name' => 'Denpasar Selatan', 'code' => 'DPS-SLT'],
            ['province' => 'Sulawesi Selatan', 'city' => 'Makassar', 'name' => 'Panakkukang', 'code' => 'MKS-PNK'],
            ['province' => 'Papua', 'city' => 'Merauke', 'name' => 'Merauke Kota', 'code' => 'MRK-KTA'],
        ];

        foreach ($fallbackAreas as $area) {
            Area::updateOrCreate(['code' => $area['code']], $area);
        }
    }
}
