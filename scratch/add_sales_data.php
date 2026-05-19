<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Customer;
use App\Models\Salesman;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use App\Traits\CodeGenerator;

class TempSeeder {
    use CodeGenerator;
    public function run() {
        $products = Product::all();
        $customers = Customer::all();
        $salesmen = Salesman::all();

        if ($products->isEmpty() || $customers->isEmpty() || $salesmen->isEmpty()) {
            echo "Missing basic data (products, customers, or salesmen)\n";
            return;
        }

        foreach (range(1, 3) as $i) {
            DB::transaction(function() use ($products, $customers, $salesmen, $i) {
                $customer = $customers->random();
                $salesman = $salesmen->random();
                $invoice = $this->generateDatedCode(Sale::class, 'INV');
                
                $sale = Sale::create([
                    'invoice_number' => $invoice,
                    'date' => now()->subDays(rand(0, 5)),
                    'customer_id' => $customer->id,
                    'salesman_id' => $salesman->id,
                    'payment_term' => 'COD',
                    'status' => 'paid',
                    'subtotal' => 0,
                    'total' => 0,
                ]);

                $subtotal = 0;
                $itemsCount = rand(1, 3);
                $selectedProducts = $products->random($itemsCount);

                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 5);
                    $price = $product->base_price ?? 10000;
                    $lineSubtotal = $qty * $price;
                    
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_code' => $product->code,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $lineSubtotal,
                    ]);

                    $product->decrement('stock', $qty);
                    $subtotal += $lineSubtotal;
                }

                $sale->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                ]);

                // Sync CashFlow
                $cashFlowService = new \App\Services\CashFlowService();
                $cashFlowService->syncFromSale($sale);
                
                echo "Created sale: {$invoice}\n";
            });
            sleep(1); // To avoid same invoice number if based on timestamp
        }
    }
}

(new TempSeeder())->run();
