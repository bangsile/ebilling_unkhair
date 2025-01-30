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
        Schema::create('tahun_pembayarans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tahun_akademik', 5)->nullable();
            $table->datetime('awal_pembayaran')->nullable();
            $table->datetime('akhir_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_pembayarans');
    }
};
