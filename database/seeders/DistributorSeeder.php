<?php
namespace Database\Seeders;

use App\Models\Distributor;
use Illuminate\Database\Seeder;

class DistributorSeeder extends Seeder
{
    public function run(): void
    {
        $distributors = [
            ['code' => 'D001', 'name' => 'PT Sumber Makmur', 'city' => 'Jakarta', 'phone' => '021-5551234', 'address' => 'Jl. Raya Jakarta No. 123', 'status' => 'active'],
            ['code' => 'D002', 'name' => 'CV Jaya Abadi', 'city' => 'Surabaya', 'phone' => '031-7778899', 'address' => 'Jl. Surabaya No. 45', 'status' => 'active'],
            ['code' => 'D003', 'name' => 'UD Sentosa', 'city' => 'Bandung', 'phone' => '022-4445566', 'address' => 'Jl. Bandung No. 78', 'status' => 'inactive'],
        ];
        
        foreach ($distributors as $distributor) {
            Distributor::updateOrCreate(
                ['code' => $distributor['code']],
                $distributor
            );
        }
    }
}