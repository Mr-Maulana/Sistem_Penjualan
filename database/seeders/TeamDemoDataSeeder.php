<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\User;
use App\Models\Salesman;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Price;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CashFlow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TeamDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ensure categories exist
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $catNames = ['MAKANAN', 'MINUMAN', 'KEBUTUHAN RUMAH', 'PERAWATAN TUBUH', 'ELEKTRONIK'];
            foreach ($catNames as $name) {
                $categories->push(Category::create(['name' => $name]));
            }
        }

        // Get all managers
        $managers = Salesman::where('level', 'manager')->get();

        foreach ($managers as $manager) {
            $province = $manager->area; // e.g. "Aceh"
            if (empty($province)) {
                continue;
            }

            // Find cities in this province
            $cities = Area::where('province', $province)->pluck('city')->unique()->filter()->values()->toArray();
            if (empty($cities)) {
                // Fallback to a default city for the province
                $cities = [$province];
            }

            // Find existing suppliers in these cities
            $suppliers = Supplier::whereIn('city', $cities)->get();
            $supplierCount = $suppliers->count();

            // We need at least 5 suppliers for this manager
            while ($supplierCount < 5) {
                $city = $faker->randomElement($cities);
                $supplierCode = 'SPL-' . strtoupper($manager->code) . '-' . str_pad($supplierCount + 1, 3, '0', STR_PAD_LEFT);
                
                // If supplier already exists, skip or use unique code
                if (Supplier::where('code', $supplierCode)->exists()) {
                    $supplierCode = 'SPL-' . strtoupper($manager->code) . '-' . str_pad($supplierCount + 1, 3, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(md5(uniqid()), 0, 2));
                }

                $companyName = 'PT ' . strtoupper($faker->company) . ' ' . strtoupper($province);
                $uniqueProdCode = 'P' . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $manager->code), 0, 2)) . ($supplierCount + 1);

                $newSupplier = Supplier::create([
                    'code' => $supplierCode,
                    'name' => 'SUPPLIER ' . ($supplierCount + 1) . ' - ' . strtoupper($city),
                    'company_name' => $companyName,
                    'npwp' => $faker->numerify('##.###.###.#-###.###'),
                    'product_code' => $uniqueProdCode,
                    'address' => $faker->address,
                    'city' => $city,
                    'phone' => $faker->phoneNumber,
                    'status' => 'active',
                ]);
                $suppliers->push($newSupplier);
                $supplierCount++;
            }

            // For each of the 5 suppliers, make sure there are exactly 15 products
            foreach ($suppliers as $supIndex => $supplier) {
                $products = Product::where('supplier_code', $supplier->code)->get();
                $productCount = $products->count();

                $realisticNames = [
                    'Kopi Bubuk', 'Susu Cokelat', 'Teh Celup', 'Biskuit Keju', 'Keripik Singkong',
                    'Sabun Cuci Piring', 'Deterjen Bubuk', 'Pembersih Lantai', 'Shampoo Herbal', 'Pasta Gigi',
                    'Mie Instan Kuah', 'Kecap Manis', 'Minyak Goreng', 'Gula Pasir', 'Tepung Terigu',
                    'Mineral Water', 'Air Kelapa Pack', 'Cokelat Batang', 'Permen Mint', 'Snack Rumput Laut'
                ];

                while ($productCount < 15) {
                    // Use deterministic hash of supplier code and product count to guarantee absolute uniqueness
                    $hashSuffix = strtoupper(substr(md5($supplier->code . '-' . $productCount), 0, 4));
                    $prodCode = $supplier->product_code . '-' . str_pad($productCount + 1, 3, '0', STR_PAD_LEFT) . '-' . $hashSuffix;
                    
                    $prodName = ($realisticNames[$productCount % count($realisticNames)]) . ' ' . ($productCount + 1);

                    Product::create([
                        'code' => $prodCode,
                        'name' => strtoupper($prodName),
                        'category_id' => $faker->randomElement($categories)->id,
                        'supplier_code' => $supplier->code,
                        'price' => $faker->numberBetween(5000, 75000),
                        'stock' => $faker->numberBetween(100, 1000),
                    ]);
                    $productCount++;
                }
            }
        }

        // Now, for every salesman (except managers, meaning sales and supervisors):
        $salesmen = Salesman::where('level', '!=', 'manager')->get();

        foreach ($salesmen as $salesman) {
            // Find or create at least 3 customers for this salesman
            $customers = Customer::where('salesman_id', $salesman->id)->get();
            if ($customers->count() < 3) {
                $custCount = $customers->count();
                while ($custCount < 3) {
                    $custCode = 'CST-' . strtoupper($salesman->code) . '-' . str_pad($custCount + 1, 3, '0', STR_PAD_LEFT);
                    
                    // Ensure uniqueness
                    if (Customer::where('code', $custCode)->exists()) {
                        $custCode = 'CST-' . strtoupper($salesman->code) . '-' . str_pad($custCount + 1, 3, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(md5(uniqid()), 0, 2));
                    }

                    $newCust = Customer::create([
                        'code' => $custCode,
                        'name' => 'CUSTOMER ' . strtoupper($faker->name),
                        'nik' => $faker->numerify('################'),
                        'npwp' => $faker->numerify('##.###.###.#-###.###'),
                        'address' => $faker->address,
                        'city' => $salesman->city ?? $faker->city,
                        'phone' => $faker->phoneNumber,
                        'group' => $faker->randomElement(['Regular', 'VIP', 'Wholesale']),
                        'salesman_id' => $salesman->id,
                        'status' => 'active',
                    ]);
                    $customers->push($newCust);
                    $custCount++;
                }
            }

            // Find products belonging to their Manager's suppliers (to keep scoping 100% correct!)
            // First, find the manager of this salesman.
            $manager = null;
            if ($salesman->level === 'supervisor') {
                $manager = Salesman::find($salesman->supervisor_id);
            } elseif ($salesman->level === 'sales') {
                $supervisor = Salesman::find($salesman->supervisor_id);
                if ($supervisor) {
                    $manager = Salesman::find($supervisor->supervisor_id);
                }
            }

            // Fallback: If no manager found, get the first manager
            if (!$manager) {
                $manager = Salesman::where('level', 'manager')->first();
            }

            // Get products of the manager's suppliers
            $teamSupplierCodes = [];
            if ($manager && !empty($manager->area)) {
                $managerCities = Area::where('province', $manager->area)->pluck('city')->unique()->filter()->values()->toArray();
                $teamSupplierCodes = Supplier::whereIn('city', $managerCities)->pluck('code')->toArray();
            }

            $teamProducts = Product::whereIn('supplier_code', $teamSupplierCodes)->get();
            if ($teamProducts->isEmpty()) {
                // Fallback to all products
                $teamProducts = Product::all();
            }

            // Verify how many sales transactions this salesman has
            $salesCount = Sale::where('salesman_id', $salesman->id)->count();

            // We need at least 15 transactions
            while ($salesCount < 15) {
                $invoiceNum = 'INV-' . strtoupper($salesman->code) . '-' . Carbon::now()->format('Ymd') . '-' . str_pad($salesCount + 1, 4, '0', STR_PAD_LEFT);
                
                // Ensure uniqueness
                if (Sale::where('invoice_number', $invoiceNum)->exists()) {
                    $invoiceNum = 'INV-' . strtoupper($salesman->code) . '-' . Carbon::now()->format('Ymd') . '-' . str_pad($salesCount + 1, 4, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(md5(uniqid()), 0, 2));
                }

                $customer = $faker->randomElement($customers);
                $saleDate = Carbon::now()->subDays($faker->numberBetween(0, 90));

                $sale = Sale::create([
                    'invoice_number' => $invoiceNum,
                    'date' => $saleDate,
                    'customer_id' => $customer->id,
                    'salesman_id' => $salesman->id,
                    'payment_term' => $faker->randomElement(['Cash', '30 Days', '60 Days']),
                    'down_payment' => 0,
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax' => 0,
                    'total' => 0,
                    'status' => 'paid',
                    'notes' => 'Seeded transaction ' . ($salesCount + 1),
                ]);

                // Create 1-3 sale items
                $numItems = $faker->numberBetween(1, 3);
                $subtotal = 0;
                $usedProducts = [];

                for ($itemIdx = 0; $itemIdx < $numItems; $itemIdx++) {
                    $product = $faker->randomElement($teamProducts);
                    
                    // Don't duplicate products in same sale
                    if (in_array($product->code, $usedProducts)) {
                        continue;
                    }
                    $usedProducts[] = $product->code;

                    $qty = $faker->numberBetween(1, 10);
                    $price = $product->price;
                    $itemSubtotal = $qty * $price;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_code' => $product->code,
                        'quantity' => $qty,
                        'price' => $price,
                        'discount' => 0,
                        'bonus' => 0,
                        'subtotal' => $itemSubtotal,
                    ]);

                    // Decrement stock
                    $product->decrement('stock', $qty);
                    $subtotal += $itemSubtotal;
                }

                $sale->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                ]);

                // Create/Sync Cash Flow
                $cashFlowService = new \App\Services\CashFlowService();
                $cashFlowService->syncFromSale($sale);

                $salesCount++;
            }
        }
    }
}
