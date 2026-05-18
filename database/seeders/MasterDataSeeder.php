<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Categories
        $catFood = Category::create(['name' => 'MAKANAN']);
        $catDrink = Category::create(['name' => 'MINUMAN']);
        $catHome = Category::create(['name' => 'KEBUTUHAN RUMAH']);
        $catBody = Category::create(['name' => 'PERAWATAN TUBUH']);

        // Suppliers
        $suppliers = [
            [
                'code' => 'OTG-001',
                'name' => 'MAULANA - OT GROUP',
                'company_name' => 'PT ORANGE TUA GROUP',
                'npwp' => '01.234.567.8-001.000',
                'product_code' => 'OTG',
                'address' => 'Jl. Lingkar Luar Barat Kav. 35-36',
                'city' => 'JAKARTA BARAT',
                'phone' => '021-5839777',
                'status' => 'active',
                'products' => [
                    ['name' => 'Tango Wafer Cokelat 130g', 'cat' => $catFood, 'price' => 8500, 'stock' => 120],
                    ['name' => 'Oops Crackers Roasted Chicken', 'cat' => $catFood, 'price' => 7000, 'stock' => 85],
                    ['name' => 'Kiranti Sehat Datang Bulan', 'cat' => $catDrink, 'price' => 6500, 'stock' => 50],
                ]
            ],
            [
                'code' => 'IFD-001',
                'name' => 'DISTRIBUSI INDOFOOD',
                'company_name' => 'PT INDOFOOD CBP SUKSES MAKMUR',
                'npwp' => '01.333.444.5-002.000',
                'product_code' => 'IFD',
                'address' => 'Sudirman Plaza, Indofood Tower',
                'city' => 'JAKARTA SELATAN',
                'phone' => '021-57958822',
                'status' => 'active',
                'products' => [
                    ['name' => 'Indomie Goreng Spesial', 'cat' => $catFood, 'price' => 3100, 'stock' => 500],
                    ['name' => 'Sarimi Isi 2 Ayam Bawang', 'cat' => $catFood, 'price' => 4500, 'stock' => 300],
                    ['name' => 'Pop Mie Baso', 'cat' => $catFood, 'price' => 5500, 'stock' => 150],
                ]
            ],
            [
                'code' => 'MYR-001',
                'name' => 'MAYORA LOGISTICS',
                'company_name' => 'PT MAYORA INDAH TBK',
                'npwp' => '01.999.888.7-003.000',
                'product_code' => 'MYR',
                'address' => 'Jl. Tomang Raya No. 21-23',
                'city' => 'JAKARTA BARAT',
                'phone' => '021-5655311',
                'status' => 'active',
                'products' => [
                    ['name' => 'Beng-Beng Share It', 'cat' => $catFood, 'price' => 12500, 'stock' => 100],
                    ['name' => 'Roma Kelapa 300g', 'cat' => $catFood, 'price' => 10500, 'stock' => 60],
                    ['name' => 'Kopiko Candy Pack', 'cat' => $catFood, 'price' => 8000, 'stock' => 200],
                ]
            ],
            [
                'code' => 'WNG-001',
                'name' => 'WINGS DISTRIBUSI',
                'company_name' => 'PT WINGS SURYA',
                'npwp' => '02.111.222.3-004.000',
                'product_code' => 'WNG',
                'address' => 'Jl. Tipar Cakung Kav. F 5-7',
                'city' => 'JAKARTA TIMUR',
                'phone' => '021-4602685',
                'status' => 'active',
                'products' => [
                    ['name' => 'So Klin Liquid 800ml', 'cat' => $catHome, 'price' => 18500, 'stock' => 40],
                    ['name' => 'Mie Sedaap Goreng', 'cat' => $catFood, 'price' => 3000, 'stock' => 450],
                    ['name' => 'Daia Putih 850g', 'cat' => $catHome, 'price' => 17000, 'stock' => 35],
                ]
            ],
            [
                'code' => 'UNL-001',
                'name' => 'UNILEVER AREA ACEH',
                'company_name' => 'PT UNILEVER INDONESIA TBK',
                'npwp' => '01.000.555.4-005.000',
                'product_code' => 'UNL',
                'address' => 'Grha Unilever, BSD City',
                'city' => 'TANGERANG',
                'phone' => '021-5262112',
                'status' => 'active',
                'products' => [
                    ['name' => 'Pepsodent Jumbo 190g', 'cat' => $catBody, 'price' => 15500, 'stock' => 80],
                    ['name' => 'Rinso Anti Noda 800g', 'cat' => $catHome, 'price' => 22000, 'stock' => 50],
                    ['name' => 'Sunsilk Black Shine 170ml', 'cat' => $catBody, 'price' => 24000, 'stock' => 45],
                ]
            ],
        ];

        foreach ($suppliers as $sData) {
            $pList = $sData['products'];
            unset($sData['products']);
            
            $supplier = Supplier::create($sData);
            
            foreach ($pList as $index => $p) {
                $code = $supplier->product_code . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                Product::create([
                    'supplier_code' => $supplier->code,
                    'category_id' => $p['cat']->id,
                    'code' => $code,
                    'name' => $p['name'],
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                ]);
            }
        }
    }
}
