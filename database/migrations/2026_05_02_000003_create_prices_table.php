<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('prices')) {
            return;
        }

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('customer_group', 50)->nullable()->index();
            $table->decimal('price_large', 15, 2)->nullable();
            $table->decimal('price_small', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->date('effective_date')->nullable()->index();
            $table->timestamps();

            $table->unique(['product_id', 'customer_group', 'effective_date'], 'prices_product_group_effective_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};

