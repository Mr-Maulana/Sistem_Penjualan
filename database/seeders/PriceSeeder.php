<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Price;
use Carbon\Carbon;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data harga lama agar tidak duplikat
        Price::truncate();

        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('Tidak ada produk ditemukan. Pastikan produk sudah diisi terlebih dahulu.');
            return;
        }

        $groups = [
            // [group_name, large_margin, small_margin, discount, tax]
            ['Grosir',  1.15, 1.20, 5,  0],
            ['Retail',  1.30, 1.40, 0,  0],
            ['VIP',     1.20, 1.25, 8,  0],
        ];

        $now = Carbon::now()->toDateString();

        foreach ($products as $product) {
            $base = (float) $product->price;

            foreach ($groups as [$groupName, $largeMargin, $smallMargin, $discount, $tax]) {
                Price::create([
                    'product_code'   => $product->code,
                    'customer_group' => $groupName,
                    'price_large'    => round($base * $largeMargin, -2),  // Dibulatkan ke ratusan
                    'price_small'    => round($base * $smallMargin, -2),
                    'discount'       => $discount,
                    'tax'            => $tax,
                    'effective_date' => $now,
                ]);
            }
        }

        $total = $products->count() * count($groups);
        $this->command->info("✅ {$total} data harga berhasil dibuat untuk {$products->count()} produk × " . count($groups) . " grup.");
    }
}
