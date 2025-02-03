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
        Schema::create('log_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('trx_id');
            $table->string('no_va');
            $table->string('nama')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('job_result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_jobs');
    }
};
