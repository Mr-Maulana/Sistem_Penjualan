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
        Schema::rename('distributors', 'suppliers');
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('name');
            $table->string('npwp', 30)->nullable()->after('company_name');
            $table->string('product_code', 50)->nullable()->after('npwp');
            $table->string('product_type', 100)->nullable()->after('product_code');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'npwp', 'product_code', 'product_type']);
        });
        Schema::rename('suppliers', 'distributors');
    }
};
