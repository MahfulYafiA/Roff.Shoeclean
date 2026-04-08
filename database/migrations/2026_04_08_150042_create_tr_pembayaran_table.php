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
            // Primary Key sesuai ERD
            $table->increments('id_pembayaran'); 

            // Foreign Key ke tabel tr_reservasi
            $table->unsignedInteger('id_reservasi'); 
            
            $table->string('metode_pembayaran', 50);
            $table->dateTime('tanggal_bayar');
            $table->string('status_pembayaran', 50);
            
            // Menggunakan VARCHAR 150 sesuai kesepakatan Strict UMKM
            $table->string('bukti_pembayaran', 150); 

            // Timestamps opsional, tapi bagus untuk audit
            $table->timestamps(); 

            // Pemasangan Kabel Relasi
            $table->foreign('id_reservasi')
                  ->references('id_reservasi')
                  ->on('tr_reservasi')
                  ->onDelete('cascade');
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