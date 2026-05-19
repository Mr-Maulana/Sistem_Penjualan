<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'city')) {
                $table->string('city', 100)->nullable()->after('address');
            }
            if (!Schema::hasColumn('customers', 'group')) {
                // "group" is in the reference doc; keep name for compatibility.
                $table->string('group', 50)->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('customers')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('customers', 'group')) {
                $table->dropColumn('group');
            }
        });
    }
};

