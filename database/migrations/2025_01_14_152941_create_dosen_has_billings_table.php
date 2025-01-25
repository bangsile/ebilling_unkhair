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
        Schema::create('dosen_has_billings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_dosen_id');
            $table->uuid('billing_id');

            $table->foreign('user_dosen_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('billing_id')
                ->references('id')
                ->on('billings')
                ->onDelete('cascade');

            $table->timestamps();

            // Tambahkan indeks jika belum ada
            $table->index('user_dosen_id');
            $table->index('billing_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_has_billings');
    }
};
