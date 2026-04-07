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
Schema::create('tr_detail_reservasi', function (Blueprint $table) {
    $table->id('id_detail');
    
    // Foreign Keys
    $table->unsignedBigInteger('id_reservasi');
    $table->unsignedBigInteger('id_layanan');
    
    $table->decimal('harga', 10, 2);

    // Relasi
    $table->foreign('id_reservasi')->references('id_reservasi')->on('tr_reservasi')->onDelete('cascade');
    $table->foreign('id_layanan')->references('id_layanan')->on('ms_layanan')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_detail_reservasi');
    }
};
