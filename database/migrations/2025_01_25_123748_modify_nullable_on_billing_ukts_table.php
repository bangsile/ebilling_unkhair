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
        Schema::table('billing_ukts', function (Blueprint $table) {
            $table->string('trx_id')->nullable()->change();
            $table->string('no_va')->nullable()->change();
            $table->string('tgl_expire')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_ukts', function (Blueprint $table) {
            $table->string('trx_id')->nullable(false)->change();
            $table->string('no_va')->nullable(false)->change();
            $table->string('tgl_expire')->nullable(false)->change();
        });
    }
};
