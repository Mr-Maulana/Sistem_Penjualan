<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Elektronik', 'Fashion', 'Makanan', 'Minuman', 'Perlengkapan Rumah'];
        
        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category], ['name' => $category]);
        }
    }
}