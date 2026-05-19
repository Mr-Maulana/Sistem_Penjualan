// database/migrations/2024_01_01_000004_create_products_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            return;
        }

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 200);
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('distributor_id')->constrained('distributors');
            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};