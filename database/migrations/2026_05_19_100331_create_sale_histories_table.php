<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('invoice_number');
            $table->date('date');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('salesman_id')->constrained('salesmen');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Backfill existing sales into sale_histories
        $sales = DB::table('sales')->get();
        foreach ($sales as $sale) {
            DB::table('sale_histories')->insert([
                'sale_id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'date' => $sale->date,
                'customer_id' => $sale->customer_id,
                'salesman_id' => $sale->salesman_id,
                'subtotal' => $sale->subtotal,
                'discount' => $sale->discount,
                'tax' => $sale->tax,
                'total' => $sale->total,
                'status' => $sale->status,
                'notes' => $sale->notes,
                'created_at' => $sale->created_at ?: now(),
                'updated_at' => $sale->updated_at ?: now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_histories');
    }
};
