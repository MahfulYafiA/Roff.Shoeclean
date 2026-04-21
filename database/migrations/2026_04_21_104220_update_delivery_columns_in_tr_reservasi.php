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
        Schema::table('tr_reservasi', function (Blueprint $table) {
            // Kita hapus ->after(), biar Laravel taruh di paling bawah otomatis
            $table->string('metode_masuk', 50)->nullable();
            $table->string('metode_keluar', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table) {
            $table->dropColumn(['metode_masuk', 'metode_keluar']);
        });
    }
};