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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('email');
            $table->string('nip', 20)->nullable()->after('nik');
            $table->string('profesi')->nullable()->after('nip');
            $table->string('phone', 15)->nullable()->after('profesi');
            $table->text('address')->nullable()->after('phone');
            $table->enum('gender', ['L', 'P'])->nullable()->after('address');
            $table->date('birth_date')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'nip', 'profesi', 'phone', 'address', 'gender', 'birth_date']);
        });
    }
};
