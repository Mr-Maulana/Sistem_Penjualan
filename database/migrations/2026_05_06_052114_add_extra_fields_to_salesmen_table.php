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
        Schema::table('salesmen', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->after('name');
            $table->string('npwp', 30)->nullable()->after('nik');
            $table->string('email')->nullable()->after('npwp');
            $table->text('address')->nullable()->after('email');
            $table->enum('level', ['sales', 'supervisor', 'manager'])->default('sales')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('salesmen', function (Blueprint $table) {
            $table->dropColumn(['nik', 'npwp', 'email', 'address', 'level']);
        });
    }
};
