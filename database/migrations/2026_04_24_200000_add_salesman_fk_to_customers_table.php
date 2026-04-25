<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers') || !Schema::hasTable('salesmen') || !Schema::hasColumn('customers', 'salesman_id')) {
            return;
        }

        // Make sure column type/engine are compatible for FK (common issue on existing tables).
        try {
            DB::statement('ALTER TABLE `customers` ENGINE=InnoDB');
        } catch (\Throwable $e) {
        }
        try {
            DB::statement('ALTER TABLE `salesmen` ENGINE=InnoDB');
        } catch (\Throwable $e) {
        }
        try {
            DB::statement('ALTER TABLE `customers` MODIFY `salesman_id` BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('salesman_id')
                ->references('id')
                ->on('salesmen')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['salesman_id']);
        });
    }
};

