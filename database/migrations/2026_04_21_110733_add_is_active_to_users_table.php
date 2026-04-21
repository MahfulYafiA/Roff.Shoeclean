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
        // GANTI 'users' dengan nama tabel Mas (contoh: 'ms_user')
        Schema::table('ms_user', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // GANTI 'users' dengan nama tabel Mas (contoh: 'ms_user')
        Schema::table('ms_user', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};