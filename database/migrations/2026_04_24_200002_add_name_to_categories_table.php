<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        if (Schema::hasColumn('categories', 'name')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name', 100)->after('id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('categories') || !Schema::hasColumn('categories', 'name')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};

