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
        Schema::create('billing_ukts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('trx_id')->unique()->nullable();
            $table->string('no_va')->unique()->nullable();
            $table->string('nama_bank');
            $table->integer('nominal');
            $table->datetime('tgl_expire')->nullable();
            $table->boolean('lunas')->default(false);
            $table->string('jenis_bayar');
            $table->string('nama');
            $table->string('no_identitas');
            $table->smallInteger('angkatan');
            $table->smallInteger('tahun_akademik');
            $table->string('kode_prodi');
            $table->string('nama_prodi');
            $table->string('nama_fakultas');
            $table->string('kategori_ukt', 10);
            $table->string('jalur', 20)->nullable();
            $table->json('detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_ukts');
    }
};
