<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Distributor;
use App\Models\Salesman;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Price;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CashFlow;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Users (10 users)
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => $faker->randomElement(['admin', 'supervisor', 'sales']),
                'nik' => $faker->numerify('################'),
                'nip' => $faker->numerify('##################'),
                'profesi' => $faker->jobTitle,
                'phone' => $faker->numerify('08##########'),
                'address' => $faker->address,
                'gender' => $faker->randomElement(['L', 'P']),
                'birth_date' => $faker->date('Y-m-d', '-20 years'),
            ]);
        }

        // 2. Distributors (10 distributors)
        for ($i = 1; $i <= 10; $i++) {
            Distributor::create([
                'code' => 'DBR-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $faker->company,
                'city' => $faker->city,
                'phone' => $faker->numerify('08##########'),
                'address' => $faker->address,
                'status' => 'active',
            ]);
        }

        // 3. Salesmen (10 salesmen)
        for ($i = 1; $i <= 10; $i++) {
            Salesman::create([
                'code' => 'SLS-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'area' => $faker->city,
                'phone' => $faker->numerify('08##########'),
                'target' => $faker->numberBetween(10000000, 50000000),
                'status' => 'active',
            ]);
        }

        // 4. Categories
        $categories = ['Minuman', 'Makanan Ringan', 'Kebutuhan Pokok', 'Kesehatan', 'Elektronik', 'Pakaian', 'Otomotif'];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }

        // 5. Customers (15 customers)
        $salesmenIds = Salesman::pluck('id')->toArray();
        for ($i = 1; $i <= 15; $i++) {
            Customer::create([
                'code' => 'CST-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'city' => $faker->city,
                'address' => $faker->address,
                'phone' => $faker->numerify('08##########'),
                'salesman_id' => $faker->randomElement($salesmenIds),
                'status' => 'active',
            ]);
        }

        // 6. Products (20 products)
        $distributorIds = Distributor::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        $realisticProducts = [
            'Coca Cola 250ml', 'Pepsi 330ml', 'Indomie Goreng', 'Indomie Rasa Soto',
            'Aqua 600ml', 'Teh Pucuk Harum', 'Sari Roti Tawar', 'Silverqueen Almond',
            'Lifebuoy Shampoo', 'Pepsodent White', 'Rinso Anti Noda', 'Sunlight 400ml',
            'Kecap Bango 550ml', 'Bimoli 2L', 'Ultra Milk 250ml', 'Kapal Api Special',
            'Biskuat Cokelat', 'Oreo Vanilla', 'Chitato Sapi Panggang', 'Rexona Men'
        ];

        foreach ($realisticProducts as $index => $name) {
            $buyPrice = $faker->numberBetween(1000, 50000);
            $product = Product::create([
                'code' => 'PRD-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $name,
                'category_id' => $faker->randomElement($categoryIds),
                'distributor_id' => $faker->randomElement($distributorIds),
                'price' => $buyPrice * 1.2,
                'stock' => $faker->numberBetween(50, 500),
            ]);

            // 7. Prices (Some special prices)
            Price::create([
                'product_id' => $product->id,
                'customer_group' => $faker->randomElement(['Regular', 'VIP', 'Wholesale']),
                'price_large' => $product->price * 0.9,
                'price_small' => $product->price,
                'effective_date' => Carbon::now()->subDays($faker->numberBetween(0, 10)),
            ]);
        }

        // 8. Sales (10 sales)
        $customerIds = Customer::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            $subtotal = 0;
            $sale = Sale::create([
                'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'date' => Carbon::now()->subDays($faker->numberBetween(0, 30)),
                'customer_id' => $faker->randomElement($customerIds),
                'salesman_id' => $faker->randomElement($salesmenIds),
                'subtotal' => 0,
                'discount' => $faker->randomElement([0, 0, 5000]),
                'total' => 0,
                'status' => $faker->randomElement(['paid', 'unpaid']),
                'notes' => $faker->sentence,
            ]);

            $numItems = $faker->numberBetween(1, 4);
            for ($j = 0; $j < $numItems; $j++) {
                $product = Product::find($faker->randomElement($productIds));
                $qty = $faker->numberBetween(1, 5);
                $itemSubtotal = $qty * $product->price;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ]);

                $subtotal += $itemSubtotal;
            }

            $sale->update([
                'subtotal' => $subtotal,
                'total' => $subtotal - $sale->discount,
            ]);

            // 9. Cash Flow (Income)
            if ($sale->status === 'paid') {
                CashFlow::create([
                    'code' => 'CF-IN-' . Carbon::now()->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'date' => $sale->date,
                    'type' => 'in',
                    'description' => 'Pembayaran invoice ' . $sale->invoice_number,
                    'amount' => $sale->total,
                    'balance' => 0,
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                ]);
            }
        }

        // Extra Cash Flow (Expenses)
        for ($i = 1; $i <= 5; $i++) {
            CashFlow::create([
                'code' => 'CF-OUT-' . Carbon::now()->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'date' => Carbon::now()->subDays($faker->numberBetween(0, 30)),
                'type' => 'out',
                'description' => $faker->randomElement(['Listrik', 'Gaji', 'Sewa']),
                'amount' => $faker->numberBetween(100000, 500000),
                'balance' => 0,
            ]);
        }
    }
}
