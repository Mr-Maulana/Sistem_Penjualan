<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sales')) {
            return;
        }

        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'payment_term')) {
                $table->string('payment_term', 50)->nullable()->after('salesman_id');
            }
            if (!Schema::hasColumn('sales', 'down_payment')) {
                $table->decimal('down_payment', 15, 2)->default(0)->after('payment_term');
            }
            if (!Schema::hasColumn('sales', 'tax')) {
                $table->decimal('tax', 15, 2)->default(0)->after('discount');
            }
            if (Schema::hasColumn('sales', 'notes') && !Schema::hasColumn('sales', 'note')) {
                // Keep existing "notes" field; provide alias-like "note" column for reference compatibility.
                $table->text('note')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('sales')) {
            return;
        }

        Schema::table('sales', function (Blueprint $table) {
            foreach (['payment_term', 'down_payment', 'tax', 'note'] as $col) {
                if (Schema::hasColumn('sales', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

