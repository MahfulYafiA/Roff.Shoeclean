<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ms_role')->insert([
            ['nama_role' => 'Superadmin'],
            ['nama_role' => 'Admin'],
            ['nama_role' => 'Customer'],
        ]);
    }
}