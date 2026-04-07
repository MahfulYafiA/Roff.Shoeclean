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
Schema::create('ms_layanan', function (Blueprint $table) {
    $table->id('id_layanan');
    $table->string('nama_layanan', 100);
    $table->decimal('harga', 10, 2);
    $table->text('deskripsi')->nullable();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_layanan');
    }
};
