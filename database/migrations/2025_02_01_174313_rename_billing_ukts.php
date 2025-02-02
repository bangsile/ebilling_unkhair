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
        Schema::rename('billing_ukts', 'billing_mahasiswas');
        Schema::table('billing_mahasiswas', function (Blueprint $table) {
            $table->string('kategori_ukt')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('billing_mahasiswas', 'billing_ukts');
        Schema::table('billing_ukts', function (Blueprint $table) {
            $table->string('kategori_ukt')->nullable(false)->change();
        });
    }
};
