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
        Schema::create('ms_user', function (Blueprint $table) {
            $table->increments('id_user'); // PK INT(11) di Workbench
            $table->string('nama', 40); // Sesuai Workbench (40)
            $table->string('email', 50)->unique(); // Sesuai Workbench (50)
            $table->string('password', 60); // Sesuai Workbench (60)
            $table->string('google_id', 30)->nullable(); // Sesuai Workbench (30)
            $table->string('no_telp', 15)->nullable(); // Sesuai Workbench (15)
            $table->string('alamat', 255)->nullable(); // Rekomendasi Strict (255)
            $table->string('foto_profil', 150)->nullable(); // Sesuai Workbench (150)
            $table->rememberToken(); // Otomatis VARCHAR(100) sesuai Workbench
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_user');
    }
};