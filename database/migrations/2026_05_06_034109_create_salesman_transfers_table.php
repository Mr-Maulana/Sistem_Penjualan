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
        Schema::create('salesman_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salesman_id');
            $table->unsignedBigInteger('from_supervisor_id')->nullable();
            $table->unsignedBigInteger('to_supervisor_id');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('salesman_id')->references('id')->on('salesmen')->onDelete('cascade');
            $table->foreign('from_supervisor_id')->references('id')->on('salesmen')->onDelete('set null');
            $table->foreign('to_supervisor_id')->references('id')->on('salesmen')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesman_transfers');
    }
};
