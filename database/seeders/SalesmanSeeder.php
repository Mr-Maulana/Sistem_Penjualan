<?php
namespace Database\Seeders;

use App\Models\Salesman;
use Illuminate\Database\Seeder;

class SalesmanSeeder extends Seeder
{
    public function run(): void
    {
        $salesmen = [
            ['code' => 'S001', 'name' => 'Budi Santoso', 'area' => 'Jakarta Selatan', 'phone' => '0811-1111-2222', 'target' => 150000000, 'status' => 'active'],
            ['code' => 'S002', 'name' => 'Rina Wati', 'area' => 'Bandung Raya', 'phone' => '0811-3333-4444', 'target' => 120000000, 'status' => 'active'],
            ['code' => 'S003', 'name' => 'Ahmad Fauzi', 'area' => 'Surabaya', 'phone' => '0811-5555-6666', 'target' => 200000000, 'status' => 'active'],
        ];
        
        foreach ($salesmen as $salesman) {
            Salesman::updateOrCreate(
                ['code' => $salesman['code']],
                $salesman
            );
        }
    }
}