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
    Schema::create('tr_pembayaran', function (Blueprint $table) {
        $table->id('id_pembayaran'); // Primary Key
        
        // Foreign Key ke tr_reservasi
        $table->unsignedBigInteger('id_reservasi');
        
        $table->string('metode_pembayaran', 50);
        $table->dateTime('tanggal_bayar')->nullable();
        $table->string('status_pembayaran', 50);

        // Relasi
        $table->foreign('id_reservasi')->references('id_reservasi')->on('tr_reservasi')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_pembayaran');
    }
};
