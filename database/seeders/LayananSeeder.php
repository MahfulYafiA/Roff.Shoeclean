<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan pengecekan foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel
        DB::table('ms_layanan')->truncate();

        // 3. Isi data baru (hanya 3)
        DB::table('ms_layanan')->insert([
            [
                'nama_layanan' => 'Fast Clean',
                'harga' => 30000,
                'deskripsi' => 'Pembersihan instan bagian luar sepatu. Estimasi 20-30 menit.',
            ],
            [
                'nama_layanan' => 'Deep Clean',
                'harga' => 50000,
                'deskripsi' => 'Pembersihan menyeluruh untuk semua bagian sepatu secara detail.',
            ],
            [
                'nama_layanan' => 'Unyellowing',
                'harga' => 60000,
                'deskripsi' => 'Perawatan khusus untuk menghilangkan noda kuning pada midsole.',
            ],
        ]);

        // 4. Aktifkan kembali pengecekan foreign key
        Schema::enableForeignKeyConstraints();
    }
}