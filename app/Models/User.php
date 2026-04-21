<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Nama tabel sesuai ms_user di database
    protected $table = 'ms_user';
    
    // 2. Primary Key menggunakan 'id_user'
    protected $primaryKey = 'id_user';

    // 3. Timestamps aktif
    public $timestamps = true; 

    /**
     * 4. Daftar kolom yang boleh diisi (Mass Assignment)
     * ✅ UPDATE: Menambahkan 'is_active' untuk fitur kontrol admin
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'id_role',    
        'no_telp',
        'alamat',
        'foto_profil',
        'google_id',
        'is_active', // 🚨 TAMBAHKAN INI
    ];

    // 5. Sembunyikan data sensitif
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'is_active'  => 'boolean', // 🚨 TAMBAHKAN INI agar terbaca true/false
        ];
    }
}