<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tr_reservasi', function (Blueprint $table) {
            $table->id('id_reservasi');
            $table->unsignedBigInteger('id_user'); // FK ke ms_user
            $table->date('tanggal_reservasi');
            $table->integer('jumlah_sepatu');
            $table->string('metode_layanan', 50);
            $table->text('alamat_jemput')->nullable();
            $table->string('status', 50);
            $table->decimal('total_harga', 10, 2);
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_reservasi');
    }
};