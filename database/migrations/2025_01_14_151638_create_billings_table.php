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
        Schema::create('billings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('trx_id')->unique();
            $table->string('no_va')->unique();
            $table->string('nama_bank');
            $table->string('nama');
            $table->string('jenis_bayar');
            $table->foreign('jenis_bayar')->references('kode')->on('jenis_bayars')->cascadeOnDelete();
            $table->integer('nominal');
            $table->date('tgl_expire')->default(now()->addDays(2));
            $table->boolean('lunas')->default(false);
            $table->json('detail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
