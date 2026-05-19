// database/migrations/2024_01_01_000002_create_salesmen_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('salesmen')) {
            return;
        }

        Schema::create('salesmen', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 200);
            $table->string('area', 100);
            $table->string('phone', 20);
            $table->decimal('target', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salesmen');
    }
};