<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('prices');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::enableForeignKeyConstraints();

        Schema::create('suppliers', function (Blueprint $table) {
            $table->string('code', 50)->primary();
            $table->string('name', 200);
            $table->string('company_name', 200);
            $table->string('npwp', 50)->nullable();
            $table->string('product_code', 50);
            $table->string('city', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->string('code', 50)->primary();
            $table->string('name', 200);
            $table->foreignId('category_id')->constrained('categories');
            $table->string('supplier_code', 50);
            $table->foreign('supplier_code')->references('code')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('product_code', 50);
            $table->foreign('product_code')->references('code')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->string('customer_group', 50)->nullable();
            $table->decimal('price_large', 15, 2)->nullable();
            $table->decimal('price_small', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->date('effective_date')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('product_code', 50);
            $table->foreign('product_code')->references('code')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->integer('bonus')->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
    }
};
