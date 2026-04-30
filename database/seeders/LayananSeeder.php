<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            [
                'nama_layanan' => 'Fast Clean',
                'deskripsi' => 'Pembersihan kilat pada bagian luar (upper) dan midsole sepatu dalam waktu singkat dan cepat.',
                'harga' => 20000,
            ],
            [
                'nama_layanan' => 'Deep Clean',
                'deskripsi' => 'Pembersihan menyeluruh ke setiap sudut sepatu, meliputi bagian luar, dalam (insole), tali, hingga telapak (outsole).',
                'harga' => 25000,
            ],
            [
                'nama_layanan' => 'Unyellowing',
                'deskripsi' => 'Treatment khusus untuk mengembalikan warna sol sepatu karet yang menguning (oksidasi) menjadi kembali putih bersih seperti baru.',
                'harga' => 30000,
            ],
        ];

        foreach ($layanans as $layanan) {
            Layanan::create($layanan);
        }
    }
}