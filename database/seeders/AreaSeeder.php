<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            // ACEH (Sabang)
            ['province' => 'Aceh', 'city' => 'Sabang', 'name' => 'Sukakarya', 'code' => 'SBG-SKK'],
            ['province' => 'Aceh', 'city' => 'Sabang', 'name' => 'Sukajaya', 'code' => 'SBG-SKJ'],
            // ACEH (Lhokseumawe)
            ['province' => 'Aceh', 'city' => 'Lhokseumawe', 'name' => 'Kuta Blang', 'code' => 'LHK-KBL'],
            ['province' => 'Aceh', 'city' => 'Lhokseumawe', 'name' => 'Banda Sakti', 'code' => 'LHK-BSK'],
            
            // SUMATERA UTARA
            ['province' => 'Sumatera Utara', 'city' => 'Medan', 'name' => 'Medan Baru', 'code' => 'MDN-BRU'],
            ['province' => 'Sumatera Utara', 'city' => 'Medan', 'name' => 'Medan Selayang', 'code' => 'MDN-SLY'],

            // DKI JAKARTA
            ['province' => 'DKI Jakarta', 'city' => 'Jakarta Pusat', 'name' => 'Gambir', 'code' => 'JKT-GMB'],
            ['province' => 'DKI Jakarta', 'city' => 'Jakarta Selatan', 'name' => 'Kebayoran Baru', 'code' => 'JKT-KBY'],

            // JAWA BARAT
            ['province' => 'Jawa Barat', 'city' => 'Bandung', 'name' => 'Coblong', 'code' => 'BDG-CBL'],
            ['province' => 'Jawa Barat', 'city' => 'Bogor', 'name' => 'Bogor Timur', 'code' => 'BGR-TMR'],

            // JAWA TIMUR
            ['province' => 'Jawa Timur', 'city' => 'Surabaya', 'name' => 'Wonokromo', 'code' => 'SUB-WNK'],
            ['province' => 'Jawa Timur', 'city' => 'Malang', 'name' => 'Lowokwaru', 'code' => 'MLG-LWK'],

            // BALI
            ['province' => 'Bali', 'city' => 'Denpasar', 'name' => 'Denpasar Selatan', 'code' => 'DPS-SLT'],

            // SULAWESI SELATAN
            ['province' => 'Sulawesi Selatan', 'city' => 'Makassar', 'name' => 'Panakkukang', 'code' => 'MKS-PNK'],

            // PAPUA (Merauke)
            ['province' => 'Papua', 'city' => 'Merauke', 'name' => 'Merauke Kota', 'code' => 'MRK-KTA'],
            ['province' => 'Papua', 'city' => 'Merauke', 'name' => 'Sota', 'code' => 'MRK-SOT'],
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate(['code' => $area['code']], $area);
        }
    }
}
