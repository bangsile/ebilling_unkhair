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
        Schema::create('history_banks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('trx_id');
            $table->string('no_va');
            $table->integer('nominal');
            $table->string('nama');
            $table->string('metode_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_banks');
    }
};
