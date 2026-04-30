<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Memanggil Seeder Layanan terlebih dahulu
        $this->call([
            LayananSeeder::class,
        ]);

        // 2. Mengisi Data User (Superadmin)
        User::create([
            'nama'      => 'superadmin',
            'email'     => 'superadmin@gmail.com',
            'password'  => Hash::make('superadmin123'),
            'id_role'   => 1, 
            'no_telp'   => '08888888888',
        ]);

        // 3. Mengisi Data User (Admin/Kasir)
        User::create([
            'nama'      => 'admin',
            'email'     => 'admin@gmail.com',
            'password'  => Hash::make('admin123'),
            'id_role'   => 2,
            'no_telp'   => '08999999999',
        ]);

        // 4. Mengisi Data User (Pelanggan)
        User::create([
            'nama'      => 'pelanggan',
            'email'     => 'pelanggan@gmail.com',
            'password'  => Hash::make('pelanggan123'),
            'id_role'   => 3,
            'no_telp'   => '08989898989',
        ]);

        $this->command->info('Database ROFF SHOECLEAN berhasil diisi data master!');
    }
}