<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sale_items')) {
            return;
        }

        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'discount')) {
                $table->decimal('discount', 15, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('sale_items', 'bonus')) {
                $table->integer('bonus')->default(0)->after('discount');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('sale_items')) {
            return;
        }

        Schema::table('sale_items', function (Blueprint $table) {
            foreach (['discount', 'bonus'] as $col) {
                if (Schema::hasColumn('sale_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

