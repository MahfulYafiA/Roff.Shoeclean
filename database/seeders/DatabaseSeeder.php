<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LayananSeeder::class,
        ]);

        User::create([
            'nama'      => 'Rofi\'i (Owner)',
            'email'     => 'superadmin@roff.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'superadmin',
            'no_telp'   => '08123456789',
        ]);

        User::create([
            'nama'      => 'Staf Kasir',
            'email'     => 'admin@roff.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'no_telp'   => '08555555555',
        ]);

        User::create([
            'nama'      => 'mahful',
            'email'     => 'mahful@gmail.com',
            'password'  => Hash::make('mahful123'),
            'role'      => 'pelanggan',
            'no_telp'   => '08987654321',
        ]);

        $this->command->info('Database ROFF SHOECLEAN berhasil diisi data master!');
    }
}