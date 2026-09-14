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
        Schema::table('sales', function (Blueprint $table) {
            $table->index('date');
            $table->index('status');
        });

        Schema::table('cash_flows', function (Blueprint $table) {
            $table->index('date');
            $table->index('type');
        });

        Schema::table('sale_histories', function (Blueprint $table) {
            $table->index('date');
            $table->index('status');
            $table->index('salesman_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['status']);
        });

        Schema::table('cash_flows', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['type']);
        });

        Schema::table('sale_histories', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['salesman_id']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
