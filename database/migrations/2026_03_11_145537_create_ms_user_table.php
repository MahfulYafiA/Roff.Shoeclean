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
            $table->id('id_user');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('google_id', 255)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->text('alamat')->nullable(); // Ditambahkan untuk FR03 (Profil) & FR07 (Logistik)
            $table->string('foto_profil', 255)->nullable();
            $table->unsignedBigInteger('id_role'); // Foreign Key
            
            // Wajib untuk sistem Autentikasi Laravel (FR02)
            $table->rememberToken(); 
            
            // Timestamps untuk melacak kapan akun dibuat dan profil diupdate
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Relasi ke tabel ms_role
            $table->foreign('id_role')
                  ->references('id_role')->on('ms_role')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
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