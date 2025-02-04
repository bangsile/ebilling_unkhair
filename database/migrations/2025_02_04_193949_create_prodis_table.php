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
        Schema::create('prodi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('fakultas_id')->nullable()->references('id')->on('fakultas');
            $table->string('kd_prodi', 10)->nullable();
            $table->string('nm_prodi', 150)->nullable();
            $table->char('status', 1)->nullable();
            $table->string('jenjang', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodi');
    }
};
