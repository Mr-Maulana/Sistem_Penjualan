// database/migrations/2024_01_01_000006_create_cash_flows_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cash_flows')) {
            return;
        }

        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->date('date');
            $table->enum('type', ['in', 'out']);
            $table->string('description', 255);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance', 15, 2);
            $table->string('reference_type')->nullable(); // sale, expense, dll
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};