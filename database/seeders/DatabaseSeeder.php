<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DistributorSeeder::class,
            SalesmanSeeder::class,
            CategorySeeder::class,
        ]);
    }
}